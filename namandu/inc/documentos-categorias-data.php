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

        $db->setQuery("SELECT SQL_CALC_FOUND_ROWS id_documento_categoria, categoria, DATE_FORMAT(creacion,'%d/%m/%Y %H:%i:%s') AS fecha, CASE estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado
            FROM documentos_categorias
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

        $db->setQuery("UPDATE documentos_categorias SET estado=$status WHERE id_documento_categoria=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;
        
    case 'cargar':

        $db    = DataBase::conectar();
        $categoria = $db->clearText($_POST['categoria']);

        if (empty($categoria)) {
            echo "Error. Ingrese una categoria";
            exit;
        }

        $db->setQuery("SELECT categoria FROM documentos_categorias WHERE categoria='$categoria'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. La categoria no se debe repetir";
            exit;
        }

        $db->setQuery("INSERT INTO documentos_categorias (categoria,creacion) VALUES ('$categoria',NOW())");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Categoria registrada correctamente";
        }

        break;

    case 'editar':

        $db         = DataBase::conectar();
        $id         = $db->clearText($_POST['hidden_id']);
        $categoria  = $db->clearText($_POST['categoria']);

        if (empty($id)) {
            echo "Error. Ingrese el id";
            exit;
        }

        if (empty($categoria)) {
            echo "Error. Ingrese un cargo";
            exit;
        }

        $db->setQuery("SELECT categoria FROM documentos_categorias WHERE id_documento_categoria NOT IN ($id) AND categoria='$cargo'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. La Categoria no se debe repetir";
            exit;
        }

        $db->setQuery("UPDATE documentos_categorias SET categoria='$categoria' WHERE id_documento_categoria=$id");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        } else {
            echo "Categoria modificada correctamente";
        }

        break;

    case 'eliminar':

        $db      = DataBase::conectar();
        $success = false;
        $id      = $db->clearText($_POST['id']);
        $nombre  = $db->clearText($_POST['nombre']);

        $db->setQuery("SELECT id_documento_categoria FROM documentos WHERE id_documento_categoria=$id");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. Esta categoria esta asociada a un documento";
            exit;
        }

        $db->setQuery("DELETE FROM documentos_categorias WHERE id_documento_categoria=$id");

        if ($db->alter()) {
            echo "Categoria eliminada correctamente";
        } else {
            echo "Error al eliminar '$nombre'. " . $db->getError();
        }

        break;
}
