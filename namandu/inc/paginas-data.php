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
            $where  = "AND CONCAT_WS(' ', s.empresa, s.representante, s.email, s.telefono, s.web) LIKE '%$search%'";
        }
        $db->setQuery("SELECT SQL_CALC_FOUND_ROWS id_pagina, titulo, meta_descripcion, url, html,foto,estado, CASE estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado, DATE_FORMAT(creacion,'%d/%m/%Y %H:%i:%s') AS fecha
            FROM paginas
            WHERE 1=1 $where GROUP BY id_pagina ORDER BY $sort $order LIMIT $offset, $limit");
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

        $db->setQuery("UPDATE paginas SET estado=$status WHERE id_pagina=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;

    case 'cargar_editar':

        $db = DataBase::conectar();
        $db->autocommit(false);

        $titulo          = $db->clearText($_POST['titulo']);
        $metadescripcion = $db->clearText($_POST['metadescripcion']);
        $html            = $db->clearText($_POST['editor']);
        $dropurl         = $db->clearText($_POST['dropurl']);
        $files           = $_FILES['file'];
        $files_name      = $_FILES['file']['name'][0];
        $url             = url_amigable($titulo);

        if (empty($titulo)) {
            echo "Error. Ingrese el Título de la página";
            exit;
        }

        if (empty($metadescripcion)) {
            echo "Error. Ingrese una descripción breve de la página";
            exit;
        }

        if (empty($html)) {
            echo "Error. Ingrese el contenido de la página";
            exit;
        }

        if (empty($dropurl)) {
            echo "Error. El tipo de formulario esta vacio";
            exit;
        }

        if ($dropurl == "cargar") {

            $db->setQuery("SELECT titulo FROM paginas WHERE titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. La Página ya existe";
                exit;
            }

            $db->setQuery("INSERT INTO paginas (titulo, meta_descripcion, url, html, creacion) VALUES ('$titulo','$metadescripcion','$url','$html', NOW())");

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
                            $targetPath = "../../archivos/multimedia/paginas/";
                            if (!is_dir($targetPath)) {
                                mkdir($targetPath, 0777, true);
                            }
                            $foo->file_new_name_body = md5($ultimo_id);
                            $foo->image_convert      = jpg;
                            $foo->image_resize       = true;
                            $foo->image_ratio_crop   = true;
                            $foo->image_y            = 230;
                            $foo->image_x            = 1920;
                            $foo->process($targetPath);
                            $foto = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                            if ($foo->processed) {
                                $db->setQuery("UPDATE paginas SET foto='$foto' WHERE id_pagina=$ultimo_id");
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
                echo "Página registrada con éxito";
            }

        }

        if ($dropurl == "editar") {

            $hidden_id = $db->clearText($_POST['hidden_id']);

            $db->setQuery("SELECT id_pagina FROM paginas WHERE id_pagina NOT IN ($hidden_id) AND titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. La Página ya existe";
                exit;
            }

            $db->setQuery("UPDATE paginas SET titulo='$titulo', meta_descripcion='$metadescripcion', url='$url', html='$html' WHERE id_pagina='$hidden_id'");

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
                    $db->setQuery("SELECT foto FROM paginas WHERE id_pagina=$hidden_id");
                    $rowfoto = $db->loadObject();
                    if (empty($rowfoto->foto)) {
                        foreach ($files as $file) {
                            $foo = new \Verot\Upload\Upload($file);
                            if ($foo->uploaded) {
                                $targetPath = "../../archivos/multimedia/paginas/";
                                if (!is_dir($targetPath)) {
                                    mkdir($targetPath, 0777, true);
                                }
                                $foo->file_new_name_body = md5($hidden_id);
                                $foo->image_convert      = jpg;
                                $foo->image_resize       = true;
                                $foo->image_ratio_crop   = true;
                                $foo->image_y            = 230;
                                $foo->image_x            = 1920;
                                $foo->process($targetPath);
                                $foto = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                                if ($foo->processed) {
                                    $db->setQuery("UPDATE paginas SET foto='$foto' WHERE id_pagina=$hidden_id");
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
                        echo "Error. La página ya posee banner";
                        $db->rollback();
                        exit;
                    }

                }
                $db->commit();
                echo "Página editada con éxito";
            }

        }

        break;

    case 'leer_fotos':
        $db        = DataBase::conectar();
        $id_pagina = $db->clearText($_POST['id_pagina']);
        $db->setQuery("SELECT foto FROM paginas WHERE id_pagina=$id_pagina");
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

        $query = "SELECT foto, id_pagina FROM paginas WHERE MD5(id_pagina)='$id_md5' AND foto LIKE '%$foto%'";
        $db->setQuery($query);
        $rows      = $db->loadObject();
        $foto      = $rows->foto;
        $id_pagina = $rows->id_pagina;
        $db->setQuery("UPDATE paginas SET foto=NULL WHERE id_pagina='$id_pagina'");
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
        $db->setQuery("SELECT foto FROM paginas WHERE id_pagina = $id");
        $row = $db->loadObject();
        if ($row->foto) {
            unlink("../../" . $row->foto);
        }
        $db->setQuery("DELETE FROM paginas WHERE id_pagina = $id");

        if ($db->alter()) {
            echo "Página '$nombre' eliminado/a correctamente";
        } else {
            echo "Error al eliminar '$nombre'. " . $db->getError();
        }

        break;
}
