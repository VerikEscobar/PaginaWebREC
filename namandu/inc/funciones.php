<?php
require __DIR__.'/auth/autoload.php';
$auth = new \Delight\Auth\Auth($db_auth);
require_once "mysql.php";

function url(){
	$host=$_SERVER['HTTP_HOST'];
	return "https://$host/registrocivil";
}

function url_pagina(){
	$host=$_SERVER['HTTP_HOST'];
	return "https://$host/registrocivil/";
}

function verificaLogin($pag = ""){
	require __DIR__.'/auth/autoload.php';
	$auth = new \Delight\Auth\Auth($db_auth);
	if (!$auth->isLoggedIn()){
		header("Location: ".url());
		exit;
	}else if ($auth->isLoggedIn() && !$auth->isNormal()){
		header("Location: ".url());
		exit;
	} else if ($pag) {
		// VERIFICAMOS SI TIENE PERMISO SOBRE LA PÁGINA
		$pagina = str_replace('.php', '', $pag);
		$id_usuario = $auth->getUserId();

		$db = DataBase::conectar();
		$db->setQuery("SELECT u.id FROM users u JOIN roles_menu rm ON rm.id_rol=u.id_rol JOIN menus m ON rm.id_menu=m.id_menu WHERE u.id=$id_usuario AND m.url LIKE '%/$pagina'");
		$row = $db->loadObject();
		if (!$row) {
			header("Location: ".url());
			exit;
		}
	}
	return true;
}

