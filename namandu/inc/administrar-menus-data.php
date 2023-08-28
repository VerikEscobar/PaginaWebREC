<?php
	include ("funciones.php");
	verificaLogin();
	$q = $_REQUEST['q'];
	$usuario = $auth->getUsername();

	switch ($q) {
		
		case 'ver':
			$db = DataBase::conectar();
			$where = "";
			//Parametros de ordenamiento, busqueda y paginacion
			$limit = $_REQUEST['limit'];
			$offset	= $_REQUEST['offset'];
			$order = $_REQUEST['order'];
			$sort = $_REQUEST['sort'];
			if (!isset($sort)) $sort = 'm.orden+1';
			

			if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
				$search = $_REQUEST['search'];
				$where = "AND CONCAT_WS(' ', m.id_menu, mp.menu, m.menu, m.titulo, m.url, m.icono, m.orden, m.estado) LIKE '%$search%'";
			}
			
			$db->setQuery("SELECT SQL_CALC_FOUND_ROWS m.id_menu, m.id_menu_padre, IF(m.id_menu_padre IS NULL, m.menu, mp.menu) AS menu, IF(m.id_menu_padre IS NOT NULL, m.menu, '-') AS submenu, m.titulo, m.url, m.icono, m.orden, m.estado
							FROM menus m LEFT JOIN menus mp ON m.id_menu_padre=mp.id_menu
							WHERE 1=1 $where ORDER BY $sort $order");
			$rows = $db->loadObjectList();
			
			$db->setQuery("SELECT FOUND_ROWS() as total");		
			$total_row = $db->loadObject();
			$total = $total_row->total;
			
			if ($rows){
				$salida = array('total' => $total, 'rows' => $rows);
			}else{
				$salida = array('total' => 0, 'rows' => array());
			}
			
			echo json_encode($salida);
		
		break;

		case 'ver_menus':
			$db = DataBase::conectar();
			
			$db->setQuery("SELECT m.id_menu, IF(m.id_menu_padre IS NULL, m.menu, CONCAT(mp.menu,'->',m.menu)) AS menu FROM menus m LEFT JOIN menus mp ON m.id_menu_padre=mp.id_menu ORDER BY m.orden+1, mp.orden+1");
			$rows = $db->loadObjectList();
			
			echo json_encode($rows);
		
		break;

		case 'cargar':
			$db = DataBase::conectar();
			$db->autocommit(FALSE);
			$id_menu_padre = $db->clearText($_POST['menu_padre']);
			$menu = $db->clearText($_POST['menu']);
			$titulo = $db->clearText($_POST['titulo']);
			$url = $db->clearText($_POST['url']);
			$icono = $db->clearText($_POST['icono']);
			$orden = $db->clearText($_POST['orden']);
			$estado = $db->clearText($_POST['estado']);
			
			if (empty($id_menu_padre)) { $id_menu_padre = "NULL"; }
			if (empty($menu)) { echo "Error. Favor ingrese el nombre del menú"; exit; }
			if (empty($icono)) { echo "Error. Favor ingrese el icono del menú"; exit; }
			if (empty($orden)) { echo "Error. Favor ingrese el orden del menú"; exit; }
			if (empty($estado)) { echo "Error. Favor ingrese el estado del rol"; exit; }
		

			$db->setQuery("INSERT INTO menus(id_menu_padre, menu, titulo, url, icono, orden, estado) VALUES($id_menu_padre,'$menu','$titulo','$url','$icono','$orden','$estado');");
		
			if (!$db->alter()) {
				echo "Error. ".$db->getError();
				$db->rollback();  //Revertimos los cambios
				exit;
			}

			$id_menu = $db->getLastID();

			// Se le da permisos al rol 1 = Administrador del Sistema
			$db->setQuery("INSERT INTO roles_menu(id_rol, id_menu, acceso, insertar, editar, eliminar) VALUES(1, $id_menu, 1, 1, 1, 1)");
		
			if (!$db->alter()) {
				echo "Error. ".$db->getError();
				$db->rollback();  //Revertimos los cambios
				exit;
			}

			$db->commit(); //Insertamos los datos en la BD
			echo "Menú agregado correctamente";
		break;
					
		case 'editar':
		
			$db = DataBase::conectar();
			$id_menu = $db->clearText($_POST['hidden_id_menu']);
			$id_menu_padre = $db->clearText($_POST['menu_padre']);
			$menu = $db->clearText($_POST['menu']);
			$titulo = $db->clearText($_POST['titulo']);
			$url = $db->clearText($_POST['url']);
			$icono = $db->clearText($_POST['icono']);
			$orden = $db->clearText($_POST['orden']);
			$estado = $db->clearText($_POST['estado']);
			
			if (empty($id_menu_padre)) { $id_menu_padre = "NULL"; }
			if (empty($menu)) { echo "Error. Favor ingrese el nombre del menú"; exit; }
			if (empty($icono)) { echo "Error. Favor ingrese el icono del menú"; exit; }
			if (empty($orden)) { echo "Error. Favor ingrese el orden del menú"; exit; }
			if (empty($estado)) { echo "Error. Favor ingrese el estado del rol"; exit; }

			$db->setQuery("UPDATE menus SET id_menu_padre=$id_menu_padre, menu='$menu', titulo='$titulo', url='$url', icono='$icono', orden='$orden', estado='$estado' WHERE id_menu = '$id_menu'");
	
			if (!$db->alter()) {
				echo "Error. ". $db->getError();
			} else {
				echo "Menú modificado correctamente";
			}

		break;
		
		case 'eliminar':
			$db = DataBase::conectar();
			$db->autocommit(FALSE);
			$id_menu = $db->clearText($_POST['id_menu']);
			$menu = $db->clearText($_POST['menu']);

			$db->setQuery("DELETE FROM menus WHERE id_menu = $id_menu");

			if (!$db->alter()) {
				echo "Error al eliminar el menú '$menu'. ". $db->getError();
				$db->rollback();  //Revertimos los cambios
				exit;
			}

			$db->setQuery("DELETE FROM roles_menu WHERE id_menu = $id_menu");

			if (!$db->alter()) {
				echo "Error la eliminar la relacion entre los roles y el el menú '$menu'. ". $db->getError();
				$db->rollback();  //Revertimos los cambios
				exit;
			}

			$db->commit(); //Insertamos los datos en la BD
			echo "Menú '$menu' eliminado correctamente";
		break;		

	}


?>