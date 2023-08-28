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
            $where  = "AND CONCAT_WS(' ', titulo, descripcion, orden, categoria) LIKE '%$search%'";
        }

        $db->setQuery("SELECT t.id_tramite, t.titulo, t.orden, t.descripcion, tc.id_tramite_categoria, tc.categoria, DATE_FORMAT(t.creacion,'%d/%m/%Y %H:%i:%s') AS fecha, CASE t.estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END AS nombre_estado
            FROM tramites t
            LEFT JOIN tramites_categorias tc ON t.id_tramite_categoria = tc.id_tramite_categoria
            WHERE 1=1 $where ORDER BY tc.id_tramite_categoria");
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

        $db->setQuery("UPDATE tramites SET estado=$status WHERE id_tramite=$id");

        if ($db->alter()) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al cambiar estado";
        }

        break;

    case 'ver_categorias':
        $db   = DataBase::conectar();

        $db->setQuery("SELECT id_tramite_categoria, categoria FROM tramites_categorias 
        ORDER BY id_tramite_categoria ASC");
        $rows = $db->loadObjectList();
        echo json_encode($rows);
        break;
        
    case 'cargar':

        $db              = DataBase::conectar();
        $titulo          = $db->clearText($_POST['titulo']);
        $orden           = $db->clearText($_POST['orden']);
        $descripcion     = $db->clearText($_POST['editor']);
        $categoria       = $db->clearText($_POST['categoria']);

        if (empty($titulo)) {
            echo "Error. Ingrese un Titulo";
            exit;
        }
        if (empty($orden)) {
            echo "Error. Ingrese un Orden";
            exit;
        }
        if (empty($descripcion)) {
            echo "Error. Ingrese una descripción";
            exit;
        }
        if (empty($categoria)) {
            echo "Error. Ingrese una Categoria";
            exit;
        }

        $db->setQuery("SELECT t.orden, t.id_tramite_categoria FROM tramites t
            WHERE orden='$orden' AND t.id_tramite_categoria ='$categoria'");
        $rows = $db->loadObject();

        if (!empty($rows)) {
            echo "Error. El orden para el tramite ya existe";
            exit;
        }

        $db->setQuery("INSERT INTO tramites (titulo, id_tramite_categoria, orden, descripcion, creacion, estado) VALUES ('$titulo', '$categoria', '$orden', '$descripcion',NOW(),1)");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        }
        else {
            echo "Tabla Tramites registrada correctamente";
        }

        break;

    case 'editar':

        $db              = DataBase::conectar();
        $id              = $db->clearText($_POST['hidden_id']);
        $titulo          = $db->clearText($_POST['titulo']);
        $orden           = $db->clearText($_POST['orden']);
        $descripcion     = $db->clearText($_POST['editor']);
        $categoria       = $db->clearText($_POST['categoria']);
        if (empty($id)) {
            echo "Error. Ingrese el id";
            exit;
        }

        if (empty($titulo)) {
            echo "Error. Ingrese un Titulo";
            exit;
        }
        if (empty($orden)) {
            echo "Error. Ingrese un Orden";
            exit;
        }
        if (empty($descripcion)) {
            echo "Error. Ingrese una descripción";
            exit;
        }
        if (empty($categoria)) {
            echo "Error. Ingrese una Categoria";
            exit;
        }

        $db->setQuery("SELECT id_tramite FROM tramites WHERE id_tramite NOT IN ($id) AND id_tramite_categoria = $categoria AND orden='$orden'");
            $rows = $db->loadObject();

            if (!empty($rows)) {
                echo "Error. El orden para el tramite ya existe";
                exit;
            }
       
        $db->setQuery("UPDATE tramites SET titulo='$titulo', id_tramite_categoria='$categoria', orden='$orden', descripcion='$descripcion' WHERE id_tramite=$id");

        if (!$db->alter()) {
            echo "Error. " . $db->getError();
        }
        else {
            echo "Tabla Tramites modificada correctamente";
        }

        break;

    case 'eliminar':

        $db      = DataBase::conectar();
        $success = false;
        $id      = $db->clearText($_POST['id']);
        $titulo  = $db->clearText($_POST['titulo']);

        $db->setQuery("DELETE FROM tramites WHERE id_tramite=$id");

        if ($db->alter()) {
            echo "Tabla Tramites eliminada correctamente";
        } else {
            echo "Error al eliminar '$Titulo'. " . $db->getError();
        }

        break;
}
