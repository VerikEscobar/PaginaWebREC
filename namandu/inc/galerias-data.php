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
        $order = $_REQUEST['order'];
        $sort  = $_REQUEST['sort'];
        if (!isset($sort)) {
            $sort = 2;
        }

        if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $where  = "AND CONCAT_WS(' ', g.titulo, g.copete, g.fecha_galeria, g.descripcion, g.video) LIKE '%$search%'";
        }
        $query = "SELECT SQL_CALC_FOUND_ROWS g.id_galeria, g.titulo, g.copete,g.fecha_galeria, g.descripcion,gf.foto,g.video, CASE g.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado, DATE_FORMAT(g.fecha_galeria,'%d/%m/%Y') AS fecha, g.destacado
            FROM galerias g
            LEFT JOIN galerias_fotos gf ON gf.id_galeria=g.id_galeria
            WHERE 1=1 $where GROUP BY g.id_galeria ORDER BY $sort $order";
        $db->setQuery("$query");
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

        $db->setQuery("UPDATE galerias SET estado=$status WHERE id_galeria=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;

    case 'cargar_editar':

        $db = DataBase::conectar();
        $db->autocommit(false);

        $titulo      = $db->clearText($_POST['titulo']);
        $fecha       = $db->clearText($_POST['fecha']);
        $copete      = $db->clearText($_POST['copete']);
        $video       = $db->clearText($_POST['video']);
        $descripcion = $db->clearText($_POST['editor']);
        $destacado   = $db->clearText($_POST['destacado'])?: 0;
        $dropurl     = $db->clearText($_POST['dropurl']);
        $files       = $_FILES['file'];
        $files_name  = $_FILES['file']['name'][0];
        
        if($video){
            $video2 = '<iframe width="660" height="415" src="https://www.youtube.com/embed/'.$video.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';       
        }

        if (empty($titulo)) {
            echo "Error. Ingrese el Título de la Galeria";
            exit;
        }

        if (empty($fecha)) {
            echo "Error. Ingrese la fecha de la Galeria";
            exit;
        }

        if (empty($copete)) {
            echo "Error. Ingrese una breve descripción";
            exit;
        }

        if (empty($descripcion)) {
            echo "Error. Ingrese una descripción";
            exit;
        }

        if (empty($dropurl)) {
            echo "Error. El tipo de formulario esta vacio";
            exit;
        }

        if ($dropurl == "cargar") {

            $db->setQuery("SELECT titulo FROM galerias WHERE titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. La Galeria ya existe";
                exit;
            }

            $db->setQuery("INSERT INTO galerias (titulo, fecha_galeria, copete, descripcion,destacado, creacion,video) VALUES ('$titulo','$fecha','$copete','$descripcion','$destacado', NOW(), '$video2')");

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
                            $targetPath = "../../archivos/multimedia/galerias/";
                            if (!is_dir($targetPath)) {
                                mkdir($targetPath, 0777, true);
                            }
                            $foo->file_new_name_body = md5($ultimo_id);
                            $foo->image_convert      = jpg;
                            $foo->image_resize       = true;
                            $foo->image_ratio_fill   = true;
                            $foo->image_y            = 740;
                            $foo->image_x            = 1200;
                            $foo->process($targetPath);
                            $foto = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                            if ($foo->processed) {
                                $db->setQuery("INSERT INTO galerias_fotos (id_galeria, foto) VALUES ('$ultimo_id','$foto')");
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
                echo "Galeria registrada con éxito";
            }

        }

        if ($dropurl == "editar") {

            $hidden_id = $db->clearText($_POST['hidden_id']);

            $db->setQuery("SELECT id_galeria FROM galerias WHERE id_galeria NOT IN ($hidden_id) AND titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. La Galeria ya existe";
                exit;
            }
            if($video){
                $video2 = '<iframe width="660" height="415" src="https://www.youtube.com/embed/'.$video.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';       
            }

            $db->setQuery("UPDATE galerias SET titulo='$titulo', fecha_galeria='$fecha', copete='$copete', descripcion='$descripcion', destacado='$destacado', video='$video2' WHERE id_galeria='$hidden_id'");

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

                    foreach ($files as $file) {
                        $foo = new \Verot\Upload\Upload($file);
                        if ($foo->uploaded) {
                            $targetPath = "../../archivos/multimedia/galerias/";
                            if (!is_dir($targetPath)) {
                                mkdir($targetPath, 0777, true);
                            }
                            $foo->file_new_name_body = md5($hidden_id);
                            $foo->image_convert      = jpg;
                            $foo->image_resize       = true;
                            $foo->image_ratio_fill   = true;
                            $foo->image_y            = 740;
                            $foo->image_x            = 1200;
                            $foo->process($targetPath);
                            $foto = str_replace("../../", "", $targetPath . $foo->file_dst_name);
                            if ($foo->processed) {
                                $db->setQuery("INSERT INTO galerias_fotos (id_galeria, foto) VALUES ('$hidden_id','$foto')");
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
                echo "Galeria editada con éxito";
            }

        }

        break;

    case 'leer_fotos':
        $db = DataBase::conectar();
        $id = $db->clearText($_POST['id']);
        $db->setQuery("SELECT foto FROM galerias_fotos WHERE id_galeria=$id");
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
        $success = false;
        $foto    = $_POST['foto'];
        $db      = DataBase::conectar();
        $id_tmp2 = explode("_", $foto);
        $id_tmp  = explode(".", $id_tmp2[0]);
        $id_md5  = $id_tmp[0];

        $query = "SELECT foto, id_galeria_foto FROM galerias_fotos WHERE MD5(id_galeria)='$id_md5' AND foto LIKE '%$foto%'";
        $db->setQuery($query);
        $rows = $db->loadObject();
        $foto = $rows->foto;
        $id   = $rows->id_galeria_foto;
        $db->setQuery("DELETE FROM galerias_fotos WHERE id_galeria_foto = '$id'");
        if ($db->alter()) {
            echo "Imagen '$foto' eliminada correctamente";
            unlink("../../" . $foto);
        } else {
            echo "Error al eliminar '$foto'. " . $db->getError();
        }
        break;

    case 'eliminar':
        $db = DataBase::conectar();
        $db->autocommit(false);
        $id     = $db->clearText($_POST['id']);
        $nombre = $db->clearText($_POST['nombre']);
        $db->setQuery("DELETE FROM galerias WHERE id_galeria = $id");
        if (!$db->alter()) {
            echo "Error al eliminar '$nombre'. " . $db->getError();
            $db->rollback();
            exit;
        }
        $db->setQuery("SELECT foto FROM galerias_fotos WHERE id_galeria = $id");
        $rows = $db->loadObjectList();
        $db->setQuery("DELETE FROM galerias_fotos WHERE id_galeria = $id");
        if (!$db->alter()) {
            echo "Error al eliminar la foto de '$nombre'. " . $db->getError();
            $db->rollback();
            exit;
        }
        foreach ($rows as $r) {
            unlink("../../" . $r->foto);
        }
        echo "Galeria '$nombre' eliminada correctamente";
        $db->commit();
        break;
}
