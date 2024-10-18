<?php
include "funciones.php";
include 'class.upload.php';
$q           = $_REQUEST['q'];
$usuario     = $auth->getUsername();
$id_sucursal = datosUsuario($usuario)->id_sucursal;
switch ($q) {

    case 'ver':

        $db     = DataBase::conectar();
        $where  = "";
        $limit  = $_REQUEST['limit'];
        $offset = $_REQUEST['offset'];
        $order  = $_REQUEST['order'];
        $sort   = $_REQUEST['sort'];

        if (!isset($sort)) {
            $sort = 2;
        }

        if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $where  = "AND CONCAT_WS(' ', sr.nro_oficina, sr.nombre, sr.cargo, sr.direccion, sr.telefono) LIKE '%$search%'";
        }

        $db->setQuery("SELECT SQL_CALC_FOUND_ROWS sr.id_sede_responsable, sr.nro_oficina,sr.foto, sr.nombre, sr.cargo, sr.interino, sr.direccion, sr.email, sr.telefono, s.id_sede, s.oficina, DATE_FORMAT(sr.creacion,'%d/%m/%Y %H:%i:%s') AS fecha, CASE sr.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado
            FROM sedes_responsables sr
            LEFT JOIN sedes_sere ss ON sr.id_sede_responsable = ss.id_sede_responsable
            LEFT JOIN sedes s ON ss.id_sede = s.id_sede
            WHERE 1=1 $where ORDER BY $sort $order LIMIT $offset, $limit");
        $rows = $db->loadObjectList();

        $db->setQuery("SELECT FOUND_ROWS() as total");
        $total_row = $db->loadObject();
        $total     = $total_row->total;

        if ($rows) {
            $salida = array('total' => $total, 'rows' => $rows);
        } else {
            $salida = array('total' => 0, 'rows' => array());
        }

        echo json_encode($salida);

        break;

    case 'cambiar-estado':
        $db = DataBase::conectar();
        $id = $db->clearText($_POST['id']);

        $status = $db->clearText($_POST['estado']);

        $db->setQuery("UPDATE sedes_responsables SET estado=$status WHERE id_sede_responsable=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;

    /*case 'ver_sede':
        $db          = DataBase::conectar();
        $page        = $db->clearText($_GET['page']);
        $term        = $db->clearText($_GET['term']);
        $resultCount = 5;
        $end         = ($page - 1) * $resultCount;

        $db->setQuery("SELECT s.oficina, s.id_sede FROM sedes s
        WHERE (s.oficina LIKE '%$term%') GROUP BY s.oficina ORDER BY s.oficina ASC LIMIT $end, $resultCount");

        $rows = $db->loadObjectList();
        $db->setQuery("SELECT FOUND_ROWS() as total");
        $total_row = $db->loadObject();
        $count     = $total_row->total;
        if ($rows) {
            foreach ($rows as $r) {
                $salida[] = ['id_sede' => $r->id_sede, 'oficina' => $r->oficina, 'total_count' => $count];
            }
        } else {
            $salida[] = ['id_sede' => '', 'oficina' => '', 'total_count' => ''];
        }
        echo json_encode($salida);
        break;*/

    case 'subir-foto':
        $db     = DataBase::conectar();
        $id     = $db->clearText($_POST['id']);
        $imagen = $db->clearText($_POST['imagen']);

        if (empty($id)) {
            echo "Error. Ingrese un ID";
            exit;
        }
        if (empty($imagen)) {
            echo "Error. Ingrese una Foto";
            exit;
        }
        $db->setQuery("SELECT foto FROM sedes_responsables WHERE id_sede_responsable=$id");
        $row = $db->loadObject();
        if ($row) {
            unlink("../../" . $row->foto);
        }

        $foo = new \Verot\Upload\Upload($imagen);
        if ($foo->uploaded) {
            $targetPath = "../../archivos/multimedia/responsables/";
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0777, true);
            }
            $foo->file_new_name_body = md5($id);
            $foo->image_convert      = jpg;
            $foo->image_ratio_y      = true;
            $foo->image_resize       = true;
            $foo->image_x            = 240;
            $foo->image_y            = 240;
            $foo->process($targetPath);
            $foto = str_replace("../", "", $targetPath . $foo->file_dst_name);
            if ($foo->processed) {
                $db->setQuery("UPDATE sedes_responsables SET foto='$foto' WHERE id_sede_responsable=$id");
                if (!$db->alter()) {
                    echo "Error al guardar foto de perfil. " . $db->getError();
                    exit;
                } else {
                    echo "Foto cargada con éxito";
                }

                $foo->clean();
            } else {
                echo 'Error. ' . $foo->error;
            }
        }

        break;

    case 'borrar_fotos':
        $db      = DataBase::conectar();
        $success = false;
        $id      = $db->clearText($_POST['id']);

        $db->setQuery("SELECT foto FROM sedes_responsables WHERE id_sede_responsable = $id");
        $row = $db->loadObject();
        if ($row->foto) {
            unlink("../../" . $row->foto);
        }

        $db->setQuery("UPDATE sedes_responsables SET foto='' WHERE id_sede_responsable=$id");

        if ($db->alter()) {
            echo "Foto de perfil eliminado correctamente";
        } else {
            echo "Error al eliminar '$foto'. " . $db->getError();
        }

        break;

    case 'cargar':

        $db     = DataBase::conectar();
        $db->autocommit(FALSE);
        $nombre = mb_convert_case($db->clearText($_POST['nombre']), MB_CASE_UPPER, "UTF-8");
        $cargo  = mb_convert_case($db->clearText($_POST['cargo']), MB_CASE_UPPER, "UTF-8");
        $email  = $db->clearText($_POST['email']);
        /*$sede  = $db->clearText($_POST['sede']);*/
        $imagen = $db->clearText($_POST['foto']);

        if (empty($nombre)) {
            echo "Error. Ingrese un Nombre y Apellido";
            exit;
        }
        if (empty($cargo)) {
            echo "Error. Ingrese un cargo";
            exit;
        }

        $db->setQuery("SELECT nombre FROM sedes_responsables WHERE nombre='$nombre'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. El responsable no se debe repetir";
            exit;
        }

        $db->setQuery("INSERT INTO sedes_responsables (nro_oficina, nombre, cargo, interino, direccion, telefono, foto, email, creacion) VALUES ('-','$nombre','$cargo','-','-','-','','$email',NOW())");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
            $db->rollback();  //Revertimos los cambios
            exit;
        } 

        /*$id_sede_responsable = $db->getLastID();//obtiene el ultimo id usado ya sea en insert, update o delete
        $db->setQuery("INSERT INTO sedes_sere (id_sede ,id_sede_responsable) VALUES ('$sede','$id_sede_responsable')");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
            $db->rollback();  //Revertimos los cambios
            exit;
        }*/

        else {
            
            $ultimo_id = $db->getLastID();
            if ($imagen) {
                $foo = new \Verot\Upload\Upload($imagen);
                if ($foo->uploaded) {
                    $targetPath = "../../archivos/multimedia/responsables/";
                    if (!is_dir($targetPath)) {
                        mkdir($targetPath, 0777, true);
                    }
                    $foo->file_new_name_body = md5($ultimo_id);
                    $foo->image_convert      = jpg;
                    $foo->image_ratio_y      = true;
                    $foo->image_resize       = true;
                    $foo->image_x            = 240;
                    $foo->image_y            = 240;
                    $foo->process($targetPath);
                    $foto = str_replace("../", "", $targetPath . $foo->file_dst_name);
                    if ($foo->processed) {
                        $db->setQuery("UPDATE sedes_responsables SET foto='$foto' WHERE id_sede_responsable=$ultimo_id");
                        if (!$db->alter()) {
                            echo "Error al guardar foto de perfil. " . $db->getError();
                            $db->rollback();  //Revertimos los cambios
                            exit;
                        } else {
                            //echo "Foto cargada con éxito";
                        }

                        $foo->clean();
                    } else {
                        echo 'Error. ' . $foo->error;
                    }
                }
            }
        }
        $db->commit();
        echo "Responsable registrado correctamente";

        break;

    case 'editar':

        $db     = DataBase::conectar();
        $db->autocommit(FALSE);
        $id     = $db->clearText($_POST['hidden_id']);
        $nombre = mb_convert_case($db->clearText($_POST['nombre']), MB_CASE_UPPER, "UTF-8");
        $cargo  = mb_convert_case($db->clearText($_POST['cargo']), MB_CASE_UPPER, "UTF-8");
        $email  = $db->clearText($_POST['email']);
        /*$sede  = $db->clearText($_POST['sede']);*/
        $imagen = $db->clearText($_POST['foto']);

        if (empty($id)) {
            echo "Error. Ingrese el id";
            exit;
        }

        if (empty($nombre)) {
            echo "Error. Ingrese un Nombre y Apellido";
            exit;
        }
        if (empty($cargo)) {
            echo "Error. Ingrese un cargo";
            exit;
        }

        $db->setQuery("SELECT nombre FROM sedes_responsables WHERE id_sede_responsable NOT IN ($id) AND nombre='$nombre'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. El Responsable no se debe repetir";
            exit;
        }

        $db->setQuery("UPDATE sedes_responsables SET nombre='$nombre',cargo='$cargo',email='$email' WHERE id_sede_responsable=$id");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
            $db->rollback();  //Revertimos los cambios
            exit;
        }

        /*$db->setQuery("DELETE FROM sedes_sere WHERE id_sede_responsable=$id");
        if (!$db->alter()) {
            echo "Error. " . $db->getError();
            $db->rollback();  //Revertimos los cambios
            exit;
        }

        $db->setQuery("INSERT INTO sedes_sere (id_sede, id_sede_responsable) VALUES ('$sede','$id')");
        if (!$db->alter()) {
            echo "Error. " . $db->getError();
            $db->rollback();  //Revertimos los cambios
            exit;
        }*/
         else {
            
            if ($imagen != "data:,") {
                $db->setQuery("SELECT foto FROM sedes_responsables WHERE id_sede_responsable = $id");
                $row = $db->loadObject();
                if ($row->foto) {
                    unlink("../../" . $row->foto);
                }
                $foo = new \Verot\Upload\Upload($imagen);
                if ($foo->uploaded) {
                    $targetPath = "../../archivos/multimedia/responsables/";
                    if (!is_dir($targetPath)) {
                        mkdir($targetPath, 0777, true);
                    }
                    $foo->file_new_name_body = md5($id);
                    $foo->image_convert      = jpg;
                    $foo->image_ratio_y      = true;
                    $foo->image_resize       = true;
                    $foo->image_x            = 240;
                    $foo->image_y            = 240;
                    $foo->process($targetPath);
                    $foto = str_replace("../", "", $targetPath . $foo->file_dst_name);
                    if ($foo->processed) {

                        $db->setQuery("UPDATE sedes_responsables SET foto='$foto' WHERE id_sede_responsable=$id");
                        if (!$db->alter()) {
                            echo "Error al guardar foto de perfil. " . $db->getError();
                            $db->rollback();  //Revertimos los cambios
                            exit;
                        } else {
                            //echo "Foto cargada con éxito";
                        }

                        $foo->clean();
                    } else {
                        echo 'Error. ' . $foo->error;
                    }
                }
            }

        }
        
        $db->commit();
        echo "Responsable modificado correctamente";

        break;

    case 'eliminar':

        $db      = DataBase::conectar();
        $db->autocommit(FALSE);
        $success = false;
        $id      = $db->clearText($_POST['id']);
        $nombre  = $db->clearText($_POST['nombre']);

        if (empty($id)) {
            echo "Error. Ingrese un ID";
            exit;
        }
        $db->setQuery("SELECT foto FROM sedes_responsables WHERE id_sede_responsable = $id");
        $row = $db->loadObject();
        if ($row->foto) {
            unlink("../../" . $row->foto);
        }

        $db->setQuery("DELETE FROM sedes_responsables WHERE id_sede_responsable=$id");

        if ($db->alter()) {
            echo "Responsable eliminado correctamente";
            $db->rollback();  //Revertimos los cambios
            exit;
        } 

        /*$id_sede_responsable = $db->getLastID();//obtiene el ultimo id usado ya sea en insert, update o delete
        $db->setQuery("DELETE FROM sedes_sere WHERE id_sede_responsable=$id");

        if ($db->alter()) {
            echo ".";
            $db->rollback();  //Revertimos los cambios
            exit;
        }

        else {
            echo "Error al eliminar '$nombre'. " . $db->getError();
        }*/

        break;
}
