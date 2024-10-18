<?php
$pag_padre = basename($_SERVER['PHP_SELF']); 
include 'header.php';
if ($auth->isRemembered()) {
	header("Location: inicio");
	exit;
}
if (isset($_POST['login'])) {
	$db = DataBase::conectar();

	/*$rememberDuration = null;
	if (isset($_POST['mantener'])){
		if ($db->clearText($_POST['mantener'])== "on") {*/
			$rememberDuration = (int) (60 * 60 * 12); //DEJAMOS 12 HORAS DE CONEXION
		/*}
	}*/
	try {
		$usuario = $db->clearText($_POST['usuario']);
		$pass = $db->clearText($_POST['pass']);
		$input_pass="
			<div class='field-wrapper'>
				<input type='password' name='pass' id='pass'>
				<div class='field-placeholder'><span>Contraseña</span></div>
			</div>";
		if (empty($usuario) || empty($pass)) {
			$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Favor ingrese usuario y contraseña</div>';
		}else {
			$auth->loginWithUsername($usuario, $pass, $rememberDuration);
			if (!$auth->isNormal() && !$auth->isPendingReview()){
				$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Acceso denegado. Consulte con el administrador del sistema</div>';
			}else if($auth->isPendingReview()){
				$old_password = $pass;
				if (isset($_POST['repertir_pass'])){
					$repetir = $_POST['repertir_pass'];
					$nuevo_pass = $_POST['nuevo_pass'];
				//	if (!empty($repetir)){
						if ($nuevo_pass != $repetir){
							$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Contraseñas no coinciden. Favor verifique</div>';
							$input_pass="<input type='hidden' name='pass' id='pass' value='$old_password'>";
							$repetir_pass = "<div class='field-wrapper'><input type='password' name='nuevo_pass' id='nuevo_pass'><div class='field-placeholder'><span>Nueva Contraseña</span></div></div>
											<div class='field-wrapper'><input type='password' name='repertir_pass' id='repertir_pass'><div class='field-placeholder'><span>Repetir Contraseña</span></div></div>";
							$readonly = "readonly";
							echo "<script>setTimeout(function () { $('#nuevo_pass').focus(); },50);</script>";
						}else{
							try {
								$auth->changePassword($old_password, $nuevo_pass);
								$db->setQuery("UPDATE users SET status=0 WHERE id=".$auth->getUserId());
								if(!$db->alter()){
									echo "Error actualizando datos. ".$db->getError();
									exit;
								}else{
									$auth->loginWithUsername($usuario, $nuevo_pass, $rememberDuration);
									header("Location: inicio");
									exit;
								}
							}
							catch (\Delight\Auth\NotLoggedInException $e) {
								$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Usuario no logueado. Favor v = erifique</div>';
							}
							catch (\Delight\Auth\InvalidPasswordException $e) {
								$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Contraseña inválida. Favor verifique</div>';
							}
							catch (\Delight\Auth\TooManyRequestsException $e) {
								$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Demasiadas peticiones. Intente de nuevo más tarde.</div>';
							}
						}
					//}
				}else{
					$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Contraseña expirada. Favor ingrese una nueva para continuar</div>';
					$input_pass="<input type='hidden' name='pass' id='pass' value='$old_password'>";
					$repetir_pass = "<div class='field-wrapper'><input type='password' name='nuevo_pass' id='nuevo_pass'><div class='field-placeholder'><span>Nueva Contraseña</span></div></div>
									<div class='field-wrapper'><input type='password' name='repertir_pass' id='repertir_pass'><div class='field-placeholder'><span>Repetir Contraseña</span></div></div>";
					$readonly = "readonly";
					echo "<script>setTimeout(function () { $('#nuevo_pass').focus(); },50);</script>";
				}
			}else if($auth->isNormal()){
				header("Location: inicio");
				exit;
			}
		}
	}
	catch (\Delight\Auth\UnknownUsernameException $e) {
		$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp; Nombre de usuario incorrecto.</div>';
		echo "<script>setTimeout(function () { $('#pass').focus(); },50);</script>";
	}
	catch (\Delight\Auth\AmbiguousUsernameException $e) {
		$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Nombre de usuario ambiguo</div>';
	}
	catch (\Delight\Auth\InvalidPasswordException $e) {
		$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Usuario o contraseña incorrecta.</div>';
		echo "<script>setTimeout(function () { $('#pass').focus(); },50);</script>";
	}
	catch (\Delight\Auth\TooManyRequestsException $e) {
		$mensaje = '<div class="alert alert-danger" role="alert"><i class="fa fa-warning" aria-hidden="true"></i> &nbsp;Demasiadas peticiones. Favor reintente nuevamente.</div>';
	}
}else{
	$input_pass="<div class='field-wrapper'><input type='password' name='pass' id='pass'><div class='field-placeholder'><span>Contraseña</span></div></div>";
}
?>
<link href="dist/css/login.css" rel="stylesheet">
</head>
<div class="login" style="background-image:url(dist/images/background/login-register.jpg);">
	<form id="loginform" method="post" accept-charset="utf-8">
		<div class="form-wrapper-outer">
			<div class="form-logo">
				<img src="dist/images/logo.png" alt="logo">
			</div>
			<div class="form-greeting">
				<span>Bienvenido al Sistema Ñamandú</span>
			</div>
			<div class="field-wrapper">
				<?php echo $mensaje; ?>
			</div>
			<div class="field-wrapper">
				<input type="text" name="usuario" id="usuario" value="<?php echo $usuario; ?>" <?php echo $readonly; ?> autocomplete="off">
				<div class="field-placeholder"><span>Usuario</span></div>
			</div>
			<?php echo $input_pass; echo $repetir_pass; ?>
			<br>
			<div class="form-button">
				<button type="submit" name="login" class="btn btn-primary">Ingresar</button>
			</div>
		</div>
	</form>
</div>

<script>
	$(function () {
		$(".field-wrapper .field-placeholder").on("click", function () {
			$(this).closest(".field-wrapper").find("input").focus();
		});
		$(".field-wrapper input").on("keyup", function () {
			var value = $.trim($(this).val());
			if (value) {
				$(this).closest(".field-wrapper").addClass("hasValue");
			} else {
				$(this).closest(".field-wrapper").removeClass("hasValue");
			}
		});
		setTimeout(function () {
			if ($(".field-wrapper input").val()){
				$(".field-wrapper input").closest(".field-wrapper").addClass("hasValue");
			}
			
			<?php if (!isset($_POST['login'])) {
				echo "$('#usuario').focus();";
			} 
			?>
		},50);

	});

 </script>

</body>
</html>