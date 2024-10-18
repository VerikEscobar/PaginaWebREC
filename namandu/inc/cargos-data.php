<?php
include "funciones.php";
$q           = $_REQUEST['q'];
$usuario     = $auth->getUsername();
$id_sucursal = datosUsuario($usuario)->id_sucursal;
switch ($q) {

    case 'ver':

        $db    = DataBase::conectar();
        $where = "";
        $order = $_REQUEST['order'];
        $sort  = $_REQUEST['sort'];
        if (!isset($sort)) {
            $sort = 2;
        }

        if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $where  = "AND CONCAT_WS(' ', categoria) LIKE '%$search%'";
        }

        $db->setQuery("SELECT SQL_CALC_FOUND_ROWS id_autoridad_cargo, cargo, orden, DATE_FORMAT(creacion,'%d/%m/%Y %H:%i:%s') AS fecha, CASE estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado
            FROM autoridades_cargos
            WHERE 1=1 $where ORDER BY $sort $order");
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

        $db->setQuery("UPDATE autoridades_cargos SET estado=$status WHERE id_autoridad_cargo=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;
        
    case 'cargar':

        $db    = DataBase::conectar();
        $cargo = $db->clearText($_POST['cargo']);
        $orden = $db->clearText($_POST['orden']);

        if (empty($cargo)) {
            echo "Error. Ingrese un cargo";
            exit;
        }
        if (empty($orden)) {
            echo "Error. Ingrese un orden";
            exit;
        }

        $db->setQuery("SELECT cargo FROM autoridades_cargos WHERE cargo='$cargo'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. El cargo no se debe repetir";
            exit;
        }

        $db->setQuery("INSERT INTO autoridades_cargos (cargo, orden,creacion) VALUES ('$cargo','$orden',NOW())");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Cargo registrado correctamente";
        }

        break;

    case 'editar':

        $db    = DataBase::conectar();
        $id    = $db->clearText($_POST['hidden_id']);
        $cargo = $db->clearText($_POST['cargo']);
        $orden = $db->clearText($_POST['orden']);

        if (empty($id)) {
            echo "Error. Ingrese el id";
            exit;
        }

        if (empty($cargo)) {
            echo "Error. Ingrese un cargo";
            exit;
        }

        if (empty($orden)) {
            echo "Error. Ingrese un Orden";
            exit;
        }

        $db->setQuery("SELECT cargo FROM autoridades_cargos WHERE id_autoridad_cargo NOT IN ($id) AND cargo='$cargo'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. El cargo no se debe repetir";
            exit;
        }

        $db->setQuery("UPDATE autoridades_cargos SET cargo='$cargo',orden='$orden' WHERE id_autoridad_cargo=$id");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Cargo modificado correctamente";
        }

        break;

    case 'eliminar':

        $db      = DataBase::conectar();
        $success = false;
        $id      = $db->clearText($_POST['id']);
        $nombre  = $db->clearText($_POST['nombre']);

        $db->setQuery("SELECT id_autoridad_cargo FROM autoridades WHERE id_autoridad_cargo=$id");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. Este cargo esta asociado a una autoridad";
            exit;
        }

        $db->setQuery("DELETE FROM autoridades_cargos WHERE id_autoridad_cargo=$id");

        if ($db->alter()) {
            echo "Cargo eliminado correctamente";
        } else {
            echo "Error al eliminar '$nombre'. " . $db->getError();
        }

        break;
}
