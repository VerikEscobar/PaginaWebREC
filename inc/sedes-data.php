<?php
include "funciones.php";
$db = DataBase::conectar();

$where  = "";
$wherecategoria  = "";
$limit  = $db->clearText($_GET['limit']);
$offset = $db->clearText($_GET['offset']);
$order  = $db->clearText($_GET['order']);
$sort   = $db->clearText($_GET['sort']);

if (!isset($sort)) {
    $sort = 2;
}

if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $categoria = $db->clearText($_GET['categoria']);
    $wherecategoria  = "AND s.id_sede_categoria = $categoria";
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $db->clearText($_GET['search']);
    $where  = "AND CONCAT_WS(' ', s.departamento, s.nro_oficina, s.oficina) LIKE '%$search%'";
}

$query = "SELECT SQL_CALC_FOUND_ROWS s.id_sede, s.departamento, s.id_sede_responsable, nombre, cargo, s.nro_oficina, s.oficina, s.coordenadas, sr.telefono, sr.foto, s.direccion, s.telefono, s.obs_interino
FROM sedes s
LEFT JOIN sedes_responsables sr ON sr.id_sede_responsable = s.id_sede_responsable  AND sr.estado =1
WHERE 1=1 AND s.estado=1 $where $wherecategoria
ORDER BY $sort $order LIMIT $offset, $limit";
$db->setQuery($query );

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
