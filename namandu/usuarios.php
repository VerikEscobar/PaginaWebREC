<?php 
	$pag_padre = basename($_SERVER['PHP_SELF']);
	include 'header.php';
?>
<link rel="stylesheet" href="dist/js/bootstrap-table/bootstrap-table.css">
<script src="dist/js/bootstrap-table/bootstrap-table.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/tableExport.js"></script>
<script src="dist/js/bootstrap-table/locale/bootstrap-table-es-CL.min.js"></script>
<script src="dist/js/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js"></script>
<!-- Bootstrap-select -->
<link href="dist/js/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
<script src="dist/js/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="dist/js/bootstrap-select/js/defaults-es_ES.js"></script>
</head>
<body class="<?php include 'menu-class.php';?> fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Cargando Ñamandú...</p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php include 'topbar.php'; include 'leftbar.php' ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <?php //include 'titulo.php'; ?>
                <div class="row">
                    <div class="col-12">
						<div id="toolbar">
							<div class="form-inline" role="form">
								<div class="form-group">
									<button type="button" class="btn btn-primary" id="agregar" data-toggle="modal" data-target="#modal_principal">Registrar Usuario</button>
								</div>
							</div>
						</div>
						<table id="tabla" data-url="inc/usuarios-data.php?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-classes="table table-hover table-condensed" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
						
						<!-- MODA PRINCIPAL -->
						<div class="modal" id="modal_principal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
							<div class="modal-dialog modal-md modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="modalLabel"></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form class="form" id="formulario" method="post" enctype="multipart/form-data" action="">
										<input type="hidden" name="hidden_id_usuario" id="hidden_id_usuario">
										<div class="modal-body">
											<div class="row">
												<div class="col-md-12 col-sm-12">
													<div class="form-row">
														<div class="form-group col-md-3 col-sm-12">
															<label for="ci">Cédula de Identidad</label>
															<div class="input-group">
															<input class="form-control input-sm" type="text" name="ci" id="ci" autocomplete="off" required>
															
																<div class="input-group-append">
                                                                    <button class="btn btn-success" type="button" id="calcular" title="Calcular precios de venta"><i id="spin_sea" class="fas fa-search"></i></button>
                                                                </div>
															</div>
														</div>
														<div class="form-group col-md-9 col-sm-12">
															<label for="nombre">Nombre y Apellido</label>
															<input class="form-control input-sm" type="text" name="nombre" id="nombre" autocomplete="off" required>
														</div>
														
														<div class="form-group col-md-3 col-sm-12">
															<label for="telefono">Teléfono / Celular</label>
															<input class="form-control input-sm" id="telefono" name="telefono" type="text" autocomplete="off">
														</div>
														<div class="form-group col-md-5 col-sm-12">
															<label for="direccion">Dirección</label>
															<input class="form-control input-sm" id="direccion" name="direccion" type="text" autocomplete="off">
														</div>
														<div class="form-group col-md-4 col-sm-12">
															<label for="email">E-mail</label>
															<input class="form-control input-sm" id="email" name="email" type="email" autocomplete="off">
														</div>
													</div>
													<div class="form-row">
														<div class="form-group col-md-4 col-sm-12">
															<label for="dpto">Departamento/Área</label>
															<select id="dpto" name="dpto" class="form-control">
																<option value="">&nbsp</option>
																<option value="Sistemas">Sistemas</option>
																<option value="Gerencia">Gerencia</option>
																<option value="Ventas">Ventas</option>
																<option value="Operaciones">Operaciones</option>
																<option value="Contabilidad">Contabilidad</option>
															</select>
														</div>
														<div class="form-group col-md-4 col-sm-12">
															<label for="cargo">Cargo</label>
															<input class="form-control input-sm" id="cargo" name="cargo" type="text">
														</div>
														<div class="form-group col-md-4 col-sm-12">
															<label for="sucursal">Sucursal</label>
															<select id="sucursal" name="sucursal" class="form-control" required></select>
														</div>
													</div>
													<div class="form-row">
														<div class="form-group col-md-4 col-sm-12">
															<label for="rol">Rol del Sistema</label>
															<select id="rol" name="rol" class="form-control" required></select>
															<!--<select id="rol" name="rol[]" class="form-control input-sm selectpicker show-tick" data-actions-box="false" data-style="form-control input-sm" data-size="5" style="padding: 0;" multiple required>
															</select>-->
														</div>
														<div class="form-group col-md-4 col-sm-12">
															<label for="usuario">Nombre de Usuario</label>
															<input class="form-control input-sm" id="usuario" name="usuario" type="text" autocomplete="off" required>
														</div>
														<div id="passConten" class="form-group col-md-4 col-sm-12">
															<label for="password">Contraseña</label>
															<input class="form-control input-sm" id="password" name="password" type="text" autocomplete="off">
														</div>
														<div id="estado_cont" class="form-group col-md-4 col-sm-12">
															<label for="estado">Estado</label>
															<select id="estado" name="estado" class="form-control">
																<option value="0">Activo</option>
																<option value="3">Bloqueado</option>
																<option value="4">Contraseña Expirada</option>
															</select>
														</div>
														<div id="contenC" class="form-group col-md-4 col-sm-12 mt-2">
														<label for="expira">&nbsp;</label>
															<div class="custom-control custom-checkbox">
															  <input type="checkbox" class="custom-control-input" name="expira" id="expira" checked>
															  <label class="custom-control-label" for="expira">Cambiar contraseña en el primer inicio</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										
										</div>
										<div class="modal-footer">
											<button id="restablecer" type="button" class="btn btn-warning mr-auto" style="display:none">Restablecer Contraseña</button>
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
											<button type="submit" class="btn btn-success">Guardar</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						
					</div><!-- End col-12 -->
                </div><!-- End Page Content -->
            </div><!-- End Container fluid  -->
        </div><!-- End Page wrapper  -->
       <?php include 'footer.php'; ?>
    </div><!-- End Wrapper -->
