<?php
function traerRelacionadas($buscar = "", $id = "")
{

$db = DataBase::conectar();
$db->setQuery("SELECT
  g.id_galeria,
  g.titulo,
  gf.foto,
  g.fecha_galeria
FROM
  galerias g
  INNER JOIN galerias_fotos gf
    ON gf.id_galeria = g.id_galeria
WHERE g.titulo LIKE '%$buscar%' AND g.id_galeria!=$id AND g.estado=1
GROUP BY g.id_galeria
ORDER BY g.fecha_galeria ASC LIMIT 6");
    $rows = $db->loadObjectList();
    if ($rows) {
        foreach ($rows as $r) {
            $id     = $r->id_galeria;
            $titulo = cortar_titulo($r->titulo, 60);
            $foto   = $r->foto;
            $fecha  = fechaLatina($r->fecha_galeria);
            $mes    = ucfirst(utf8_encode(strftime($r->mes)));
            $url    = url() . "galeria/" . url_amigable($r->titulo) . "-" . $id;
            if ($foto) {
                $foto = url() . $foto;
            } else {
                $foto = url() . "img/sin-foto.jpg";
            }
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
        echo "<p class='text-center'>No se encontraron galerias relacionadas.</p>";
    }

}
