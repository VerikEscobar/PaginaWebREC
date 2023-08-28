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
            $where  = "AND CONCAT_WS(' ', descripcion) LIKE '%$search%'";
        }

        $db->setQuery("SELECT f.id_footer, f.descripcion, DATE_FORMAT(f.creacion,'%d/%m/%Y %H:%i:%s') AS fecha, CASE f.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado
            FROM footer f
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

        $db->setQuery("UPDATE footer SET estado=$status WHERE id_footer=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;
        
    case 'cargar':

        $db    = DataBase::conectar();
        $descripcion = $db->clearText($_POST['descripcion']);

        if (empty($descripcion)) {
            echo "Error. Ingrese una descripción";
            exit;
        }

        $db->setQuery("SELECT descripcion FROM footer WHERE descripcion='$descripcion'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. La Descripcion no se debe repetir";
            exit;
        }

        $db->setQuery("INSERT INTO footer (descripcion, creacion, estado) VALUES ('$descripcion',NOW(),1)");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Descripcion Footer registrada correctamente";
        }

        break;

    case 'editar':

        $db         = DataBase::conectar();
        $id         = $db->clearText($_POST['hidden_id']);
        $descripcion = $db->clearText($_POST['descripcion']);

        if (empty($descripcion)) {
            echo "Error. Ingrese una descripción";
            exit;
        }

        $db->setQuery("SELECT descripcion FROM footer WHERE id_footer NOT IN ($id) AND descripcion='$descripcion'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. La Descripcion no se debe repetir";
            exit;
        }

        $db->setQuery("UPDATE footer SET descripcion='$descripcion' WHERE id_footer=$id");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Descripcion Footer modificada correctamente";
        }

        break;

    case 'eliminar':

        $db      = DataBase::conectar();
        $success = false;
        $id      = $db->clearText($_POST['id']);
        $descripcion  = $db->clearText($_POST['descripcion']);

        $db->setQuery("DELETE FROM footer WHERE id_footer=$id");

        if ($db->alter()) {
            echo "Descripcion Footer eliminada correctamente";
        } else {
            echo "Error al eliminar '$descripcion'. " . $db->getError();
        }

        break;
}
