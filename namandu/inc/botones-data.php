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
            $where  = "AND CONCAT_WS(' ', titulo) LIKE '%$search%'";
        }

        $db->setQuery("SELECT id_boton, titulo, url, DATE_FORMAT(creacion,'%d/%m/%Y %H:%i:%s') AS fecha, CASE estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado
            FROM botones
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

        $db->setQuery("UPDATE botones SET estado=$status WHERE id_boton=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;
        
    case 'cargar':

        $db     = DataBase::conectar();
        $titulo = $db->clearText($_POST['titulo']);
        $url    = $db->clearText($_POST['url']);

        if (empty($titulo)) {
            echo "Error. Ingrese un Titulo";
            exit;
        }
        if (empty($url)) {
            echo "Error. Ingrese una URL";
            exit;
        }

        $db->setQuery("SELECT titulo FROM botones WHERE titulo ='$titulo'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. El titulo no se debe repetir";
            exit;
        }

        $db->setQuery("INSERT INTO botones (titulo, url, creacion, estado) VALUES ('$titulo', '$url',NOW(),1)");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Boton registrado correctamente";
        }

        break;

    case 'editar':

        $db         = DataBase::conectar();
        $id         = $db->clearText($_POST['hidden_id']);
        $titulo     = $db->clearText($_POST['titulo']);
        $url        = $db->clearText($_POST['url']);

        if (empty($titulo)) {
            echo "Error. Ingrese un Titulo";
            exit;
        }
        if (empty($url)) {
            echo "Error. Ingrese una URL";
            exit;
        }

        $db->setQuery("SELECT titulo FROM botones WHERE id_boton NOT IN ($id) AND titulo='$titulo'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. El titulo no se debe repetir";
            exit;
        }

        $db->setQuery("UPDATE botones SET titulo='$titulo', url='$url' WHERE id_boton=$id");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Boton modificado correctamente";
        }

        break;

    case 'eliminar':

        $db         = DataBase::conectar();
        $id         = $db->clearText($_POST['id']);
        $titulo     = $db->clearText($_POST['titulo']);

        $db->setQuery("DELETE FROM botones WHERE id_boton=$id");

        if ($db->alter()) {
            echo "Boton eliminado correctamente";
        } else {
            echo "Error al eliminar '$titulo'. " . $db->getError();
        }

        break;
}
