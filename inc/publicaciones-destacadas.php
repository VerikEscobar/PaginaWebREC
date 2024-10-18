<?php
$db = DataBase::conectar();
$db->setQuery("SET lc_time_names = 'es_ES';");
$db->alter();
$db->setQuery("SELECT
  n.id_noticia,
  n.titulo,
  n.copete,
  n.descripcion,
  nf.foto
FROM
  noticias n
  INNER JOIN noticias_fotos nf
    ON nf.id_noticia = n.id_noticia
WHERE n.estado = 1
  AND n.destacado = 1
GROUP BY n.id_noticia
ORDER BY n.fecha_noticia DESC LIMIT 3");
$rows = $db->loadObjectList();
if ($rows) {
    foreach ($rows as $r) {
        $id          = $r->id_noticia;
        $titulo      = cortar_titulo($r->titulo, 60);
        $descripcion = cortar_titulo($r->descripcion, 120);
        $mes         = ucfirst(utf8_encode(strftime($r->mes)));
        $url         = url()."publicacion/". url_amigable($r->titulo)."-".$id;

        $foto = verificaFoto($r->foto);

        echo '
        <div class="col-md-4 col-sm-6 col-xs-12">
            <a href="'.$url.'">
                <div class="single-services">
                    <div class="item">
                        <figure class="img-box">
                            <a href="'.$url.'"><img src="' . $foto . '" alt="'.$titulo.'"> </a>
                        </figure>
                        <div class="text-content">
                            <h4><a href="'.$url.'">'.$titulo.'</a></h4>
                            <p>'.$descripcion.'</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        ';
    }
    echo '<div class="col-md-12 text-center">
            <a class="load-more-btn" href="'.url().'publicaciones">Más Noticias</a>
        </div>';
}else{
    echo "<p class='text-center'>No se encontraron publicaciones destacadas.</p>";
}
