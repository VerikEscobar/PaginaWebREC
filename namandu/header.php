<?php 
	include 'inc/funciones.php';
	$tit_tmp4 = "{$pag_padre}";
	$tit_tmp3 = str_replace("-"," ",$tit_tmp4);
	$tit_tmp2 = explode(".php",$tit_tmp3);
	$tit_tmp = $tit_tmp2[0];
	$pagina = basename($_SERVER['PHP_SELF']);
	$datos_sistema = configuracionSistema();
	$nombre_sistema = $datos_sistema->nombre_sistema;
	$favicon = $datos_sistema->favicon;
	if ($tit_tmp <> "index"){
		if (verificaLogin($pagina)){
			$datos_empresa = datosSucursal($auth->getUsername());
			$establecimiento = $datos_empresa->nombre_empresa."  ".$datos_empresa->sucursal;
			$permisos = permisos($auth->getUsername());
			$titulo = $permisos->titulo_pagina." - <span style='font-size:16px'>".$establecimiento."</span>";
			$title = $permisos->titulo_pagina." - ".$nombre_sistema." - ".$establecimiento;
		}
	}else{
		$titulo = ucwords($tit_tmp);
		$title = $nombre_sistema;
	}
	
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favicon; ?>">
    <title><?php echo $title; ?></title>
	<style type="text/css"><?php include 'colores.php'; ?></style>
	<script src="dist/js/jquery/jquery-3.2.1.min.js"></script>
	<!-- Bootstrap popper Core JavaScript -->
	<script src="dist/js/popper/popper.min.js"></script>
    <script src="dist/js/bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js?v=2"></script>
	<script src="dist/js/jquery.easing.min.js"></script>
	<script src="dist/js/jquery-ui/jquery-ui.min.js"></script>
    <!-- Sweet-Alert  -->
	<link href="dist/js/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <script src="dist/js/sweetalert/sweetalert.min.js"></script>
	<!-- croppie -->
    <link href="croppie/croppie.min.css" rel="stylesheet" type="text/css">
    <script src="croppie/croppie.min.js"></script>
	<!-- TOAST -->
	<link href="dist/js/toast-master/css/jquery.toast.css" rel="stylesheet">
	<script src="dist/js/toast-master/js/jquery.toast.js"></script>
	<!-- Nprogress -->
	<script src="dist/js/nprogress/nprogress.js"></script>
	<link href="dist/js/nprogress/nprogress.css?v=1" rel="stylesheet">
	<!-- Select2 -->
	<link href="dist/js/select2/css/select2.min.css" rel="stylesheet">
	<link href="dist/js/select2/css/select2-bootstrap4.min.css" rel="stylesheet">
	<script src="dist/js/select2/js/select2.full.min.js"></script>
	<script src="dist/js/select2/js/select2-dropdownPosition.js"></script>
	<script src="dist/js/select2/js/i18n/es.js"></script>
	<!-- Custom -->
	<link href="dist/css/style.css?v=2" rel="stylesheet">
	<link href="dist/css/custom.css?v=6" rel="stylesheet">
	<script src="dist/js/custom.js?v=1"></script>