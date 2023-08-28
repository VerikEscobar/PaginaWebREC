<?php
include 'funciones.php';
if (!is_csrf_valid()) {
    $salida = ["status" => "error", "mensaje" => 'Faltan algunos parametros de seguridad'];
    echo json_encode($salida);
    exit;
}
$db     = DataBase::conectar();
$buscar = $db->clearText($_POST['buscar']);
$where  = "";

if (!empty($buscar)) {
    $where  = "CONCAT_WS(' ', a.descripcion, a.nombre, ac.cargo) LIKE '%$buscar%' AND";
}

$query = "SELECT a.nombre, a.foto, a.descripcion, ac.cargo
FROM autoridades a
LEFT JOIN autoridades_cargos ac ON ac.id_autoridad_cargo=a.id_autoridad_cargo
WHERE $where a.estado = 1
GROUP BY a.id_autoridad ORDER BY ABS(ac.orden) ASC
";
$db->setQuery($query);
$rows = $db->loadObjectList();
if (!empty($rows)) {
    foreach ($rows as $r) {
        $nombre      = $r->nombre;
        $cargo       = $r->cargo;
        $descripcion = $r->descripcion;
        $foto        = $r->foto;
        if ($foto) {
            $foto = url() . $foto;
        } else {
            $foto = url() . "img/sin-foto.jpg";
        }
        $html[] = '<div class="col-md-3 col-sm-4 col-xs-12 mb-2">
                        <div class="single-team-member">
                            <div class="img-box"><img class="imgAutoridad" src="'.$foto.'" alt="'.$nombre.'"></div>
                            <div class="text-box">
                                <h4 class="mb-1">'.$nombre.'</h4>
                                <div class="altoDesc">
                                    <p>'.$cargo.'</p>
                                </div>
                               
                            </div>
                        </div>
                    </div>';
    }
    $salida = ["status" => "ok", "mensaje" => 'Consulta exitosa', "html" => $html];
    echo json_encode($salida);
    exit;
} else {
    $salida = ["status" => "error", "mensaje" => 'No se encontraron Autoridades.'];
    echo json_encode($salida);
    exit;
}
