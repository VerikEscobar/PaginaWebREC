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
									<button type="button" class="btn btn-primary" id="agregar" data-toggle="modal" data-target="#modal_principal">Agregar Menú</button>
								</div>
							</div>
						</div>
						<table id="tabla" data-url="inc/administrar-menus-data.php?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="false" data-side-pagination="server" data-classes="table table-hover table-condensed" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
						
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
										<input type="hidden" name="hidden_id_menu" id="hidden_id_menu">
										<div class="modal-body">
											<div class="row">
												<div class="col-md-12 col-sm-12">
													<div class="form-row">
														<div class="form-group col-md-6 col-sm-6">
															<label for="menu_padre">Menu Padre</label>
															<select id="menu_padre" name="menu_padre" class="form-control">
                                                                <option value="Activo">Activo</option>
                                                                <option value="Inactivo">Inactivo</option>
                                                            </select>
														</div>
														<div class="form-group col-md-6 col-sm-6">
															<label for="menu">Menu</label>
															<input class="form-control input-sm" type="text" name="menu" id="menu" autocomplete="off">
														</div>
														<div class="form-group col-md-6 col-sm-6">
															<label for="titulo">Título</label>
															<input class="form-control input-sm" type="text" name="titulo" id="titulo" autocomplete="off">
														</div>
														<div class="form-group col-md-6 col-sm-6">
															<label for="url">Url</label>
															<input class="form-control input-sm" type="text" name="url" id="url" autocomplete="off">
														</div>
														<div class="form-group col-md-5 col-sm-5">
															<label for="icono">Icono</label>
															<input class="form-control input-sm" type="text" name="icono" id="icono" autocomplete="off">
														</div>
														<div class="form-group col-md-3 col-sm-3">
															<label for="orden">Orden Ubicación</label>
															<input class="form-control input-sm" type="text" name="orden" id="orden" autocomplete="off">
														</div>
														<div class="form-group col-md-4 col-sm-4">
															<label for="estado">Estado</label>
															<select id="estado" name="estado" class="form-control">
                                                                <option value="Habilitado">Habilitado</option>
                                                                <option value="Deshabilitado">Deshabilitado</option>
                                                            </select>
														</div>
														<div class="form-group col-md-12 col-sm-12">
															<p class="pt-3"><b>Nota:</b> Al deshabilitar un menú los submenús que este contenga no serán visualizados por mas que esten habilitados.</p>
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
						
					</div><!-- End col-12 -->
                </div><!-- End Page Content -->
            </div><!-- End Container fluid  -->
        </div><!-- End Page wrapper  -->
       <?php include 'footer.php'; ?>
    </div><!-- End Wrapper -->
<script type="text/javascript">

	// MENUS
	function menus () {
		$.ajax({
			dataType: 'json', async: true, cache: false, url: 'inc/administrar-menus-data.php', type: 'POST', data: {q: 'ver_menus'},
			beforeSend: function() {
				NProgress.start();
			},
			success: function (json){
				$('#menu_padre').empty();
				$('#menu_padre').append('<option value="">-</option>');
				$.each(json, function(key, value) {
					$('#menu_padre').append('<option value="'+ value.id_menu + '">' + value.menu + '</option>');
				});
				NProgress.done();
			},
			error: function (xhr) {
				NProgress.done();
				alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error');
			}
		});
	}
	$("#estado, #menu_padre").select2({
        theme: "bootstrap4",
        width: 'style',
        selectOnClose: true,
		minimumResultsForSearch: Infinity,
    });
	
	function iconosFila(value, row, index) {
		return [
			'<button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm editar" title="Editar datos"><i class="fa fa-pencil-alt"></i>&nbsp; Editar</button>'
		].join('');
	}
	
	window.accionesFila = {
		'click .editar': function (e, value, row, index) {
			$('#modalLabel').html('Editar Menú');
			$('#formulario').attr('action', 'editar');
			$('#eliminar').show();
			$('#modal_principal').modal('show');
			$("#hidden_id_menu").val(row.id_menu);
			setTimeout(() => {
				$("#menu_padre").val(row.id_menu_padre).trigger('change');
			}, 500);
			if (row.submenu != "-") {
				$("#menu").val(row.submenu);
			} else {
				$("#menu").val(row.menu);
			}
			$("#titulo").val(row.titulo);
			$("#url").val(row.url);
			$("#icono").val(row.icono);
			$("#orden").val(row.orden);
			$("#estado").val(row.estado).trigger('change');
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
			case 'Habilitado':
				return { value: 'Pagada', css: {"color": "#187e63", "font-weight": "500" } }
			break;
			case 'Deshabilitado':
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
		// sortName: "orden",
		sortOrder: 'asc',
		trimOnSearch: false,
		columns: [
			[
				{	field: 'id_menu', align: 'left', valign: 'middle', title: 'ID Menú', sortable: true, visible: false }, 
				{	field: 'orden', align: 'left', valign: 'middle', title: 'Orden Ubicación', sortable: true	},
				{	field: 'id_menu_padre', align: 'left', valign: 'middle', title: 'ID Menú Padre', sortable: true, visible: false }, 
				{	field: 'menu', align: 'left', valign: 'middle', title: 'Menu', sortable: true	},
				{	field: 'submenu', align: 'left', valign: 'middle', title: 'Submenu', sortable: true	},
				{	field: 'titulo', align: 'left', valign: 'middle', title: 'Título', sortable: true	},
				{	field: 'url', align: 'left', valign: 'middle', title: 'Url', sortable: true	},
				{	field: 'icono', align: 'center', valign: 'middle', title: 'Icono', sortable: true	},
				{	field: 'estado', align: 'center', valign: 'middle', title: 'Estado', cellStyle: color, sortable: true	},
				{	field: 'editar', align: 'center', valign: 'middle', title: 'Editar', sortable: false, events: accionesFila,  formatter: iconosFila	}
			]
		]
	});
	
	$('#agregar').click(function(){
		$('#modalLabel').html('Agregar Menú');
		$('#formulario').attr('action', 'cargar');
	});
	
	$('#modal_principal').on('show.bs.modal', function (e) {
		menus();
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
		$('#estado').val('Habilitado').trigger('change');
		$('#menu_padre').val('').trigger('change');
	}
	
	//GUARDAR NUEVO REGISTRO O CAMBIOS EDITADOS
	$("#formulario").submit(function(e){
		e.preventDefault();
		var data = $(this).serializeArray();
		$.ajax({
			url: 'inc/administrar-menus-data?q='+$(this).attr("action"),
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
		var menu = $("#menu").val();
		swal({   
			title: "¿Eliminar Menu "+menu+"?",
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
				url: 'inc/administrar-menus-data.php',
				cache: false,
				data: {q: 'eliminar', id_menu: $("#hidden_id_menu").val(), menu: menu },
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