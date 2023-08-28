<?php
include "funciones.php";
include "class.upload.php";
$q           = $_REQUEST['q'];
$usuario     = $auth->getUsername();
$sucursal    = datosUsuario($usuario);
$id_sucursal = $sucursal->id_sucursal;
$username    = $sucursal->username;

$limite_noticias = 3;
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
            $search = preg_replace('/\s+/', "%%", $db->clearText($_REQUEST['search']));
            $where  = "AND CONCAT_WS(' ', n.titulo, n.copete, n.fecha_noticia, n.descripcion) LIKE '%$search%'";
        }
        $query = "SELECT SQL_CALC_FOUND_ROWS n.id_noticia, n.titulo, n.copete,DATE(n.fecha_noticia) AS fecha_noticia, n.descripcion,nf.foto, CASE n.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado, DATE_FORMAT(n.creacion,'%d/%m/%Y %H:%i:%s') AS fecha, n.destacado,
            CASE n.destacado WHEN '1' THEN 'Sí' WHEN '0' THEN 'No' END AS nombre_destacado, n.fecha_modificacion, n.usuario, 
            n.usuario_modifica
            FROM noticias n
            LEFT JOIN noticias_fotos nf ON nf.id_noticia=n.id_noticia
            WHERE 1=1 $where GROUP BY n.id_noticia ORDER BY $sort $order LIMIT $offset, $limit";
        $db->setQuery("$query");
        $rows = $db->loadObjectList();

        $db->setQuery("SELECT FOUND_ROWS() as total");
        $total_row = $db->loadObject();
        $total     = $total_row->total;

        $noticias = [];
        foreach ($rows as $r){
            $foto = $r->foto;
            if (!empty($foto) && file_exists("../../".$foto)) {
                $foto = $r->foto;
            }else{
                $r->foto = "";  
            }
            $noticias[] = $r;
        }


        if ($noticias) {
            $salida = array('total' => $total, 'rows' => $noticias);
        } else {
            $salida = array('total' => 0, 'rows' => array());
        }

        echo json_encode($salida);

        break;

    // case 'validar_directorio_foto';
    //     $db = DataBase::conectar();
    //     $id = $db->clearText($_POST['id']);

    //     $db->setQuery("SELECT foto FROM noticias_fotos WHERE id_noticia='$id'");
    //     $path_foto  = $db->loadObject()->foto;
        
    //     if(!empty($path_foto) && file_exists("./".$path_foto)){
    //         $foto = url() . $path_foto;

    //     }else{
    //         $foto = url() . "img/sin-foto.jpg";
    //     }

    // break;

    case 'cambiar-estado':
        $db = DataBase::conectar();
        $id = $db->clearText($_POST['id']);

        $status = $db->clearText($_POST['estado']);

        $db->setQuery("UPDATE noticias SET estado=$status WHERE id_noticia=$id");

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
        $hora        = $db->clearText($_POST['hora']);
        $copete      = $db->clearText($_POST['copete']);
        $descripcion = $db->clearText($_POST['editor']);
        $destacado   = ($db->clearText($_POST['destacado'])) ?: 0;
        $dropurl     = $db->clearText($_POST['dropurl']);
        $files       = $_FILES['file'];
        $files_name  = $_FILES['file']['name'][0];

        if (empty($titulo)) {
            echo "Error. Ingrese el Título de la Noticia";
            exit;
        }

        if (empty($fecha)) {
            echo "Error. Ingrese la fecha de la Noticia";
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

            $db->setQuery("SELECT titulo FROM noticias WHERE titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. La Noticia ya existe";
                exit;
            }

            /*$db->setQuery("SELECT COUNT(id_noticia) AS contador FROM noticias WHERE destacado = 1");
            $contador_destacados = $db->loadObject()->contador;
            if($contador_destacados > $limite_noticias){
                echo "Error. El numero de noticias destacadas solo puede ser de tres";
                exit;
            }*/

            $db->setQuery("INSERT INTO noticias (titulo, fecha_noticia, copete, descripcion, usuario, destacado, creacion) VALUES ('$titulo','$fecha','$copete','$descripcion', '$username','$destacado', NOW())");

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
                            $targetPath = "../../archivos/multimedia/noticias/";
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
                                $db->setQuery("INSERT INTO noticias_fotos (id_noticia, foto) VALUES ('$ultimo_id','$foto')");
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
                echo "Noticia registrada con éxito";
            }

        }

        if ($dropurl == "editar") {

            $hidden_id = $db->clearText($_POST['hidden_id']);

            $db->setQuery("SELECT id_noticia FROM noticias WHERE id_noticia NOT IN ($hidden_id) AND titulo='$titulo'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. La Noticia ya existe";
                exit;
            }

            /*$db->setQuery("SELECT COUNT(id_noticia) AS contador FROM noticias WHERE destacado = 1 AND id_noticia <> $hidden_id");
            $contador_destacados = $db->loadObject()->contador;
            if($contador_destacados > $limite_noticias){
                echo "Error. El numero de noticias destacadas solo puede ser de tres";
                exit;
            }*/

            $db->setQuery("UPDATE noticias SET titulo='$titulo', fecha_noticia='$fecha', copete='$copete', descripcion='$descripcion', usuario_modifica='$username', destacado='$destacado', fecha_modificacion=NOW() WHERE id_noticia='$hidden_id'");

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
                            $targetPath = "../../archivos/multimedia/noticias/";
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
                                $db->setQuery("INSERT INTO noticias_fotos (id_noticia, foto) VALUES ('$hidden_id','$foto')");
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
                echo "Noticia editada con éxito";
            }

        }

        break;

    case 'leer_fotos':
        $db = DataBase::conectar();
        $id = $db->clearText($_POST['id']);
        $db->setQuery("SELECT foto FROM noticias_fotos WHERE id_noticia=$id");
        $rows = $db->loadObjectList();
        if ($rows)
        {
            foreach ($rows as $r)
            {
                $size = filesize("../../" . $r->foto);
                $nombre_tmp = explode("/", $r->foto);
                $nombre = end($nombre_tmp);
                $path = $r->foto;
                $salida[] = ['name' => $nombre, 'size' => $size, 'path' => "../" . $path];
            }
        }
        echo json_encode($salida);
        break;

