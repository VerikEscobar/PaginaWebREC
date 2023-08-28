<?php
include "funciones.php";
include "class.upload.php";
$q           = $_REQUEST['q'];
$usuario     = $auth->getUsername();
$id_sucursal = datosUsuario($usuario)->id_sucursal;
switch ($q) {

    case 'ver':

        $db    = DataBase::conectar();
        $where = "";
        $limit  = $_REQUEST['limit'];
        $offset = $_REQUEST['offset'];
        $order = $_REQUEST['order'];
        $sort  = $_REQUEST['sort'];
        if (!isset($sort)) {
            $sort = 2;
        }

        if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $where  = "AND CONCAT_WS(' ', a.nombre, a.id_autoridad_cargo, a.descripcion) LIKE '%$search%'";
        }
        $db->setQuery("SELECT SQL_CALC_FOUND_ROWS a.id_autoridad, a.nombre, a.id_autoridad_cargo, ac.cargo, a.descripcion, a.foto, a.creacion, CASE a.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado, DATE_FORMAT(a.creacion,'%d/%m/%Y %H:%i:%s') AS fecha
            FROM autoridades a
            LEFT JOIN autoridades_cargos ac ON ac.id_autoridad_cargo=a.id_autoridad_cargo
            WHERE 1=1 $where GROUP BY a.id_autoridad ORDER BY $sort $order LIMIT $offset, $limit");
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

        $db->setQuery("UPDATE autoridades SET estado=$status WHERE id_autoridad=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;

    case 'cargar_editar':

        $db = DataBase::conectar();
        $db->autocommit(false);

        $nombre      = $db->clearText($_POST['nombre']);
        $cargo       = $db->clearText($_POST['cargo']);
        $descripcion = $db->clearText($_POST['editor']);
        $dropurl     = $db->clearText($_POST['dropurl']);
        $files       = $_FILES['file'];
        $files_name  = $_FILES['file']['name'][0];

        if (empty($nombre)) {
            echo "Error. Ingrese el nombre de la autoridad";
            exit;
        }

        if (empty($cargo)) {
            echo "Error. Ingrese un cargo";
            exit;
        }

        if (empty($dropurl)) {
            echo "Error. El tipo de formulario esta vacio";
            exit;
        }

        if (empty($files)) {
            echo "Error. Adjunte una foto de la autoridad";
            exit;
        }

        if ($dropurl == "cargar") {

            $db->setQuery("SELECT nombre FROM autoridades WHERE nombre='$nombre'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. La Autoridad ya existe";
                exit;
            }

            $db->setQuery("INSERT INTO autoridades (nombre, id_autoridad_cargo, descripcion, creacion, estado) VALUES ('$nombre','$cargo','$descripcion', NOW(),1)");

            if (!$db->alter()) {
                echo "Error. " . $db->getError();
                $db->rollback();
                exit;
            } else {

                $ultimo_id = $db->getLastID();

                if ($files_name !== "blob") {
                    foreach ($files as $k => $l) {
                        foreach ($l as $i => $v) {
                            if (!array_key_exists($i, $files)) {
                                $files[$i] = array();
                            }
                            $files[$i][$k] = $v;
                        }
                    }
                    foreach ($files as $file) {
                        $foo = new \Verot\Upload\Upload($file);
                        if ($foo->uploaded) {
                            $targetPath = "../../archivos/multimedia/autoridades/";
                            if (!is_dir($targetPath)) {
                                mkdir($targetPath, 0777, true);
                            }
                            $foo->file_new_name_body = md5($ultimo_id);
                            $foo->image_convert      = jpg;
                            $foo->image_resize       = true;
                            $foo->image_ratio_crop   = true;
                            $foo->image_y            = 360;
                            $foo->image_x            = 360;
                            $foo->process($targetPath);
                            $foto = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                            if ($foo->processed) {
                                $db->setQuery("UPDATE autoridades SET foto='$foto' WHERE id_autoridad=$ultimo_id");
                                if (!$db->alter()) {
                                    echo "Error al guardar la Imagen. " . $db->getError();
                                    $db->rollback();
                                    exit;
                                }
                                $foo->clean();
                            } else {
                                echo 'Error : ' . $foo->error;
                                $db->rollback();
                                exit;
                            }
                        }
                    }
                }
                $db->commit();
                echo "Autoridad registrada con éxito";
            }

        }

        if ($dropurl == "editar") {

            $id_hidden = $db->clearText($_POST['hidden_id']);

            $db->setQuery("SELECT id_autoridad FROM autoridades WHERE id_autoridad NOT IN ($id_hidden) AND nombre='$nombre'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. La Autoridad ya existe";
                exit;
            }

            $db->setQuery("UPDATE autoridades SET nombre='$nombre', id_autoridad_cargo='$cargo', descripcion='$descripcion' WHERE id_autoridad='$id_hidden'");

            if (!$db->alter()) {
                echo "Error. " . $db->getError();
                $db->rollback();
                exit;
            } else {

                if ($files_name !== "blob") {
                    foreach ($files as $k => $l) {
                        foreach ($l as $i => $v) {
                            if (!array_key_exists($i, $files)) {
                                $files[$i] = array();
                            }
                            $files[$i][$k] = $v;
                        }
                    }
                    $db->setQuery("SELECT foto FROM autoridades WHERE id_autoridad=$id_hidden");
                    $rowfoto = $db->loadObject();
                    if (empty($rowfoto->foto)) {
                        foreach ($files as $file) {
                            $foo = new \Verot\Upload\Upload($file);
                            if ($foo->uploaded) {
                                $targetPath = "../../archivos/multimedia/autoridades/";
                                if (!is_dir($targetPath)) {
                                    mkdir($targetPath, 0777, true);
                                }
                                $foo->file_new_name_body = md5($id_hidden);
                                $foo->image_convert      = jpg;
                                $foo->image_resize       = true;
                                $foo->image_ratio_crop   = true;
                                $foo->image_y            = 360;
                                $foo->image_x            = 360;
                                $foo->process($targetPath);
                                $foto = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                                if ($foo->processed) {
                                    $db->setQuery("UPDATE autoridades SET foto='$foto' WHERE id_autoridad=$id_hidden");
                                    if (!$db->alter()) {
                                        echo "Error al guardar la Imagen. " . $db->getError();
                                        $db->rollback();
                                        exit;
                                    }
                                    $foo->clean();
                                } else {
                                    echo 'Error : ' . $foo->error;
                                    $db->rollback();
                                    exit;
                                }
                            }
                        }
                    } else {
                        echo "Error. La Autoridad ya posee una foto";
                        $db->rollback();
                        exit;
                    }

                }
                $db->commit();
                echo "Autoridad editada con éxito";
            }

        }

        break;

    case 'ver_cargos':
        $db           = DataBase::conectar();
        $db->setQuery("SELECT cargo, id_autoridad_cargo
            FROM autoridades_cargos ORDER BY orden ASC");
        $rows = $db->loadObjectList();
        echo json_encode($rows);
        break;

    case 'leer_fotos':
        $db           = DataBase::conectar();
        $id = $db->clearText($_POST['id']);
        $db->setQuery("SELECT foto FROM autoridades WHERE id_autoridad=$id");
        $rows = $db->loadObjectList();
        if ($rows) {
            foreach ($rows as $r) {
                $size       = filesize("../../" . $r->foto);
                $nombre_tmp = explode("/", $r->foto);
                $nombre     = end($nombre_tmp);
                $path       = $r->foto;
                $salida[]   = ['name' => $nombre, 'size' => $size, 'path' => "../" . $path];
            }
        }
        echo json_encode($salida);
        break;

    case 'borrar_fotos':
        $success   = false;
        $foto      = $_POST['foto'];
        $db        = DataBase::conectar();
        $fotoArray = explode(".", $foto);
        $id_md5    = $fotoArray[0];

        $query = "SELECT foto, id_autoridad FROM autoridades WHERE MD5(id_autoridad)='$id_md5' AND foto LIKE '%$foto%'";
        $db->setQuery($query);
        $rows         = $db->loadObject();
        $foto         = $rows->foto;
        $id           = $rows->id_autoridad;
        $db->setQuery("UPDATE autoridades SET foto=NULL WHERE id_autoridad='$id'");
        if ($db->alter()) {
            echo "Imagen '$foto' eliminada correctamente";
            unlink("../../" . $foto);
        } else {
            echo "Error al eliminar '$foto'. " . $db->getError();
        }
        break;

    case 'eliminar':

        $db      = DataBase::conectar();
        $success = false;
        $id      = $db->clearText($_POST['id']);
        $nombre  = $db->clearText($_POST['nombre']);
        $db->setQuery("SELECT foto FROM autoridades WHERE id_autoridad = $id");
        $row = $db->loadObject();
        if ($row->foto) {
            unlink("../../" . $row->foto);
        }
        $db->setQuery("DELETE FROM autoridades WHERE id_autoridad = $id");
        if ($db->alter()) {
            echo "Autoridad '$nombre' eliminado/a correctamente";
        } else {
            echo "Error al eliminar '$nombre'. " . $db->getError();
        }

        break;
}
