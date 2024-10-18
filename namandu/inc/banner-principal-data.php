<?php
include "funciones.php";
include "class.upload.php";
$q           = $_REQUEST['q'];
$usuario     = $auth->getUsername();
$id_sucursal = datosUsuario($usuario)->id_sucursal;
switch ($q) {
    case 'ver':
        $db = DataBase::conectar();
        $db->setQuery("SELECT SQL_CALC_FOUND_ROWS id_banner, url, url_nombre, imagen,titulo,descripcion, DATE_FORMAT(creacion,'%d/%m/%Y %H:%i:%s') AS fecha, orden, estado, CASE estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado
                        FROM banner
                        ORDER BY id_banner");
        $rows = $db->loadObjectList();
        echo json_encode($rows);
        break;
    case 'cargar_editar':
        $db          = DataBase::conectar();
        $url         = $db->clearText($_POST['url']);
        $url_nombre  = $db->clearText($_POST['url_nombre']);
        $orden       = ($db->clearText($_POST['orden']))?: 0;
        $descripcion = $db->clearText($_POST['descripcion']);
        $titulo      = $db->clearText($_POST['titulo']);
        $estado      = $db->clearText($_POST['estado']);
        $dropurl     = $db->clearText($_POST['dropurl']);
        $categoria   = mb_convert_case("$categoria", MB_CASE_UPPER, "UTF-8");
        if (empty($orden)) {
            echo "Error. Ingrese un orden";
            exit;
        }
        if ($dropurl == "cargar") {
            $db->setQuery("INSERT INTO banner (url, url_nombre, orden, estado, titulo, descripcion, creacion) VALUES ('$url','$url_nombre','$orden',1,'$titulo','$descripcion', NOW())");
            if (!$db->alter()) {
                echo "Error. " . $db->getError();
                exit;
            }
            $ultimo_id = $db->getLastID();
            $db->setQuery("UPDATE banner SET orden=0 where orden=$orden");
            if ($db->alter()) {
                $db->setQuery("UPDATE banner SET orden='$orden' where id_banner=$ultimo_id");
                if (!$db->alter()) {
                    echo "Error. " . $db->getError();
                    exit;
                }
            }
            $files      = $_FILES['file'];
            $files_name = $_FILES['file']['name'][0];
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
                        $targetPath = "../../archivos/multimedia/banner/";
                        if (!is_dir($targetPath)) {
                            mkdir($targetPath, 0777, true);
                        }
                        $foo->file_new_name_body = "banner_" . $ultimo_id;
                        $foo->image_convert      = jpg;
                        //$foo->image_ratio_x      = true;
                        $foo->image_ratio_crop      = true;
                        $foo->image_resize = true;
                        $foo->image_x      = 1920;
                        $foo->image_y      = 630;
                        $foo->process($targetPath);
                        $foto = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                        if ($foo->processed) {
                            $id_banner = $ultimo_id;
                            $db->setQuery("UPDATE banner SET imagen='$foto' WHERE id_banner = '$id_banner'");
                            if (!$db->alter()) {
                                echo alertDismiss("Error al guardar las Imagenes. " . $db->getError(), "error");
                                exit;
                            }
                            $foo->clean();
                        } else {
                            echo 'error : ' . $foo->error;
                        }
                    }
                }
            }
            echo "Banner registrado correctamente";
        } else {
            $id_banner = $_POST['hidden_id'];
            $db->setQuery("UPDATE banner SET url='$url', url_nombre='$url_nombre', orden='$orden', titulo='$titulo', descripcion='$descripcion' WHERE id_banner = '$id_banner'");
            if (!$db->alter()) {
                echo "Error. " . $db->getError();
                exit;
            }
            $files      = $_FILES['file'];
            $files_name = $_FILES['file']['name'][0];
            $db->setQuery("UPDATE banner SET orden=0 where orden=$orden");
            if ($db->alter()) {
                $db->setQuery("UPDATE banner SET orden='$orden' where id_banner=$id_banner");
                if (!$db->alter()) {
                    echo "Error. " . $db->getError();
                    exit;
                }
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
                foreach ($files as $file) {
                    $foo = new \Verot\Upload\Upload($file);
                    if ($foo->uploaded) {
                        $targetPath = "../../archivos/multimedia/banner/";
                        if (!is_dir($targetPath)) {
                            mkdir($targetPath, 0777, true);
                        }
                        $foo->file_new_name_body = "banner_" . $id_banner;
                        $foo->image_convert      = jpg;
                        //$foo->image_ratio_x      = true;
                        $foo->image_ratio_crop      = true;
                        $foo->image_resize = true;
                        $foo->image_x      = 1920;
                        $foo->image_y      = 630;
                        $foo->process($targetPath);
                        $foto = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                        if ($foo->processed) {
                            $db->setQuery("UPDATE banner SET imagen='$foto' WHERE id_banner = '$id_banner'");
                            if (!$db->alter()) {
                                echo alertDismiss("Error al guardar las Imagenes. " . $db->getError(), "error");
                                exit;
                            }
                            $foo->clean();
                        } else {
                            echo 'error : ' . $foo->error;
                        }
                    }
                }
            }
            echo "Banner modificado correctamente";
        }
        break;
    case 'leer_fotos':
        $db        = DataBase::conectar();
        $id_banner = $db->clearText($_POST['id_banner']);
        $db->setQuery("SELECT imagen FROM banner WHERE id_banner=$id_banner");
        $rows = $db->loadObjectList();
        if ($rows) {
            foreach ($rows as $r) {
                $size       = filesize("../../" . $r->imagen);
                $nombre_tmp = explode("/", $r->imagen);
                $nombre     = end($nombre_tmp);
                $path       = $r->imagen;
                $salida[]   = ['name' => $nombre, 'size' => $size, 'path' => "../" . $path];
            }
        }
        echo json_encode($salida);
        break;

    case 'cambiar-estado':
        $db = DataBase::conectar();
        $id = $db->clearText($_POST['id']);

        $status = $db->clearText($_POST['estado']);

        $db->setQuery("UPDATE banner SET estado=$status WHERE id_banner=$id");
        if ($db->alter()) {
            echo "Estado actualizado correctamente";
            exit;
        } else {
            echo "Error al cambiar estado";
            exit;
        }
        break;

    case 'eliminar':
        $success = false;
        $id      = $_POST['id'];
        $nombre  = $_POST['nombre'];
        $db      = DataBase::conectar();
        $db->setQuery("SELECT imagen FROM banner WHERE id_banner=$id");
        $rows = $db->loadObject();
        $foto = $rows->imagen;
        $db->setQuery("DELETE FROM banner WHERE id_banner = $id");
        if ($db->alter()) {
            unlink("../../" . $foto);
            echo "Banner eliminado correctamente";
        } else {
            echo "Error al eliminar. " . $db->getError();
        }
        break;
    case 'borrar_fotos':
        $success        = false;
        $foto           = $_POST['foto'];
        $db             = DataBase::conectar();
        $id_banner_tmp2 = explode("_", $foto);
        $id_banner_tmp  = explode(".", $id_banner_tmp2[1]);
        $id_banner      = $id_banner_tmp[0];
        $db->setQuery("SELECT imagen FROM banner WHERE id_banner=$id_banner");
        $rows = $db->loadObject();
        $foto = $rows->imagen;
        $db->setQuery("UPDATE banner SET imagen=NULL WHERE id_banner = '$id_banner'");
        if ($db->alter()) {
            echo "Imagen eliminada correctamente";
            unlink("../../" . $foto);
        } else {
            echo "Error al eliminar '$foto'. " . $db->getError();
        }
        break;
}