function url_amigable($url_tmp){
	//header('Content-Type: text/html; charset=utf-8');
	//Convertimos a minúsculas y UTF8
	$url_utf8 = mb_strtolower($url_tmp, 'UTF-8');
	//Reemplazamos espacios por guion
	$find = array(' ', '&', '\r\n', '\n', '+');
	$url_utf8 = str_replace ($find, '-', $url_utf8);
	
	//Convertimos todos los caracteres especiales a ASCII NO ANDA CON PHP 5.4
	//$url_utf8 = iconv('UTF-8', 'ASCII//TRANSLIT', $url_utf8); 
	
	$url_utf8 = strtr(utf8_decode($url_utf8), 
			utf8_decode('_àáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
							'-aaaaaaaceeeeiiiionoooooouuuuyy');
	
	//Ya que usamos TRANSLIT en el comando iconv, tenemos que limpiar los simbolos que quedaron
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url_utf8);
	return $url;
}
function resizeImage($sourceImage, $targetImage, $maxWidth, $maxHeight, $quality = 90){
	// Obtain image from given source file.
	if (!$image = @imagecreatefromjpeg($sourceImage))
	{
		return false;
	}
	// Get dimensions of source image.
	list($origWidth, $origHeight) = getimagesize($sourceImage);
	if ($maxWidth == 0)
	{
		$maxWidth  = $origWidth;
	}
	if ($maxHeight == 0)
	{
		$maxHeight = $origHeight;
	}
	// Calculate ratio of desired maximum sizes and original sizes.
	$widthRatio = $maxWidth / $origWidth;
	$heightRatio = $maxHeight / $origHeight;
	// Ratio used for calculating new image dimensions.
	$ratio = min($widthRatio, $heightRatio);
	// Calculate new image dimensions.
	$newWidth  = (int)$origWidth  * $ratio;
	$newHeight = (int)$origHeight * $ratio;
	// Create final image with new dimensions.
	$newImage = imagecreatetruecolor($newWidth, $newHeight);
	imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
	imagejpeg($newImage, $targetImage, $quality);
	// Free up the memory.
	imagedestroy($image);
	imagedestroy($newImage);
	return true;
}
function limpia_archivo($url_tmp) {
	$url_utf8 = mb_strtolower($url_tmp, 'UTF-8');
	$find = array(' ', '&', '\r\n', '\n', '+');
	$url_utf8 = str_replace ($find, '-', $url_utf8);
	$url_utf8 = strtr(utf8_decode($url_utf8), 
			utf8_decode('_àáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
							'-aaaaaaaceeeeiiiionoooooouuuuyy');
	//Ya que usamos TRANSLIT en el comando iconv, tenemos que limpiar los simbolos que quedaron
	$find = array('/[^a-z0-9.\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url_utf8);
	return $url;
}
function mesEspanol($mes){
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	return $meses[$mes-1];
}

function datosUsuario($username){
	$db = DataBase::conectar();
	$db->setQuery("SELECT * from users where username='$username'");
	$u = $db->loadObject();
	return $u;
}

function permisos($username) {
	$db = DataBase::conectar();
	$nombre_archivo = basename($_SERVER['PHP_SELF']);
	$pagina = str_replace('.php', '', $nombre_archivo);
	$db->setQuery("SELECT r.rol, m.titulo AS titulo_pagina, rm.acceso, rm.insertar, rm.editar, rm.eliminar
					FROM users u
					JOIN roles r ON r.id_rol=u.id_rol
					JOIN roles_menu rm ON rm.id_rol=r.id_rol
					JOIN menus m ON rm.id_menu=m.id_menu
					WHERE username='$username' AND m.url LIKE '%/$pagina'");
	$p = $db->loadObject();
	return $p;
}

function verRol($auth){
	// ROLES_MASK 1 = ADMIN
	// ROLES_MASK 2 = VENTAS
	// ROLES_MASK 4 = DEPOSITO
	// SUMAR ROLES PARA TENER EL ROLES_MASK
	// $auth->admin()->getRolesForUserById($id_usuario); POR ID DE USUARIO
	return $auth->getRoles(); //DEL USUARIO LOGUEADO
}

function menu($id_usuario) {
	$db = DataBase::conectar();
    $db->setQuery("SELECT m.id_menu, rm.id_rol, IFNULL(m.id_menu_padre, 0) AS id_menu_padre, m.menu, m.url, m.icono, m.orden
					FROM menus m
					JOIN roles_menu rm ON m.id_menu=rm.id_menu
					JOIN users u ON u.id_rol=rm.id_rol
					WHERE m.estado='Habilitado' AND u.id=$id_usuario
					UNION
					SELECT mp.id_menu, rm.id_rol, IFNULL(mp.id_menu_padre, 0) AS id_menu_padre, mp.menu, mp.url, mp.icono, mp.orden
					FROM menus m
					JOIN roles_menu rm ON m.id_menu=rm.id_menu
					JOIN menus mp ON m.id_menu_padre=mp.id_menu
					JOIN users u ON u.id_rol=rm.id_rol
					WHERE mp.estado='Habilitado' AND u.id=$id_usuario
					ORDER BY orden+1");

    $rows = $db->loadObjectList();

	// Se agrupan los menus y submenus
    $menus = [];
    foreach ($rows as $menu) {
		$menus[$menu->id_menu_padre][] = $menu;
    }

	// Funcion recursiva que crea el html
    function html($id_menu, $menus) {
        $html = "";
        if (isset($menus[$id_menu])) {
            foreach ($menus[$id_menu] as $menu) {
				// Menus
                if(!isset($menus[$menu->id_menu])) {
                    $html .= '<li><a href="' . $menu->url .'">' . $menu->menu . $menu->icono . '</a></li>';
				}
				// Menus padres
                if(isset($menus[$menu->id_menu])) {
					$html .= '<li><a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">' . $menu->icono .'<span class="hide-menu">' . $menu->menu . '</span></a>
								<ul aria-expanded="false" class="collapse">';
                    $html .= html($menu->id_menu, $menus);
					$html .= '</ul>
							</li>';
                }
            }
        }
        return $html;
    }
    echo html(0, $menus);
}

function datosSucursal($username){
	$db = DataBase::conectar();
	$db->setQuery("SELECT * FROM sucursales s INNER JOIN users u ON u.id_sucursal=s.id_sucursal AND u.username='$username'");
	$u = $db->loadObject();
	return $u;
}

function configuracionSistema(){
	$db = DataBase::conectar();
	$db->setQuery("SELECT * FROM configuracion WHERE estado=1");
	$config = $db->loadObject();
	return $config;
}

function datosEmpresa($id_sucursal){
	$db = DataBase::conectar();
	$db->setQuery("SELECT ruc, razon_social, id_sucursal, sucursal, nombre_empresa, concat(nombre_empresa,' - ',sucursal) as sucursal_name, direccion, ciudad, departamento FROM sucursales WHERE id_sucursal=$id_sucursal AND estado=1 ORDER BY sucursal");
	$u = $db->loadObject();
	return $u;
}

function fechaLatina($fecha){
    $fecha = substr($fecha,0,10);
	/*$date = new DateTime($fecha);
	return $date->format('d/m/Y');*/
    list($anio,$mes,$dia)=explode("-",$fecha);
	if (!$anio){
		return "";
	}else{
		return $dia."/".$mes."/".$anio;
	}
}
function fechaLatinaHora($fecha){
	/*$date = new DateTime($fecha);
	return $date->format('d/m/Y H:i');*/
    list($anio,$mes,$dia)=explode("-",$fecha);
	$hora = substr($fecha,11,5);
	if (!$anio){
		return "";
	}else{
		return substr($dia,0,2)."/".$mes."/".$anio." ".$hora;
	}
}
function fechaMYSQL($fecha){
    $fecha = substr($fecha,0,10);
    list($dia,$mes,$anio)=explode("/",$fecha);
    return $anio."-".$mes."-".$dia;
}
function fechaMYSQLHora($fecha){
    $fecha_sola = substr($fecha,0,10);
	$fecha_hora = substr($fecha,11,16);
    list($dia,$mes,$anio)=explode("/",$fecha_sola);
	list($hora,$min) = explode(":",$fecha_hora);
    return $anio."-".$mes."-".$dia." ".$hora.":".$min;
}
function getAutoincrement($table){
	$db = DataBase::conectar();
	$db->setQuery("SELECT LPAD(`AUTO_INCREMENT`,9,'0') as auto FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '$table'");
	$r = $db->loadObject()->auto;
	return $r;
}
function redondearGs($gs){
	if (strlen($gs) >= 4){
	   $a = (int)$gs / 100;
	   $b = round($a);
	   $c = $b * 100;
	   return $c;
	}else if (strlen($gs) <= 3)	{
		$a = (int)$gs / 100;
		$b = round($a);
	    $c = $b * 100;
		return $c;
	} 
}
function separadorMiles($number){
	if (is_numeric($number)){
		$nro=number_format($number,0, ".", ".");
		return $nro;
	}
}
function separadorMilesDecimales($number){
	if (is_numeric($number)){
		$nro=number_format($number,2, ",", ".");
		return $nro;
	}
}
function quitaSeparadorMiles($x){
	if($x) {
		return str_replace('.','',$x);
	}else{
		return 0;
	}
}
function fechaEspanol($x){
	$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	if ($x == "dia"){
		return $dias[date('w')];
	}else{
		return $dias[date('w')].", ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	}
}

function nombrePagina($pagina){
	$db2 = DataBase::conectar();
	$db2->setQuery("SELECT titulo from menus where url like '%".$pagina."'");
	$pa = $db2->loadObject();
	return $pa->titulo;
}
/*function verificaLogin($pag){
	session_start([
		 'cookie_lifetime' => 86400,
	]);
	
	if(!isset($_SESSION['id_usuario']) && !isset($_COOKIE['3a60fbdR3c0Rd4R0ebf5'])){
		header('Location:index.php');
	}else if (isset($_COOKIE['3a60fbdR3c0Rd4R0ebf5'])){
		$_SESSION['id_usuario']=$_COOKIE['3a60fbdR3c0Rd4R0ebf5'];
		$_SESSION['usuario']=datosUsuario($_SESSION['id_usuario'])->nombre_usuario;
	}
	
	if($pag){
		//VERIFICAMOS SI TIENE PERMISO SOBRE LA PÁGINA
		$pag_tmp = explode("/",$pag);
		$pagina = end($pag_tmp);
		$id_usu = $_SESSION['id_usuario'];
		$db = DataBase::conectar();
		$db->setQuery("SELECT u.id_usuario FROM usuarios u INNER JOIN roles_menu rm ON rm.id_rol=u.rol INNER JOIN menus m ON rm.id_menu=m.id_menu WHERE md5(id_usuario)='$id_usu' AND m.url like '%/$pagina'");
		$row = $db->loadObject();
		if (!$row){
			echo "<p style='font:bold 16px Tahoma'>PAGINA NO ENCONTRADA<br>Si cree que se trata de un error, favor consulte con el administrador del sistema.<br><a href=".url()."/>Volver al Inicio</a></p>";
			exit;
		}
	}
	
}*/
function alertDismiss($msj, $tipo){
	
	switch ($tipo){
		case 'error':
			$salida = "<div class='alert alert-danger'> <i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;$msj&nbsp;&nbsp;&nbsp;<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					 <span aria-hidden='true'>&times;</span></button></div>";
		break;
		
		case 'error_span':
			$salida = "<span class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<span class='glyphicon glyphicon-exclamation-sign'>&nbsp;</span>$msj</span>";
		break;
		
		case 'ok':
			$salida = "<div class='alert alert-success'> <i class='fa fa-check-circle'></i>&nbsp;&nbsp;$msj&nbsp;&nbsp;&nbsp;<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>&times;</span></button></div>";
		break;
		
		case 'ok_span':
			$salida = "<span class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<span class='glyphicon glyphicon-ok'>&nbsp;</span>$msj</span>";
		break;
		
		case 'yellow':
			$salida = "<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<span class='glyphicon glyphicon-ok'>&nbsp;</span>$msj</div>";
		break;
		
	}
	return $salida; 
}
function sweetAlert($msj, $tipo){
	return ["msj"=>$msj, "tipo"=>"error"];
}
function piePagina(){
	$pie = "<div id='footer'>
		  		<div class='container'>
					<p class='text-muted'>".datosConfig('nombre_sistema')." - Desarrollado por <a href='http://www.freelancer.com.py' target='blank'>Freelancers del Paraguay</a>
					</p>
				</div>
			</div>";
	return $pie;
}
function exportarExcel($datos, $titulo){
		
	$hoy=date('d-m-Y');
	$nombre='xls/Exportado_'.$titulo.'_'.$hoy.".xls";
	
	$xml = simplexml_load_string($datos);
	$salida = "<table border='1'>";
	foreach ($xml->Worksheet->Table->Row as $row) {
	   $celda = $row->Cell;
	   $salida .= "<tr>".$celda;
	   //echo "\t";
	   foreach ($celda as $cell) {
			$salida .= "<td>".$cell->Data."</td>";
			//echo "\t";
		}
		$salida .= "</tr>";
	}
	$salida .= "</table>";
	//print $salida;
	
	file_put_contents($nombre, utf8_decode($salida));
	
	echo $nombre;
}
function ceiling($number=NULL, $significance=1)
{
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
}
class Password {
    const SALT = 'freelancerpy';
    public static function hash($password) {
        return hash('sha512', self::SALT . $password);
    }
    public static function verify($password, $hash) {
        return ($hash == self::hash($password));
    }
}
/**
 * Retorna el id de la ultima caja si esta abierta
 */
function obtener_id_caja() {
	$db = DataBase::conectar();
	$db->setQuery("SELECT id_caja FROM caja WHERE estado = 'Abierta' AND id_caja = (SELECT MAX(id_caja) FROM caja)");
	$row = $db->loadObject();

	return ($row) ? $row->id_caja : null;
}

/**
 * Retorna los datos de la ultima caja de una sucursal si esta abierta
 */
function datosCaja($id_sucursal) {
	$db = DataBase::conectar();
	$db->setQuery("SELECT * FROM caja WHERE estado = 'Abierta' AND id_caja = (SELECT MAX(id_caja) FROM caja WHERE id_sucursal=$id_sucursal)");
	$row = $db->loadObject();

	return $row;
}
function convert($size,$unit){
	if($unit == "KB"){
	  	return $fileSize = round($size / 1024,4);	
	}
	if($unit == "MB"){
	  	return $fileSize = round($size / 1024 / 1024,4);	
	}
	if($unit == "GB"){
	  	return $fileSize = round($size / 1024 / 1024 / 1024,4);	
	}
}
	
?>