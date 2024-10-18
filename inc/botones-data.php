<?php
$db = DataBase::conectar();

$query = "SELECT id_boton, titulo, url, estado 
FROM botones
WHERE 1=1 AND estado=1
ORDER BY id_boton";
$db->setQuery($query);
$rows = $db->loadObjectList();

$html_botones = "";

foreach ($rows as $r) {
    $id_boton   = $r->id_boton;
    $titulo     = $r->titulo;
    $url        = $r->url;
    $html_botones .= '
        <div class="col-md-4">
            <div class="espaco">
                <a href="'.$url.'"><button class="btn color-boton"><span class="boton-tamaño">'.$titulo.'</span></button></a>
            </div>
        </div>
    ';
}


if ($rows) {
    echo '
        <section class="welcome-section bg-color padding-arriba centro">
            <div class="container">
                <div class="row">
                ' . $html_botones . '
                </div>
            </div>
        </section>
            ';
    
} else {
    echo '';
}



