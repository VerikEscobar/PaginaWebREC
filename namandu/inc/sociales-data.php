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
            $where  = "AND CONCAT_WS(' ', titulo, url) LIKE '%$search%'";
        }

        $db->setQuery("SELECT r.id_red_social, r.titulo, r.url, r.icono, DATE_FORMAT(r.creacion,'%d/%m/%Y %H:%i:%s') AS fecha, CASE r.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado
            FROM redes_sociales r
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

        $db->setQuery("UPDATE redes_sociales SET estado=$status WHERE id_red_social=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;
        
    case 'cargar':

        $db         = DataBase::conectar();
        $titulo     = $db->clearText($_POST['titulo']);
        $url        = $db->clearText($_POST['url']);
        $icono      = $db->clearText($_POST['icono']);

        if (empty($titulo)) {
            echo "Error. Ingrese un Titulo";
            exit;
        }

        if (empty($url)) {
            echo "Error. Ingrese una URL";
            exit;
        }

        if (empty($icono)) {
            echo "Error. Ingrese un ICONO";
            exit;
        }

        $db->setQuery("SELECT titulo FROM redes_sociales WHERE titulo='$titulo'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. El Titulo no se debe repetir";
            exit;
        }

        $db->setQuery("INSERT INTO redes_sociales (titulo, url, icono, creacion, estado) VALUES ('$titulo', '$url', '$icono',NOW(),1)");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Red Social registrada correctamente";
        }

        break;

    case 'editar':

        $db         = DataBase::conectar();
        $id         = $db->clearText($_POST['hidden_id']);
        $titulo     = $db->clearText($_POST['titulo']);
        $url        = $db->clearText($_POST['url']);
        $icono      = $db->clearText($_POST['icono']);

        if (empty($titulo)) {
            echo "Error. Ingrese un Titulo";
            exit;
        }

        $db->setQuery("SELECT titulo FROM redes_sociales WHERE id_red_social NOT IN ($id) AND titulo='$titulo'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. El Titulo no se debe repetir";
            exit;
        }

        $db->setQuery("UPDATE redes_sociales SET titulo='$titulo', url='$url', icono='$icono' WHERE id_red_social=$id");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Red Social modificada correctamente";
        }

        break;

    case 'eliminar':

        $db      = DataBase::conectar();
        $success = false;
        $id      = $db->clearText($_POST['id']);
        $titulo  = $db->clearText($_POST['titulo']);

        $db->setQuery("DELETE FROM redes_sociales WHERE id_red_social=$id");

        if ($db->alter()) {
            echo "Red Social eliminada correctamente";
        } else {
            echo "Error al eliminar '$titulo'. " . $db->getError();
        }

        break;
}
