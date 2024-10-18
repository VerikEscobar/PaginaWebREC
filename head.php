<?php
include 'inc/funciones.php';

if ($pagina) {

    $db    = DataBase::conectar();
    $url   = $db->clearText($pagina);
    $query = "SELECT titulo, meta_descripcion, html, foto FROM paginas WHERE url='$url' AND estado=1";
    $db->setQuery($query);
    $row = $db->loadObject();
    if (!empty($row)) {
        $titulo      = $row->titulo;
        $descripcion = $row->meta_descripcion;
        $html        = $row->html;
        if ($row->foto) {$foto = url() . $row->foto;} else { $foto = url() . "img/background/c.jpg";}

        $title       = $titulo . " | Registro del Estado Civil";
        $description = $descripcion;

    } else {
        echo "<script>location.href ='" . url() . "error/404';</script>";
    }
}

if ($publicacion) {
    $db    = DataBase::conectar();
    $id    = $db->clearText(end(explode('-', $publicacion)));
    $query = "SELECT id_noticia, titulo, fecha_noticia, copete, descripcion FROM noticias WHERE id_noticia='$id' AND estado=1";
    $db->setQuery($query);
    $row = $db->loadObject();
    if (!empty($row)) {

        $titulo      = $row->titulo;
        $fecha_noticia = $row->fecha_noticia;
        $id_noticia  = $row->id_noticia;
        $copete      = $row->copete;
        $descripcion = $row->descripcion;

        $title       = $titulo;
        $description = $copete;

        $query = "SELECT foto FROM noticias_fotos WHERE id_noticia='$id'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if (!empty($rows)) {
            foreach ($rows as $r) {
                $foto = verificaFoto($r->foto);
                $fotoConten .= '<div class="item"><img src="' . $foto . '" alt="' . $titulo . '"></div>';
            }
        } else {
            $fotoConten = "";
        }

    } else {
        echo "<script>location.href ='" . url() . "error/404';</script>";
    }
}

if ($galeria) {
    $db    = DataBase::conectar();
    $id    = $db->clearText(end(explode('-', $galeria)));
    $query = "SELECT id_galeria, titulo, copete, descripcion, video FROM galerias WHERE id_galeria='$id' AND estado=1";
    $db->setQuery($query);
    $row = $db->loadObject();
    if (!empty($row)) {

        $titulo      = $row->titulo;
        $id_galeria  = $row->id_galeria;
        $copete      = $row->copete;
        $descripcion = $row->descripcion;
        $video       = $row->video;
        $title       = $titulo;
        $description = $copete;

        $query = "SELECT foto FROM galerias_fotos WHERE id_galeria='$id'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if (!empty($rows)) {
            foreach ($rows as $r) {
                $foto = url() . $r->foto;
                $fotoConten .= '<div class="col-md-3 mt-1"><a href="' . $foto . '" class="tt-gallery-1 lightbox"><img class="imggaleria img-thumbnail" src="' . $foto . '" alt="' . $titulo . '"></a></div>';
            }
        } else {
            $fotoConten = "";
        }

    } else {
        echo "<script>location.href ='" . url() . "error/404';</script>";
    }
}



if ($categoriaSede) {
    if ($categoriaSede == "coordinacion-departamentales") {
        $categoriaSede = 1;
    }else{
        if($categoriaSede == "oficinas-registrales"){
            $categoriaSede = 2;
        }else{
            $categoriaSede = 4;
        }
    }
}

?>
<!doctype html>
<html class="no-js" lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo url(); ?>img/logo/favicon.ico">
    <link rel="stylesheet" href="<?php echo url(); ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/bootstrap-table.min.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/owl.transitions.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/icon.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/flaticon.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/magnific.min.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/venobox.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/style.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/responsive.css">
    <link rel="stylesheet" href="<?php echo url(); ?>css/custom.css">
    <script src="<?php echo url(); ?>js/pedron/modernizr-2.8.3.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link rel="stylesheet" href="../mapaModal/Leaflet Mapa/css/leaflet.css">
</head>