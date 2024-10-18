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
            $where  = "AND CONCAT_WS(' ', d.id_documento, d.descripcion, d.titulo, d.numero, dc.categoria) LIKE '%$search%'";
        }
        $db->setQuery("SELECT SQL_CALC_FOUND_ROWS d.id_documento, d.fecha_documento, d.titulo, d.numero, d.descripcion, d.documento, dc.categoria, dc.id_documento_categoria , CASE d.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado, DATE_FORMAT(d.creacion,'%d/%m/%Y %H:%i:%s') AS fecha
            FROM documentos d
            LEFT JOIN documentos_categorias dc ON dc.id_documento_categoria=d.id_documento_categoria
            WHERE 1=1 $where GROUP BY id_documento ORDER BY $sort $order LIMIT $offset, $limit");
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

        $db->setQuery("UPDATE documentos SET estado=$status WHERE id_documento=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;

    case 'cargar_editar':

        $db = DataBase::conectar();
        $db->autocommit(false);

        $categoria      = $db->clearText($_POST['categoria']);
        $fecha          = $db->clearText($_POST['fecha']);
        $titulo         = $db->clearText($_POST['titulo']);
        $numero         = $db->clearText($_POST['numero']);
        $descripcion    = $db->clearText($_POST['descripcion']);
        $dropurl        = $db->clearText($_POST['dropurl']);
        $files          = $_FILES['file'];
        $files_name     = $_FILES['file']['name'][0];


        if (empty($categoria)) {
            echo "Error. Ingrese la categoria del documento";
            exit;
        }

        if (empty($fecha)) {
            echo "Error. Ingrese la fecha del documento";
            exit;
        }

        if (empty($titulo)) {
            echo "Error. Ingrese el título del documento";
            exit;
        }

        if (empty($numero)) {
            echo "Error. Ingrese el número del documento";
            exit;
        }

        if (empty($descripcion)) {
            echo "Error. Ingrese una descripcion del documento";
            exit;
        }

        if (empty($dropurl)) {
            echo "Error. El tipo de formulario esta vacio";
            exit;
        }

        if ($dropurl == "cargar") {

            if ($files_name == "blob") {
                echo "Error. Favor cargue el documento";
                exit;
            }

            $db->setQuery("SELECT titulo FROM documentos WHERE titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. El documento ya existe";
                exit;
            }

            $db->setQuery("INSERT INTO documentos (id_documento_categoria, fecha_documento, titulo, numero, descripcion, creacion) VALUES ('$categoria','$fecha','$titulo','$numero','$descripcion', NOW())");

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
                            $targetPath = "../../archivos/multimedia/documentos/";
                            if (!is_dir($targetPath)) {
                                mkdir($targetPath, 0777, true);
                            }
                            $foo->file_new_name_body = md5($ultimo_id);
                            $foo->process($targetPath);
                            $documento = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                            if ($foo->processed) {
                                $db->setQuery("UPDATE documentos SET documento='$documento' WHERE id_documento=$ultimo_id");
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
                echo "Documento registrado con éxito";
            }

        }

        if ($dropurl == "editar") {

            $hidden_id = $db->clearText($_POST['hidden_id']);

            $db->setQuery("SELECT id_documento FROM documentos WHERE id_documento NOT IN ($hidden_id) AND titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. El documento ya existe";
                exit;
            }

            $db->setQuery("UPDATE documentos SET id_documento_categoria='$categoria',fecha_documento='$fecha',titulo='$titulo',numero='$numero',descripcion='$descripcion' WHERE id_documento='$hidden_id'");

            if (!$db->alter()) {
                echo "Error. " . $db->getError();
                $db->rollback();
                exit;
            } else {
                $db->setQuery("SELECT documento FROM documentos WHERE id_documento=$hidden_id");
                $row = $db->loadObject();
                if ($files_name == "blob" && empty($row->documento)) {
                    echo "Error. Favor cargue el documento";
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
                    $db->setQuery("SELECT documento FROM documentos WHERE id_documento=$hidden_id");
                    $row = $db->loadObject();
                    if (empty($row->documento)) {
                        foreach ($files as $file) {
                            $foo = new \Verot\Upload\Upload($file);
                            if ($foo->uploaded) {
                                $targetPath = "../../archivos/multimedia/documentos/";
                                if (!is_dir($targetPath)) {
                                    mkdir($targetPath, 0777, true);
                                }
                                $foo->file_new_name_body = md5($hidden_id);
                                $foo->process($targetPath);
                                $documento = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                                if ($foo->processed) {
                                    $db->setQuery("UPDATE documentos SET documento='$documento' WHERE id_documento=$hidden_id");
                                    if (!$db->alter()) {
                                        echo "Error al guardar el documento. " . $db->getError();
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
                        echo "Error. Ya tiene un documento asociado";
                        $db->rollback();
                        exit;
                    }

                }
                $db->commit();
                echo "Documento editado con éxito";
            }

        }

        break;

    case 'ver_categorias':
        $db           = DataBase::conectar();
        $db->setQuery("SELECT categoria, id_documento_categoria
            FROM documentos_categorias ORDER BY id_documento_categoria ASC");
        $rows = $db->loadObjectList();
        echo json_encode($rows);
        break;

    case 'leer_fotos':
        $db       = DataBase::conectar();
        $id = $db->clearText($_POST['id']);
        $db->setQuery("SELECT documento FROM documentos WHERE id_documento=$id");
        $rows = $db->loadObjectList();
        if ($rows) {
            foreach ($rows as $r) {
                $size       = filesize("../../" . $r->documento);
                $nombre_tmp = explode("/", $r->documento);
                $nombre     = end($nombre_tmp);
                $path       = $r->documento;
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

        $query = "SELECT documento, id_documento FROM documentos WHERE MD5(id_documento)='$id_md5' AND documento LIKE '%$foto%'";
        $db->setQuery($query);
        $rows       = $db->loadObject();
        $foto       = $rows->documento;
        $id         = $rows->id_documento;
        $db->setQuery("UPDATE documentos SET documento=NULL WHERE id_documento='$id'");
        if ($db->alter()) {
            echo "Documento '$foto' eliminado correctamente";
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
        $db->setQuery("SELECT documento FROM documentos WHERE id_documento = $id");
        $row = $db->loadObject();
        if ($row->documento) {
            unlink("../../" . $row->documento);
        }
        $db->setQuery("DELETE FROM documentos WHERE id_documento = $id");

        if ($db->alter()) {
            echo "Documento eliminado correctamente";
        } else {
            echo "Error al eliminar el documento. " . $db->getError();
        }

        break;
}
