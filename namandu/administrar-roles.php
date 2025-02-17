<?php 
require __DIR__.'/inc/auth/autoload.php';
	$auth = new \Delight\Auth\Auth($db_auth);
	if(!$auth->isLoggedIn()) header("Location: login");
	$pag_padre = basename($_SERVER['PHP_SELF']);
	include 'header.php';
?>
<link rel="stylesheet" href="dist/js/bootstrap-table/bootstrap-table.css">
<script src="dist/js/bootstrap-table/bootstrap-table.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/tableExport.js"></script>
<script src="dist/js/bootstrap-table/locale/bootstrap-table-es-CL.min.js"></script>
<script src="dist/js/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js"></script>
<!-- select2 -->
<link href="dist/js/select2/css/select2.min.css" rel="stylesheet">
<link href="dist/js/select2/css/select2-bootstrap4.min.css" rel="stylesheet">
<script src="dist/js/select2/js/select2.min.js"></script>
<script src="dist/js/select2/js/select2-dropdownPosition.js"></script>
<script src="dist/js/select2/js/i18n/es.js"></script>

<style>
	table .selected td {
		background-color: #fff !important;
		color: #000 !important;
	}
</style>

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
									<?php if ($permisos->insertar) { ?><button type="button" class="btn btn-primary" id="agregar" data-toggle="modal" data-target="#modal_principal">Agregar Rol</button><?php } ?>
								</div>
							</div>
						</div>
						<table id="tabla" data-url="inc/administrar-roles-data.php?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="true" data-side-pagination="server" data-classes="table table-hover table-condensed" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
						
						<!-- MODA PRINCIPAL -->
						<div class="modal fade" id="modal_principal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
							<div class="modal-dialog modal-md modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="modalLabel"></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form class="form" id="formulario" method="post" enctype="multipart/form-data" action="">
										<input type="hidden" name="hidden_id_rol" id="hidden_id_rol">
										<div class="modal-body">
											<div class="row">
												<div class="col-md-12 col-sm-12">
													<div class="form-row">
														<div class="form-group col-md-8 col-sm-8">
															<label for="rol">Rol</label>
															<input class="form-control input-sm" type="text" name="rol" id="rol" autocomplete="off">
														</div>
														<div class="form-group col-md-4 col-sm-4">
															<label for="estado">Estado</label>
															<select id="estado" name="estado" class="form-control">
                                                                <option value="Activo">Activo</option>
                                                                <option value="Inactivo">Inactivo</option>
                                                            </select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button id="eliminar" type="button" class="btn btn-danger mr-auto" style="display:none">Eliminar</button>		
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
											<button type="submit" class="btn btn-success">Guardar</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<!-- MODA PERMISOS -->
						<div class="modal fade" id="modal_permisos" tabindex="-1" role="dialog" aria-labelledby="modalLabelPermisos" aria-hidden="true">
							<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="modalLabelPermisos"></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<input type="hidden" name="permisos_id_rol" id="permisos_id_rol">
									<div class="modal-body">
										<div class="row">
											<div class="col-md-12 col-sm-12">
												<div id="toolbarPermisos">
													<div class="form-inline" role="form">
														<div class="form-group">
															<h4 id="permisos_rol"></h4>
														</div>
													</div>
												</div>
												<table id="tabla_permisos" data-url="" data-toolbar="#toolbarPermisos" data-show-export="false" data-search="false" data-show-refresh="true" data-show-toggle="false" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="false" data-side-pagination="server" data-classes="table table-hover table-condensed" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
										<button type="button" class="btn btn-success" id="btn-editar-permisos">Guardar</button>
									</div>
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
	
	function iconosFila(value, row, index) {
		return [
			'<button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm editar" title="Editar datos"><i class="fa fa-pencil-alt"></i>&nbsp; Editar</button>'+
			'<button type="button" onclick="javascript:void(0)" class="btn btn-success btn-sm permisos ml-2" title="Editar Permisos"><i class="fa fa-plus"></i>&nbsp; Permisos</button>'
		].join('');
	}
	
	window.accionesFila = {
		'click .editar': function (e, value, row, index) {
			$('#modalLabel').html('Editar Rol');
			$('#formulario').attr('action', 'editar');
			$('#modal_principal').modal('show');
			$("#hidden_id_rol").val(row.id_rol);
			$("#rol").val(row.rol);
			$("#estado").val(row.estado).trigger('change');
			if (row.id_rol == 1) {
				$("#estado").prop('disabled', true);
				$('#eliminar').hide();
			} else {
				$('#eliminar').show();
				$("#estado").prop('disabled', false);
			}
		},
		'click .permisos': function (e, value, row, index) {
			$('#modalLabelPermisos').html('Permisos');
			$('#modal_permisos').modal('show');
			$("#permisos_id_rol").val(row.id_rol);
			$("#permisos_rol").html(row.rol);
			$('#tabla_permisos').bootstrapTable('refresh', { url: 'inc/administrar-roles-data.php?q=ver_menus&id_rol=' + row.id_rol });
		}
	}
	
	//TOOLTIP EN COLUMNAS TRUNCADAS
	$('#tabla').on('mouseenter', ".verTooltip", function () {
		var $this = $(this);
		$this.attr('title', $this.text());
	});
	
	//CSS PARA TRUNCAR COLUMNAS MUY LARGAS
	function truncarColumna(value,row,index, field){
	  return {
		classes: 'verTooltip',
		css: {"max-width": "150px" , "white-space": "pre", "overflow": "hidden", "text-overflow": "ellipsis"}
	  };
	}

	function color (value, row, index) {
		switch (value) {
			case 'Activo':
				return { value: 'Pagada', css: {"color": "#187e63", "font-weight": "500" } }
			break;
			case 'Inactivo':
				return { css: {"color": "black", "font-weight": "500" } }
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
		sortName: "id_rol",
		sortOrder: 'desc',
		trimOnSearch: false,
		columns: [
			[
				{	field: 'id_rol', align: 'left', valign: 'middle', title: 'ID Rol', sortable: true }, 
				{	field: 'rol', align: 'left', valign: 'middle', title: 'Rol', sortable: true	},
				{	field: 'estado', align: 'center', valign: 'middle', title: 'Estado', cellStyle: color, sortable: true	},
				<?php if ($permisos->editar) { ?>{	field: 'editar', align: 'center', valign: 'middle', title: 'Editar', sortable: false, events: accionesFila,  formatter: iconosFila, width: 200	}<?php } ?>
			]
		]
	});
	
	$('#agregar').click(function(){
		$('#modalLabel').html('Agregar Rol');
		$('#formulario').attr('action', 'cargar');
		$("#estado").prop('disabled', false);
	});
	
	$('#modal_principal').on('show.bs.modal', function (e) {
		if ($('#formulario').attr('action')=="cargar"){
			limpiarModal(e);
			$('#eliminar').hide();
		}
	});
	
	$('#modal_principal').on('shown.bs.modal', function (e) {
		$("form input[type!='hidden']:first").focus();
	});
	
	$(".modal-dialog").draggable({
		handle: ".modal-header"
	});
			
	function limpiarModal(){
		$(document).find('form').trigger('reset');
		$('#estado').val('Activo').trigger('change');
	}
	
	//GUARDAR NUEVO REGISTRO O CAMBIOS EDITADOS
	$("#formulario").submit(function(e){
		e.preventDefault();
		var data = $(this).serializeArray();
		$.ajax({
			url: 'inc/administrar-roles-data?q='+$(this).attr("action"),
			dataType: 'html',
			type: 'post',
			contentType: 'application/x-www-form-urlencoded',
			data: data,
			beforeSend: function(){
				NProgress.start();
			},
			success: function(datos, textStatus, jQxhr){
				var n = datos.toLowerCase().indexOf("error");
				if (n == -1) {
					$('#modal_principal').modal('hide');
					alertDismissJS(datos, "ok");
					$('#tabla').bootstrapTable('refresh');
				} else {
					alertDismissJS(datos, "error");
				}
				NProgress.done();
			},
			error: function(jqXhr, textStatus, errorThrown){
				NProgress.done();
				alertDismissJS($(jqXhr.responseText).text().trim(), "error");
			}
		});
	});
		
	
	//ELIMINAR
	$('#eliminar').click(function(){
		var rol = $("#rol").val();
		swal({   
			title: "¿Eliminar Rol "+rol+"?",
			text: "",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "var(--primary)",   
			confirmButtonText: "Eliminar",   
			cancelButtonText: "Cancelar",
			closeOnConfirm: false
		}, function(){
			$.ajax({
				dataType: 'html',
				async: false,
				type: 'POST',
				url: 'inc/administrar-roles-data.php',
				cache: false,
				data: {q: 'eliminar', id: $("#hidden_id_rol").val(), rol: rol },
				beforeSend: function(){
					NProgress.start();
				},
				success: function (data, status, xhr) {
					var n = data.toLowerCase().indexOf("error");
					if (n == -1) {
						swal.close();
						$('#modal_principal').modal('hide');
						$('#tabla').bootstrapTable('refresh');
						alertDismissJS(data, "ok");
					} else {
						alertDismissJS(data, "error");
					}
					NProgress.done();
				},
				error: function (jqXhr) {
					NProgress.done();
					alertDismissJS($(jqXhr.responseText).text().trim(), "error");
				}
			});
		});
	});

	$("#estado").select2({
        theme: "bootstrap4",
        width: 'style',
        selectOnClose: true,
		minimumResultsForSearch: Infinity,
	});

	////// EDITAR PERMISOS /////
	function inputCheckbox (clase, title, value) {
		if (value == "1") var checked = "checked"; else var checked = "";
		return [
			'<input type="checkbox" onclick="javascript:void(0)" class="' + clase + '" title="' + title + '"' + checked +'>'
		].join('');
	}

	function checkboxAcceso(value, row, index) {
		return inputCheckbox('acceso', 'Acceso a la página', value);
	}
	function checkboxInsertar(value, row, index) {
		return inputCheckbox('insertar', 'Insertar datos', value);
	}
	function checkboxEditar(value, row, index) {
		return inputCheckbox('editar', 'Modificar datos', value);
	}
	function checkboxEliminar(value, row, index) {
		return inputCheckbox('eliminar', 'Eliminar datos', value);
	}
	function checkboxTodos(value, row, index) {
		return inputCheckbox('todos', 'Todos los permisos', value);
	}

	// Actualiza los persmisos del menu
	function actualizarPermisos (field, value, row) {
		if (value == '1') var newValue = '0'; else var newValue = '1';
		$('#tabla_permisos').bootstrapTable('updateCellByUniqueId', { id: row.id_menu, field: field, value: newValue });

		if (field == 'todos' || (field == 'acceso' && newValue == '0')) {
			$('#tabla_permisos').bootstrapTable('updateByUniqueId', {
				id: row.id_menu,
				row: {
					acceso: newValue,
					insertar: newValue,
					editar: newValue,
					eliminar: newValue,
					todos: newValue,
				}
			});
		}
		if (row.acceso == '1' && row.insertar == '1' && row.editar == '1' && row.eliminar == '1') {
			$('#tabla_permisos').bootstrapTable('updateCellByUniqueId', { id: row.id_menu, field: 'todos', value: '1' });
		} else {
			$('#tabla_permisos').bootstrapTable('updateCellByUniqueId', { id: row.id_menu, field: 'todos', value: '0' });
		}
	}

	// Verifica si se debe desmarcar los menus que estan en un nivel superior, si es que tiene
	function actualizarPermisosSuperiores (field, value, row) {
		if (row.id_menu_padre) {
			var menus = $('#tabla_permisos').bootstrapTable('getData');
			var sigte_padre = true;
			menus.forEach(m => {
				if (m.id_menu_padre == row.id_menu_padre && m.id_menu != row.id_menu && m[field] == '0') {
					sigte_padre = false;
					return false;
				}
			});
			if (sigte_padre) {
				actualizarPermisos (field, value, $('#tabla_permisos').bootstrapTable('getRowByUniqueId', row.id_menu_padre));
				actualizarPermisosSuperiores (field, value, $('#tabla_permisos').bootstrapTable('getRowByUniqueId', row.id_menu_padre));
			}
		}
	}

	// Verifica si se debe desmarcar los menus que contiene, si es que tiene
	function actualizarPermisosInferiores (field, value, row) {
		var menus = $('#tabla_permisos').bootstrapTable('getData');
		menus.forEach(m => {
			if (row.id_menu == m.id_menu_padre) {
				actualizarPermisos (field, value, $('#tabla_permisos').bootstrapTable('getRowByUniqueId', m.id_menu));
				actualizarPermisosInferiores (field, value, $('#tabla_permisos').bootstrapTable('getRowByUniqueId', m.id_menu));
			}
		});
	}

	window.accionesFilaPermisos = {
		'click .acceso': function (e, value, row, index) {
			actualizarPermisos('acceso', value, row);
			actualizarPermisosSuperiores('acceso', value, row);
			actualizarPermisosInferiores('acceso', value, row);
		},
		'click .insertar': function (e, value, row, index) {
			actualizarPermisos('insertar', value, row);
			actualizarPermisosSuperiores('insertar', value, row);
			actualizarPermisosInferiores('insertar', value, row);
		},
		'click .editar': function (e, value, row, index) {
			actualizarPermisos('editar', value, row);
			actualizarPermisosSuperiores('editar', value, row);
			actualizarPermisosInferiores('editar', value, row);
		},
		'click .eliminar': function (e, value, row, index) {
			actualizarPermisos('eliminar', value, row);
			actualizarPermisosSuperiores('eliminar', value, row);
			actualizarPermisosInferiores('eliminar', value, row);
		},
		'click .todos': function (e, value, row, index) {
			actualizarPermisos('todos', value, row);
			actualizarPermisosSuperiores('todos', value, row);
			actualizarPermisosInferiores('todos', value, row);
		},
	}

	$("#tabla_permisos").bootstrapTable({
		mobileResponsive: true,
		height: $(window).height()-280,
		pageSize: Math.floor(($(window).height()-280)/50),
		// sortName: "orden",
		sortOrder: 'asc',
		trimOnSearch: false,
		uniqueId: 'id_menu',
		columns: [
			[
				{	field: 'id_menu', align: 'left', valign: 'middle', title: 'ID Menú', sortable: true, visible: false }, 
				{	field: 'orden', align: 'left', valign: 'middle', title: 'Orden Ubicación', sortable: true	},
				{	field: 'id_menu_padre', align: 'left', valign: 'middle', title: 'ID Menú Padre', sortable: true, visible: false }, 
				{	field: 'menu', align: 'left', valign: 'middle', title: 'Menu', sortable: true	},
				{	field: 'submenu', align: 'left', valign: 'middle', title: 'Submenu', sortable: true	},
				{	field: 'icono', align: 'center', valign: 'middle', title: 'Icono', sortable: true	},
				{	field: 'acceso', align: 'center', valign: 'middle', title: 'Acceso', sortable: false, events: accionesFilaPermisos, formatter: checkboxAcceso	},
				{	field: 'insertar', align: 'center', valign: 'middle', title: 'Insertar', sortable: false, events: accionesFilaPermisos, formatter: checkboxInsertar	},
				{	field: 'editar', align: 'center', valign: 'middle', title: 'Modificar', sortable: false, events: accionesFilaPermisos, formatter: checkboxEditar	},
				{	field: 'eliminar', align: 'center', valign: 'middle', title: 'Eliminar', sortable: false, events: accionesFilaPermisos, formatter: checkboxEliminar	},
				{	field: 'todos', align: 'center', valign: 'middle', title: 'Todos', sortable: false, events: accionesFilaPermisos, formatter: checkboxTodos	},
			]
		]
	});

	// GUARDAR MODIFICACION DE PERMISOS
	$("#btn-editar-permisos").click(function(e){
		e.preventDefault();
		var data = $(this).serializeArray();
		$.ajax({
			url: 'inc/administrar-roles-data?q=editar_permisos',
			dataType: 'html',
			type: 'post',
			contentType: 'application/x-www-form-urlencoded',
			data: { id_rol: $('#permisos_id_rol').val(), rol: $('#permisos_rol').html(), permisos: $('#tabla_permisos').bootstrapTable('getData') },
			beforeSend: function(){
				NProgress.start();
			},
			success: function(datos, textStatus, jQxhr){
				var n = datos.toLowerCase().indexOf("error");
				if (n == -1) {
					$('#modal_permisos').modal('hide');
					alertDismissJS(datos, "ok");
				} else {
					alertDismissJS(datos, "error");
				}
				NProgress.done();
			},
			error: function(jqXhr, textStatus, errorThrown){
				NProgress.done();
				alertDismissJS($(jqXhr.responseText).text().trim(), "error");
			}
		});
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
	
</script>
</body>
</html>