<script type="text/javascript">

	/*$('.modal').on("hidden.bs.modal", function (e) { //fire on closing modal box
		if ($('.modal:visible').length) { // check whether parent modal is opend after child modal close
			$('body').addClass('modal-open'); // if open mean length is 1 then add a bootstrap css class to body of the page
		}
	});*/

	$("#dpto, #sucursal, #estado, #rol").select2({
        theme: "bootstrap4",
        width: 'auto',
        minimumResultsForSearch: 10,
        selectOnClose: true,
        dropdownPosition: 'below',
    });
	
	function iconosFila(value, row, index) {
		return [
			'<button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm editar" title="Editar datos"><i class="fa fa-pencil"></i>&nbsp; Editar</button>'
		].join('');
	}
	
	window.accionesFila = {
		'click .editar': function (e, value, row, index) {
			$('#modalLabel').html('Editar Usuario');
			$('#formulario').attr('action', 'editar');
			$('#restablecer').show();
			$('#estado_cont').show();
			$('#modal_principal').modal('show');
			$("#hidden_id_usuario").val(row.id);
			$("#nombre").val(row.nombre_apellido);
			$("#ci").val(row.ci);
			//$('#sucursal').prop("disabled", true);
			$("#telefono").val(row.telefono);
			$("#direccion").val(row.direccion);
			$("#email").val(row.email);
			$("#cargo").val(row.cargo);
			$("#dpto").select2('trigger', 'select', {
                data: { id: row.departamento, text: row.departamento }
            });
            $("#estado").select2('trigger', 'select', {
                data: { id: row.status, text: row.estado }
            });
            $('#passConten').hide();
            $('#contenC').hide();
			$("#usuario").val(row.username).attr("disabled", true);
			//Marcamos los roles del usuario
			/*$.ajax({
				dataType: 'json', async: false, cache: false, url: 'inc/usuarios-data.php', type: 'POST', data: {q: 'ver_roles_usuario', id:row.id },
				beforeSend: function(){
					NProgress.start();
				},
				success: function (json){
					$('#rol').selectpicker('val', json);
					$('#rol').selectpicker('refresh');
					NProgress.done();
				},
				error: function (xhr) {
					alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error');
				}
			});*/
			$("#rol").val(row.id_rol).trigger('change');
		}
	}

	function color (value, row, index) {
		switch (value) {
			case 'Activo':
				return { value: 'Pagada', css: {"color": "#187e63", "font-weight": "500" } }
			break;
			case 'Pendiente':
				return { css: {"color": "black", "font-weight": "500" } }
			break;
			case 'Bloqueado':
				return { css: {"color": "red", "font-weight": "500" } }
			break;
			case 'Contraseña Expirada':
				return { css: {"color": "gray", "font-weight": "500" } }
			break;
			default:
				return {};
			break;
		}
	}

	$("#tabla").bootstrapTable({
		mobileResponsive: true,
		height: $(window).height()-90,
		pageSize: Math.floor(($(window).height()-90)/50),
		columns: [
			[
				{	field: 'id', align: 'left', valign: 'middle', title: 'ID', sortable: true, visible: true }, 
				{	field: 'username', align: 'left', valign: 'middle', title: 'Usuario', sortable: true	},
				{	field: 'nombre_apellido', align: 'left', valign: 'middle', title: 'Nombre', sortable: true	},
				{	field: 'departamento', align: 'left', valign: 'middle', title: 'Dpto./Área', sortable: true	},
				{	field: 'cargo', align: 'left', valign: 'middle', title: 'Cargo', sortable: true, visible: false	},
				{	field: 'telefono', align: 'left', valign: 'middle', title: 'Tel.', sortable: true	},
				{	field: 'direccion', align: 'left', valign: 'middle', title: 'Dirección', sortable: true, visible:false },
				{	field: 'ci', align: 'left', valign: 'middle', title: 'C.I.', sortable: true, visible: false	}, 
				{	field: 'email', align: 'left', valign: 'middle', title: 'E-mail', sortable: true, visible: false	},
				{	field: 'foto', align: 'left', valign: 'middle', title: 'Foto', sortable: true, visible:false	},
				//{	field: 'roles', align: 'left', valign: 'middle', title: 'Rol', sortable: true },
				{	field: 'id_rol', align: 'left', valign: 'middle', title: 'ID Rol', sortable: true, visible: false }, 
				{	field: 'rol', align: 'left', valign: 'middle', title: 'Rol', sortable: true }, 
				{	field: 'estado', align: 'center', valign: 'middle', title: 'Estado', cellStyle: color, sortable: true }, 
				{	field: 'id_sucursal', visible:false }, 
				{	field: 'status', visible:false }, 
				{	field: 'sucursal', align: 'left', valign: 'middle', title: 'Sucursal', sortable: true }, 
				{	field: 'fecha_registro', align: 'left', valign: 'middle', title: 'Fecha alta', sortable: true, visible:false	}, 
				{	field: 'ultimo_acceso', align: 'left', valign: 'middle', title: 'Último acceso', sortable: true	}, 
				{	field: 'usuario', align: 'left', valign: 'middle', title: 'Usuario Carga', sortable: true, visible: false	}, 
				{	field: 'editar', align: 'center', valign: 'middle', title: 'Editar', sortable: false, events: accionesFila,  formatter: iconosFila	}
			]
		]
	});
	
	//Altura de tabla automatica
	$(document).ready(function () {
		$(window).bind('resize', function(e)
			{
			  if (window.RT) clearTimeout(window.RT);
			  window.RT = setTimeout(function()
			  {
				$("#tabla").bootstrapTable('refreshOptions', { 
					height: $(window).height()-90,
					pageSize: Math.floor(($(window).height()-90)/50),
				});
			  }, 100);
			});
	});
	
	$('#agregar').click(function(){
		$('#modalLabel').html('Registrar Usuario');
		$('#formulario').attr('action', 'cargar');
	});
	
	$('#modal_principal').on('show.bs.modal', function (e) {
		if ($('#formulario').attr('action')=="cargar"){
			limpiarModal(e);
			$("#usuario").attr("disabled", false);
			$('#passConten').show();
			$('#contenC').show();
			//$('#sucursal').prop("disabled", true);
			$('#estado_cont').hide();
			$('#restablecer').hide();
		}
	});
	
	$('#modal_principal').on('shown.bs.modal', function (e) {
		$("form .select2:first,input[type!='hidden']:first").focus();
	});
	
	$(".modal-dialog").draggable({
		handle: ".modal-header"
	});
			
	function limpiarModal(){
		$(document).find('form').trigger('reset');
		$('#rol, #sucursal, #dpto').val(null).trigger('change');
		//$('#rol').selectpicker('val', '');
		//$('#rol').selectpicker('refresh');
	}
	
	//GUARDAR NUEVO REGISTRO O CAMBIOS EDITADOS
	$("#formulario").submit(function(e){
		e.preventDefault();
		var data = $(this).serializeArray();
		//data.push({name: 'detalles', value: JSON.stringify($('#tabla_detalles').bootstrapTable('getData'))});
		$.ajax({
			url: 'inc/usuarios-data?q='+$(this).attr("action"),
			dataType: 'html',
			type: 'post',
			contentType: 'application/x-www-form-urlencoded',
			data: data,
			beforeSend: function(){
				NProgress.start();
			},
			success: function(datos, textStatus, jQxhr){
				NProgress.done();
				var n = datos.toLowerCase().indexOf("error");
				if (n == -1) {
					$('#modal_principal').modal('hide');
					alertDismissJS(datos, "ok");
					$('#tabla').bootstrapTable('refresh', {url: 'inc/usuarios-data.php?q=ver'});
				}else{
					alertDismissJS(datos, "error");
				}
			},
			error: function(jqXhr, textStatus, errorThrown){
				NProgress.done();
				alertDismissJS($(jqXhr.responseText).text().trim(), "error");
			}
		});
	});
		
	
	//ELIMINAR
	$('#restablecer').click(function(){
		var nombre = $("#usuario").val();
		swal({   
			title: "¿Restablecer Usuario: "+nombre+"?",
			text: "La nueva contraseña sera: "+nombre+"",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "var(--primary)",   
			confirmButtonText: "Restablecer",   
			cancelButtonText: "Cancelar",
			closeOnConfirm: false
		}, function(){
			$.ajax({
				dataType: 'html',
				async: false,
				type: 'POST',
				url: 'inc/usuarios-data.php',
				cache: false,
				data: {q: 'restablecer_pass', id: $("#hidden_id_usuario").val(), nombre: $("#usuario").val() },	
				beforeSend: function(){
					NProgress.start();
				},
				success: function (data, status, xhr) {
					var n = data.toLowerCase().indexOf("error");
					if (n == -1) {
						$('#modal_principal').modal('hide');
						swal.close();
						alertDismissJS(data, "ok");
						NProgress.done();
						$('#tabla').bootstrapTable('refresh', {url: 'inc/usuarios-data.php?q=ver'});
					}else{
						alertDismissJS(data, "error");
					}
				},
				error: function (jqXhr) {
					alertDismissJS($(jqXhr.responseText).text().trim(), "error");
				}
			});
		});
	});
	
	$('#usuario').on('keyup change', function (e) {
		$('#password').val($('#usuario').val().trim());
	})
	
	
	//AL PERDER EL FOCO Y SI EL MODAL ESTÁ VISIBLE
	$('#calcular').click(function() {
		if ($('#modal_principal').is(':visible')){
			if ($("#ci").val()){
				buscarCI();
			}
		}
	});
	function buscarCI(){
		$.ajax({
			dataType: 'json', async: true, cache: false, url: 'inc/usuarios-data.php', timeout:10000, type: 'POST', data: {q:'buscar_ci', ci: $("#ci").val()},
			beforeSend: function(){
				//$("#spin_ci").show();
			},
			success: function (json){
				//$("#spin_ci").hide();
				$('#nombre').val(json.nombre);
			},
			error: function (jqXhr) {
				//$("#spin_ci").hide();
				alertDismissJS($(jqXhr.responseText).text().trim(), "error");
			}
		});
	}
	
	//ROLES
	$.ajax({
		dataType: 'json', async: false, cache: false, url: 'inc/usuarios-data.php', type: 'POST', data: {q: 'ver_roles'},
		beforeSend: function(){
			NProgress.start();
		},
		success: function (json){
			$('#rol').empty();
			$.each(json, function(key, value) {
				//$('#rol').append('<option value="'+ value.rol + '">' + value.rol + '</option>');
				$('#rol').append('<option value="'+ value.id_rol + '">' + value.rol + '</option>');
			 });
			NProgress.done();
			$('#rol').selectpicker('refresh');
		},
		error: function (xhr) {
			NProgress.done();
			alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error');
		}
	});
	
	//SUCURSALES
	$.ajax({
		dataType: 'json', async: true, cache: false, url: 'inc/listados.php', type: 'POST', data: {q: 'sucursales'},
		beforeSend: function(){
			NProgress.start();
		},
		success: function (json){
			$('#sucursal').html("");
			$.each(json, function(key, value) {
				$('#sucursal').append('<option value="'+ value.id_sucursal +'">' + value.sucursal + '</option>');
			});
			NProgress.done();
		},
		error: function (jqXhr) {
			NProgress.done();
			alertDismissJS($(jqXhr.responseText).text().trim(), "error");
		}
	});
</script>
</body>
</html>