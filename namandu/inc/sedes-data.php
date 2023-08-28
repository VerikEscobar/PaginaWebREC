<?php
include "funciones.php";
$q           = $_REQUEST['q'];
$usuario     = $auth->getUsername();
$id_sucursal = datosUsuario($usuario)->id_sucursal;
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
            $search = $_REQUEST['search'];
            $where  = "AND CONCAT_WS(' ', sc.categoria, s.departamento, s.oficina, s.creacion, s.nro_oficina) LIKE '%$search%'";
        }

        $db->setQuery("SELECT SQL_CALC_FOUND_ROWS s.id_sede, s.id_sede_categoria,sc.categoria, s.departamento, s.id_sede_responsable, sr.nombre, s.nro_oficina, s.oficina, s.direccion, s.telefono, s.coordenadas, DATE_FORMAT(s.creacion,'%d/%m/%Y %H:%i:%s') AS fecha, CASE s.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado, s.interino,
            CASE s.interino WHEN '1' THEN 'ACTIVO' WHEN '0' THEN 'INACTIVO' END AS interino_str, s.obs_interino
            FROM sedes s
            LEFT JOIN sedes_categorias sc ON s.id_sede_categoria=sc.id_sede_categoria
            LEFT JOIN sedes_responsables sr ON s.id_sede_responsable=sr.id_sede_responsable
            WHERE 1=1 $where ORDER BY $sort $order LIMIT $offset, $limit");
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

        $db->setQuery("UPDATE sedes SET estado=$status WHERE id_sede=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;

        case 'ver_categorias':
        $db = DataBase::conectar();
        $db->setQuery("SELECT categoria, id_sede_categoria FROM sedes_categorias ORDER BY id_sede_categoria ASC");
        $rows = $db->loadObjectList();
        echo json_encode($rows);
        break;

        case 'ver_responsables':
        $db          = DataBase::conectar();
        $page        = $db->clearText($_GET['page']);
        $term        = $db->clearText($_GET['term']);
        $resultCount = 5;
        $end         = ($page - 1) * $resultCount;

        $db->setQuery("SELECT id_sede_responsable, UPPER(nombre) AS nombre FROM sedes_responsables WHERE
            (nombre LIKE '%$term%') GROUP BY id_sede_responsable ORDER BY nombre ASC LIMIT $end, $resultCount");

        $rows = $db->loadObjectList();
        $db->setQuery("SELECT FOUND_ROWS() as total");
        $total_row = $db->loadObject();
        $count     = $total_row->total;
        if ($rows) {
            foreach ($rows as $r)   {
                $salida[] = ['id_sede_responsable' => $r->id_sede_responsable, 'nombre' => $r->nombre,'total_count' => $count];
            }
        } else {
            $salida[] = ['id_sede_responsable' => '', 'nombre' => '', 'total_count' => ''];
        }
        echo json_encode($salida);
        break;

        case 'ver_departamentos':
        $db          = DataBase::conectar();
        $page        = $db->clearText($_GET['page']);
        $term        = $db->clearText($_GET['term']);
        $resultCount = 5;
        $end         = ($page - 1) * $resultCount;

        $db->setQuery("SELECT UPPER(departamento) AS departamento FROM departamentos WHERE
            (departamento LIKE '%$term%') GROUP BY departamento ORDER BY departamento ASC LIMIT $end, $resultCount");

        $rows = $db->loadObjectList();
        $db->setQuery("SELECT FOUND_ROWS() as total");
        $total_row = $db->loadObject();
        $count     = $total_row->total;
        if ($rows) {
            foreach ($rows as $r) {
                $salida[] = ['departamento' => $r->departamento, 'total_count' => $count];
            }
        } else {
            $salida[] = ['departamento' => '', 'total_count' => ''];
        }
        echo json_encode($salida);
        break;

        case 'cargar':

        $db           = DataBase::conectar();

        $categoria    = $db->clearText($_POST['categoria']);
        $departamento = $db->clearText($_POST['departamento']);
        $nro_oficina  = $db->clearText($_POST['nro_oficina']);
        $oficina      = mb_convert_case($db->clearText($_POST['oficina']), MB_CASE_UPPER, "UTF-8");
        $responsable   = $db->clearText($_POST['responsable']);
        $direccion    = $db->clearText($_POST['direccion']);
        $telefono     = $db->clearText($_POST['telefono']);
        $interino     = $db->clearText($_POST['interino']) ?: 0;
        $obs_interino    = $db->clearText($_POST['obs_interino']);
        $longitud     = $db->clearText($_POST['lon_proceso']);
        $latitud      = $db->clearText($_POST['lat_proceso']);

        if (empty($categoria)) {
            echo "Error. Ingrese una categoria";
            exit;
        }
        if (empty($departamento)) {
            echo "Error. Ingrese una Departamento";
            exit;
        }

        if (empty($oficina)) {
            echo "Error. Ingrese una oficina";
            exit;
        }

        if (empty($longitud) && empty($latitud)) {
            echo "Error. Ingrese una ubicación";
            exit;
        } else {
            $coordenadas = $latitud . ", " . $longitud;
        }

        if(empty($nro_oficina)){
            $nro_oficina = 'NULL';
        }

        /*$db->setQuery("SELECT COUNT(id_sede_responsable) AS CAN FROM sedes WHERE id_sede_responsable='$responsable' GROUP BY id_sede_responsable");
        $rows = $db->loadObject();
        if ($rows->CAN >= 2 ) {
            echo "Error. Responsable solo puede ser asignado hasta 2 sedes";
            exit;
        } ELSE {*/
        $db->setQuery("INSERT INTO sedes (id_sede_categoria, departamento, id_sede_responsable, nro_oficina, oficina, direccion, telefono, coordenadas, interino, obs_interino, creacion) VALUES ('$categoria','$departamento', '$responsable', '$nro_oficina','$oficina','$direccion','$telefono','$coordenadas', '$interino', '$obs_interino', NOW())");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Sede registrada correctamente";
        }
        break;

    case 'editar':

        $db           = DataBase::conectar();
        $id           = $db->clearText($_POST['hidden_id']);
        $categoria    = $db->clearText($_POST['categoria']);
        $departamento = $db->clearText($_POST['departamento']);
        $nro_oficina  = $db->clearText($_POST['nro_oficina']);
        $oficina      = mb_convert_case($db->clearText($_POST['oficina']), MB_CASE_UPPER, "UTF-8");
        $responsable   = $db->clearText($_POST['responsable']);
        $interino = ($db->clearText($_POST['interino']))?: 0;
        $obs_interino    = $db->clearText($_POST['obs_interino']);
        $direccion    = $db->clearText($_POST['direccion']);
        $telefono     = $db->clearText($_POST['telefono']);
        $longitud     = $db->clearText($_POST['lon_proceso']);
        $latitud      = $db->clearText($_POST['lat_proceso']);

        if (empty($id)) {
            echo "Error. Ingrese el id";
            exit;
        }

        if (empty($categoria)) {
            echo "Error. Ingrese una categoria";
            exit;
        }

        if (empty($departamento)) {
            echo "Error. Ingrese una Departamento";
            exit;
        }

        if (empty($oficina)) {
            echo "Error. Ingrese una oficina";
            exit;
        }

        if (empty($longitud) && empty($latitud)) {
            echo "Error. Ingrese una ubicación";
            exit;
        } else {
            $coordenadas = $latitud . ", " . $longitud;
        }

        if(empty($nro_oficina)){
            $nro_oficina = 'NULL';
        }
        /*if (!empty($responsable)) {
            $db->setQuery("SELECT COUNT(id_sede_responsable) AS CAN FROM sedes WHERE id_sede_responsable='$responsable' and id_sede !='$id' GROUP BY id_sede_responsable");
            $rows = $db->loadObject();
            if ($rows->CAN >= 2 ) {
                echo "Error. Responsable solo puede ser asignado hasta 2 sedes";
                exit;
            }
        }*/
            $db->setQuery("UPDATE sedes SET id_sede_categoria='$categoria', id_sede_responsable='$responsable',departamento='$departamento',nro_oficina=$nro_oficina,oficina='$oficina',direccion='$direccion',telefono='$telefono',coordenadas='$coordenadas', interino='$interino', obs_interino='$obs_interino' WHERE id_sede=$id");
            if (!$db->alter()) {
                echo "Error. " . $db->getError();
                } else {
                echo "Sede modificada correctamente";
                }  
        break;

    case 'eliminar':

        $db      = DataBase::conectar();
        $success = false;
        $id      = $db->clearText($_POST['id']);
        $nombre  = $db->clearText($_POST['nombre']);

        if (empty($id)) {
            echo "Error. Ingrese un ID";
            exit;
        }

        $db->setQuery("DELETE FROM sedes WHERE id_sede=$id");

        if ($db->alter()) {
            echo "Sede eliminada correctamente";
        } else {
            echo "Error al eliminar '$nombre'. " . $db->getError();
        }

        break;
}
