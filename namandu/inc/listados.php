<?php
	include ("funciones.php");
	//verificaLogin();
	$q = $_REQUEST['q'];
	
	switch ($q){

		case 'clientes':
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_cliente, CONCAT(ruc,' - ', razon_social) AS cliente FROM clientes WHERE estado = 1 ORDER BY cliente");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;

		case 'usuarios':
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_usuario, nombre_usuario from usuarios order by 2");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;
		
		case 'vendedoras_por_suc':
			$where="";
			if (isset($_REQUEST['id'])){
				$where = "AND id_sucursal=".$_REQUEST['id'];
			}
			$db = DataBase::conectar();
			//$db->setQuery("SELECT id, nombre_apellido FROM users WHERE (roles_mask='2' OR roles_mask='1') AND username !='admin' AND username !='vendedor' $where");
			$db->setQuery("SELECT id, nombre_apellido FROM users WHERE id_rol='2' AND username !='admin' AND username !='vendedor' $where");
			$rows = $db->loadObjectList();	
			echo json_encode($rows);
		break;
		
		case 'proveedores':
			$db = DataBase::conectar();
			$db->setQuery("SELECT * FROM proveedores ORDER BY proveedor");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;
		
		case 'sucursales':
			$where="";
			if (isset($_REQUEST['id'])){
				$where = "AND s.id_sucursal=".$_REQUEST['id'];
			}
			$db = DataBase::conectar();
			$db->setQuery("SELECT s.ruc, s.razon_social, s.id_sucursal, concat_ws(' - ',s.nombre_empresa,s.sucursal) as sucursal, s.direccion, s.ciudad, s.departamento FROM sucursales s WHERE s.estado=1 $where ORDER BY s.sucursal");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;

		case 'roles':
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_rol, rol from roles where estado = 1 order by 2");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;
		
		case 'menus':
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_menu, CONCAT_WS('->',menu,submenu) as menu FROM menus WHERE estado=1 ORDER BY orden");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;
		
		case 'departamentos':
			$db = DataBase::conectar();
			$db->setQuery("SELECT departamento from departamentos where estado = 1 order by 1");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		
		break;
		
		case 'categorias':
			$db = DataBase::conectar();
			$db->setQuery("SELECT * FROM categorias ORDER BY categoria");
			/*$db->setQuery("SELECT a.id_categoria padre_id, a.categoria padre_cat, a.descripcion padre_desc,
							IFNULL(b.id_categoria,a.id_categoria) as hijo_id, IFNULL(b.categoria,a.categoria) hijo_cat, 
							b.descripcion hijo_desc
							FROM categorias a LEFT OUTER JOIN categorias b ON a.id_categoria = b.id_padre
							WHERE a.id_padre = 0 ORDER BY a.categoria, b.categoria");*/
			$rows = $db->loadObjectList();
			if ($rows) echo json_encode($rows);
		break;
		
		case 'familias':
			$db = DataBase::conectar();
			$buscar = trim($db->clearText($_GET['query']));
			$db->setQuery("SELECT * FROM categorias WHERE categoria LIKE '%$buscar%' ORDER BY categoria LIMIT 8");
			$rows = $db->loadObjectList();
			
			$suggestions = array();
			if ($rows){
				foreach ($rows AS $row) {
					$suggestions[] = array(
						"value" => trim($row->categoria),
						"data" => $row->id_categoria
					);
				}
			}
			$result = array(
				"query" => "Unit",
				"suggestions" => $suggestions
			);
			echo json_encode($result);
		break;
	}

?>