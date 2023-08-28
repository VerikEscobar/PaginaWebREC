<?php
function traerRelacionadas($buscar = "", $id = "")
{

    $db = DataBase::conectar();

    $contar = count(explode(' ', $buscar));

    if ($contar == 1) {
        $q = "SELECT
              n.id_noticia,
              n.titulo,
              nf.foto,
              n.fecha_noticia
            FROM
              noticias n
              INNER JOIN noticias_fotos nf
                ON nf.id_noticia = n.id_noticia
            WHERE n.titulo LIKE '%$buscar%' AND n.id_noticia!=$id AND n.estado=1
            GROUP BY n.id_noticia
            ORDER BY n.fecha_noticia ASC LIMIT 6";
    } else {
        $q = "
          SELECT  id_noticia,titulo,descripcion, MATCH ( titulo,descripcion)
          AGAINST ('$buscar') AS Score
          FROM noticias n
          WHERE
          MATCH ( titulo, descripcion ) AGAINST (  '$buscar' ) AND n.id_noticia!=$id AND n.estado=1 ORDER  BY Score DESC LIMIT 6
        ";
    }

    $db->setQuery($q);

    $rows = $db->loadObjectList();
    if ($rows) {
        foreach ($rows as $r) {
            $id     = $r->id_noticia;
            $titulo = cortar_titulo($r->titulo, 60);
            $fecha  = fechaLatina($r->fecha_noticia);
            $mes    = ucfirst(utf8_encode(strftime($r->mes)));
            $url    = url() . "publicacion/" . url_amigable($r->titulo) . "-" . $id;

            $db->setQuery("SELECT foto FROM noticias_fotos WHERE id_noticia='$id'");
            $foto  = verificaFoto($db->loadObject()->foto);
            echo '
        <li>
            <div class="icon-box"><img src="' . $foto . '" alt="' . $titulo . '"></div>
            <div class="text-box">
                <h5><a href="' . $url . '">' . $titulo . '</a></h5><span>' . $fecha . '</span>
            </div>
        </li>
        ';
        }
    } else {
        echo "<p class='text-center'>No se encontraron publicaciones relacionadas.</p>";
    }

}