case 'borrar_fotos':
        $success = false;
        $foto    = $_POST['foto'];
        $db      = DataBase::conectar();
        // $id_tmp2 = explode("_", $foto);
        // $id_tmp  = explode(".", $id_tmp2[0]);
        // $id_md5  = $id_tmp[0];
        //$query = "SELECT foto, id_noticia_foto FROM noticias_fotos WHERE MD5(id_noticia)='$id_md5' AND foto LIKE '%$foto%'";
        $query = "SELECT foto, id_noticia_foto FROM noticias_fotos WHERE foto LIKE '%$foto%'";
        $db->setQuery($query);
        $rows = $db->loadObject();
        $foto = $rows->foto;
        $id   = $rows->id_noticia_foto;
        $db->setQuery("DELETE FROM noticias_fotos WHERE id_noticia_foto = '$id'");
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
        $db->setQuery("DELETE FROM noticias WHERE id_noticia = $id");
        if (!$db->alter()) {
            echo "Error al eliminar '$nombre'. " . $db->getError();
            $db->rollback();
            exit;
        }
        $db->setQuery("SELECT foto FROM noticias_fotos WHERE id_noticia = $id");
        $rows = $db->loadObjectList();
        $db->setQuery("DELETE FROM noticias_fotos WHERE id_noticia = $id");
        if (!$db->alter()) {
            echo "Error al eliminar la foto de '$nombre'. " . $db->getError();
            $db->rollback();
            exit;
        }
        foreach ($rows as $r) {
            unlink("../../" . $r->foto);
        }
        echo "Noticia '$nombre' eliminada correctamente";
        $db->commit();
        break;
}
