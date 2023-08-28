<?php
	//Consulta está en header.php
	$nombre_usuario = $datos_empresa->nombre_apellido;
	$usu_foto = $datos_empresa->foto;
	$logo_horizontal = $datos_sistema->logo_horizontal;
?>
<header class="topbar">
	<nav class="navbar top-navbar navbar-expand-md navbar-dark">
		<!-- ============================================================== -->
		<!-- Logo -->
		<!-- ============================================================== -->
		<!--<div class="navbar-header">
			<a class="navbar-brand" href="index">
				<b><img src="<?php echo $logo_horizontal; ?>" alt="homepage" class="dark-logo" /></b>
				<span>
					<img src="<?php echo $logo_horizontal; ?>" alt="homepage" class="dark-logo" />
					<img src="dist/images/logo-light-text.png" class="light-logo" alt="homepage" />
				</span> 
			</a>
		</div>-->
		<!-- ============================================================== -->
		<!-- End Logo -->
		<!-- ============================================================== -->
		<div class="navbar-collapse">
			<!-- ============================================================== -->
			<!-- toggle and nav items -->
			<!-- ============================================================== -->
			<ul class="navbar-nav mr-auto">
				<!-- This is  -->
				<li class="nav-item hidden-sm-up"> <a class="nav-link nav-toggler waves-effect waves-light" href="javascript:void(0)"><i class="fas fa-bars"></i></a></li>
				<!-- ============================================================== -->
				<!-- Search -->
				<!-- ============================================================== -->
				<!--<li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="fa fa-search"></i></a>
					<form class="app-search">
						<input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="fa fa-times"></i></a>
					</form>
				</li>-->
				<li class="nav-item">
					<span class="titulo"><?php echo $titulo; ?></span>
					
				</li>
			</ul>
			<?php echo $menu_central; ?>
			<ul class="navbar-nav my-lg-0">
				<!-- ============================================================== -->
				<!-- User profile and search -->
				<!-- ============================================================== -->
				<!--<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="dist/images/users/1.jpg" alt="user" class="img-circle" width="30"></a>
				</li>-->
				
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo $usu_foto ?>" alt="user" class="img-circle" width="30"></a>
					<div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
						<span class="with-arrow"><span class="bg-primary"></span></span>
						<div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
							<div class=""><img src="<?php echo $usu_foto ?>" alt="user" class="img-circle" width="60"></div>
							<div class="m-l-10">
								<h4 class="m-b-0"><?php echo $nombre_usuario; ?></h4>
								<!--<p class=" m-b-0">varun@gmail.com</p>-->
							</div>
						</div>
						<!--<a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
						<a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i> My Balance</a>
						<a class="dropdown-item" href="javascript:void(0)"><i class="ti-email m-r-5 m-l-5"></i> Inbox</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="javascript:void(0)"><i class="ti-settings m-r-5 m-l-5"></i> Account Setting</a>
						<div class="dropdown-divider"></div>-->
						<a class="dropdown-item" href="./logout"><i class="fa fa-power-off m-r-5 m-l-5"></i> Cerrar Sesión</a>
						<!--<div class="dropdown-divider"></div>
						<div class="p-l-30 p-10"><a href="javascript:void(0)" class="btn btn-sm btn-success btn-rounded">View Profile</a></div>-->
					</div>
				</li>
				<!-- ============================================================== -->
				<!-- User profile and search -->
				<!-- ============================================================== -->
			</ul>
		</div>
	</nav>
</header>