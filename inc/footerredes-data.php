<?php
$db = DataBase::conectar();

$query = "SELECT rs.id_red_social, rs.titulo, rs.url, rs.icono, rs.estado 
FROM redes_sociales rs
WHERE 1=1 AND rs.estado=1
ORDER BY rs.id_red_social";
$db->setQuery($query);
$rows = $db->loadObjectList();

if ($rows) {
    foreach ($rows as $r) {
        $id_red_social    = $r->id_red_social;
        $titulo           = $r->titulo;
        $url              = $r->url;
        $icono            = $r->icono;
        echo '
                <li style="margin-top: 10px !important;"><a href="'.$url.'" target="_blank">'.$icono.'</a></li>
            ';
    }
} else {
    echo '<div class="alert alert-danger"role="alert">
                No se encontro registros de Footer.
             </div>';
}

