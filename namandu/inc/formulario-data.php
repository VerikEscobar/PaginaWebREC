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
            $where  = "AND CONCAT_WS(' ', f.id_formulario, f.descripcion, f.titulo) LIKE '%$search%'";
        }
        $db->setQuery("SELECT SQL_CALC_FOUND_ROWS f.id_formulario, f.fecha_formulario, f.titulo, f.descripcion, f.archivo, CASE f.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado, DATE_FORMAT(f.creacion,'%d/%m/%Y %H:%i:%s') AS fecha
            FROM formularios f
            WHERE 1=1 $where GROUP BY id_formulario ORDER BY $sort $order LIMIT $offset, $limit");
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

        $db->setQuery("UPDATE formularios SET estado=$status WHERE id_formulario=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;

    case 'cargar_editar':

        $db = DataBase::conectar();
        $db->autocommit(false);

        $fecha          = $db->clearText($_POST['fecha']);
        $titulo         = $db->clearText($_POST['titulo']);
        $descripcion    = $db->clearText($_POST['descripcion']);
        $dropurl        = $db->clearText($_POST['dropurl']);
        $files          = $_FILES['file'];
        $files_name     = $_FILES['file']['name'][0];

        if (empty($fecha)) {
            echo "Error. Ingrese la fecha del Formulario";
            exit;
        }

        if (empty($titulo)) {
            echo "Error. Ingrese el título del Formulario";
            exit;
        }

        if (empty($descripcion)) {
            echo "Error. Ingrese una descripcion del Formulario";
            exit;
        }

        if (empty($dropurl)) {
            echo "Error. El tipo de formulario esta vacio";
            exit;
        }

        if ($dropurl == "cargar") {

            if ($files_name == "blob") {
                echo "Error. Favor cargue el archivo";
                exit;
            }

            $db->setQuery("SELECT titulo FROM formularios WHERE titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. El formulario ya existe";
                exit;
            }

            $db->setQuery("INSERT INTO formularios (fecha_formulario, titulo, descripcion, creacion, estado) VALUES ('$fecha','$titulo','$descripcion', NOW(), 1)");

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
                            $targetPath = "../../archivos/multimedia/formularios/";
                            if (!is_dir($targetPath)) {
                                mkdir($targetPath, 0777, true);
                            }
                            $foo->file_new_name_body = md5($ultimo_id);
                            $foo->process($targetPath);
                            $archivo = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                            if ($foo->processed) {
                                $db->setQuery("UPDATE formularios SET archivo='$archivo' WHERE id_formulario=$ultimo_id");
                                if (!$db->alter()) {
                                    echo "Error al guardar el archivo. " . $db->getError();
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
                echo "Formulario registrado con éxito";
            }

        }

        if ($dropurl == "editar") {

            $hidden_id = $db->clearText($_POST['hidden_id']);

            $db->setQuery("SELECT id_formulario FROM formularios WHERE id_formulario NOT IN ($hidden_id) AND titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. El Formulario ya existe";
                exit;
            }

            $db->setQuery("UPDATE formularios SET fecha_formulario='$fecha',titulo='$titulo',descripcion='$descripcion' WHERE id_formulario='$hidden_id'");

            if (!$db->alter()) {
                echo "Error. " . $db->getError();
                $db->rollback();
                exit;
            } else {
                $db->setQuery("SELECT archivo FROM formularios WHERE id_formulario=$hidden_id");
                $row = $db->loadObject();
                if ($files_name == "blob" && empty($row->archivo)) {
                    echo "Error. Favor cargue el archivo";
                    exit;
                }
                if ($files_name !== "blob") {

                    foreach ($files as $k => $l) {
                        foreach ($l as $i => $v) {
                            if (!array_key_exists($i, $files)) {
                                $files[$i] = array();
                            }
                            $files[$i][$k] = $v;
                        }
                    }
                    $db->setQuery("SELECT archivo FROM formularios WHERE id_formulario=$hidden_id");
                    $row = $db->loadObject();
                    if (empty($row->archivo)) {
                        foreach ($files as $file) {
                            $foo = new \Verot\Upload\Upload($file);
                            if ($foo->uploaded) {
                                $targetPath = "../../archivos/multimedia/formularios/";
                                if (!is_dir($targetPath)) {
                                    mkdir($targetPath, 0777, true);
                                }
                                $foo->file_new_name_body = md5($hidden_id);
                                $foo->process($targetPath);
                                $archivo = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                                if ($foo->processed) {
                                    $db->setQuery("UPDATE formularios SET archivo='$archivo' WHERE id_formulario=$hidden_id");
                                    if (!$db->alter()) {
                                        echo "Error al guardar el archivo. " . $db->getError();
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
                        echo "Error. Ya tiene un archivo asociado";
                        $db->rollback();
                        exit;
                    }

                }
                $db->commit();
                echo "Formulario editado con éxito";
            }

        }

        break;

    case 'leer_fotos':
        $db       = DataBase::conectar();
        $id = $db->clearText($_POST['id']);
        $db->setQuery("SELECT archivo FROM formularios WHERE id_formulario=$id");
        $rows = $db->loadObjectList();
        if ($rows) {
            foreach ($rows as $r) {
                $size       = filesize("../../" . $r->archivo);
                $nombre_tmp = explode("/", $r->archivo);
                $nombre     = end($nombre_tmp);
                $path       = $r->archivo;
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

        $query = "SELECT archivo, id_formulario FROM formularios WHERE MD5(id_formulario)='$id_md5' AND archivo LIKE '%$foto%'";
        $db->setQuery($query);
        $rows       = $db->loadObject();
        $foto       = $rows->archivo;
        $id         = $rows->id_formulario;
        $db->setQuery("UPDATE formularios SET archivo=NULL WHERE id_formulario='$id'");
        if ($db->alter()) {
            echo "Formulario '$foto' eliminado correctamente";
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
        $db->setQuery("SELECT archivo FROM formularios WHERE id_formulario = $id");
        $row = $db->loadObject();
        if ($row->archivo) {
            unlink("../../" . $row->archivo);
        }
        $db->setQuery("DELETE FROM formularios WHERE id_formulario = $id");

        if ($db->alter()) {
            echo "Formulario eliminado correctamente";
        } else {
            echo "Error al eliminar el Formulario. " . $db->getError();
        }

        break;
}
