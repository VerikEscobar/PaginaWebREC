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
                                    <button type="button" class="btn btn-primary mr-4" id="agregar" data-toggle="modal" data-target="#modal_principal">Agregar Menú</button>
                                </div>
                                <div class="form-group row">
                                    <label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">FILTRAR:</label>
                                    <div class="col-sm-10">
                                        <select id="tipo_filtro" name="tipo_filtro" class="select2" required>
                                            <option value="">[TODOS]</option>
                                            <option value="1">CABECERA</option>
                                            <option value="2">FOOTER</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table id="tabla" data-url="inc/menu-pagina-data?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="true" data-classes="table table-hover table-condensed" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
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
                                                        <div class="form-group col-md-3 col-sm-3">
                                                            <label for="orden">Orden Ubicación</label>
                                                            <input class="form-control input-sm" type="text" name="orden" id="orden" autocomplete="off">
                                                        </div>
                                                        <div class="form-group col-md-3 col-sm-4">
                                                            <label for="tipo">Tipo</label>
                                                            <select id="tipo" name="tipo" class="form-control">
                                                                <option value="1">CABECERA</option>
                                                                <option value="2">FOOTER</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2 col-sm-12">
                                                            <div style="margin-top: 0.5rem" class="custom-control custom-radio mr-sm-2">
                                                                <input type="radio" class="custom-control-input" id="checkPagina" name="url_tipo" value="1">
                                                                <label class="custom-control-label" for="checkPagina">PÁGINA DINÁMICA</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-2 col-sm-12">
                                                            <div style="margin-top: 0.5rem" class="custom-control custom-radio mr-sm-2">
                                                                <input type="radio" class="custom-control-input" id="checkEnlance" name="url_tipo" value="2">
                                                                <label class="custom-control-label" for="checkEnlance">ENLANCE</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-8 col-sm-12"></div>
                                                        <div style="display: none;" id="contenEnlance" class="form-group col-md-9 col-sm-12">
                                                            <label for="url">Enlance</label>
                                                            <input class="form-control input-sm" type="text" name="url" id="url" autocomplete="off">
                                                        </div>
                                                        <div style="display: none;" id="contenPagina" class="form-group col-md-9 col-sm-12">
                                                            <label for="pagina">Página</label>
                                                            <select id="pagina" name="pagina" class="form-control">
                                                            </select>
                                                        </div>
                                                        <div style="display: none;" id="contenTarget" class="form-group col-md-3 col-sm-12">
                                                            <label for="target">Target</label>
                                                            <select id="target" name="target" class="form-control">
                                                                <option value="1">_self (Cambia Pestaña)</option>
                                                                <option value="2">_blank (Nueva Pestaña)</option>
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
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
    <script type="text/javascript">
    // MENUS
    function menus() {
        $.ajax({
            dataType: 'json',
            async: true,
            cache: false,
            url: 'inc/menu-pagina-data',
            type: 'POST',
            data: { q: 'ver_menus' },
            beforeSend: function() {
                NProgress.start();
            },
            success: function(json) {
                $('#menu_padre').empty();
                $('#menu_padre').append('<option value="">-</option>');
                $.each(json, function(key, value) {
                    $('#menu_padre').append('<option value="' + value.id_menu_pagina + '">' + value.menu + '</option>');
                });
                NProgress.done();
            },
            error: function(xhr) {
                NProgress.done();
                alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error');
            }
        });
    }

    function paginas() {
        $.ajax({
            dataType: 'json',
            async: true,
            cache: false,
            url: 'inc/menu-pagina-data',
            type: 'POST',
            data: { q: 'ver_paginas' },
            beforeSend: function() {
                NProgress.start();
            },
            success: function(json) {
                $('#pagina').empty();
                $('#pagina').append('<option value="">[SELECCIONAR]</option>');
                $.each(json, function(key, value) {
                    $('#pagina').append('<option value="' + value.id_pagina + '">' + value.titulo + '</option>');
                });
                NProgress.done();
            },
            error: function(xhr) {
                NProgress.done();
                alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error');
            }
        });
    }

    $("#tipo_filtro").select2({
        theme: "bootstrap4",
        width: 200,
        selectOnClose: true,
        minimumResultsForSearch: Infinity,
    });
    $("#estado, #menu_padre, #tipo, #pagina, #target").select2({
        theme: "bootstrap4",
        width: 'style',
        selectOnClose: true,
        minimumResultsForSearch: Infinity,
    });

    function iconosFila(value, row, index) {
        return [
            '<button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm cambiar-estado mr-1" title="Cambiar Estado"><i class="fas fa-exchange-alt"></i></button><button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm editar mr-2" title="Editar datos"><i class="fa fa-pencil-alt"></i>&nbsp; Editar</button>'
        ].join('');
    }

    window.accionesFila = {
        'click .cambiar-estado': function(e, value, row, index) {
            let nextStatus = (row.nombre_estado == "Activo" ? "Inactivo" : "Activo");
            let status = (row.nombre_estado == "Activo" ? 0 : 1);
            swal({
                title: `Cambiar Estado`,
                text: `¿Actualizar estado de '${row.menu}' a '${nextStatus}'?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "var(--primary)",
                confirmButtonText: "Cambiar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            }, function() {
                $.ajax({
                    url: 'inc/menu-pagina-data?q=cambiar-estado',
                    type: 'post',
                    data: { id: row.id_menu_pagina, estado: status },
                    beforeSend: function() {
                        NProgress.start();
                    },
                    success: function(datos, textStatus, jQxhr) {
                        NProgress.done();
                        var n = datos.toLowerCase().indexOf("error");
                        if (n == -1) {
                            swal.close();
                            alertDismissJS(datos, "ok");
                            $('#tabla').bootstrapTable('refresh', { url: 'inc/menu-pagina-data?q=ver' });
                        } else {
                            swal.close();
                            alertDismissJS(datos, "error");
                        }
                    },
                    error: function(jqXhr, textStatus, errorThrown) {
                        NProgress.done();
                        alertDismissJS($(jqXhr.responseText).text().trim(), "error");
                    }
                });
            });
        },
        'click .editar': function(e, value, row, index) {
            $('#checkEnlance').change(function() {
                if (this.checked) {
                    $('#contenEnlance').show();
                    $('#contenTarget').show();
                    $('#contenPagina').hide();
                }
            });
            $('#checkPagina').change(function() {
                if (this.checked) {
                    $('#contenPagina').show();
                    $('#contenTarget').show();
                    $('#contenEnlance').hide();
                }
            });
            $('#modalLabel').html('Editar Menú');
            $('#formulario').attr('action', 'editar');
            $('#eliminar').show();
            $('#modal_principal').modal('show');
            $("#tipo").select2('trigger', 'select', {
                data: { id: row.tipo, text: row.nombre_tipo }
            });
            $("#hidden_id_menu").val(row.id_menu_pagina);
            setTimeout(() => {
                $("#menu_padre").val(row.id_menu_padre).trigger('change');
            }, 500);
            if (row.submenu != "-") {
                $("#menu").val(row.submenu);
            } else {
                $("#menu").val(row.menu);
            }
            $("#titulo").val(row.titulo);
            $("#orden").val(row.orden);

            if (row.url_tipo == 1) {

                $("#checkPagina").prop("checked", true);
                $('#contenPagina').show();
                $('#contenTarget').show();
                $('#contenEnlance').hide();
                $('#url').val('');
                setTimeout(() => {
	                $("#pagina").val(row.id_pagina).trigger('change');
	            }, 100);
            }
            if (row.url_tipo == 2) {

                $("#checkEnlance").prop("checked", true);
                $('#contenEnlance').show();
                $('#contenTarget').show();
                $('#contenPagina').hide();
                $('#pagina').val('').trigger('change');
                $('#url').val(row.url);
            }
            if (row.url_target == 2) {
                $("#target").select2('trigger', 'select', {
                    data: { id: "2", text: "_blank (Nueva Pestaña)" }
                });
            }

        }
    }


    $("#tipo_filtro").change(function() {
        var tipo_filtro = $("#tipo_filtro").val();
        $('#tabla').bootstrapTable('refresh', { url: 'inc/menu-pagina-data?q=ver&tipo=' + tipo_filtro });
    });

    //TOOLTIP EN COLUMNAS TRUNCADAS
    $('#tabla').on('mouseenter', ".verTooltip", function() {
        var $this = $(this);
        $this.attr('title', $this.text());
    });

    //CSS PARA TRUNCAR COLUMNAS MUY LARGAS
    function truncarColumna(value, row, index, field) {
        return {
            classes: 'verTooltip',
            css: { "max-width": "150px", "white-space": "pre", "overflow": "hidden", "text-overflow": "ellipsis" }
        };
    }

    function colorEstado(data) {
        switch (data) {
            case 'Inactivo':
                return '<span style="text-transform: capitalize;" class="badge badge-pill badge-danger">' + data + '</span></b>';
                break;
                break;
            case 'Activo':
                return '<span style="text-transform: capitalize;" class="badge badge-pill badge-success">' + data + '</span></b>';
                break;
            default:
                return '<span style="text-transform: capitalize;" class="badge badge-pill badge-default">' + data + '</span></b>';
        }
    }

    $("#tabla").bootstrapTable({
        mobileResponsive: true,
        height: $(window).height() - 90,
        pageSize: Math.floor(($(window).height() - 90) / 50),
        // sortName: "orden",
        sortOrder: 'asc',
        trimOnSearch: false,
        columns: [
            [
                { field: 'id_menu_pagina', align: 'left', valign: 'middle', title: 'ID Menú', sortable: true, visible: false },
                { field: 'orden', align: 'left', valign: 'middle', title: 'Orden Ubicación', sortable: true },
                { field: 'id_menu_padre', align: 'left', valign: 'middle', title: 'ID Menú Padre', sortable: true, visible: false },
                { field: 'menu', align: 'left', valign: 'middle', title: 'Menu', sortable: true },
                { field: 'submenu', align: 'left', valign: 'middle', title: 'Submenu', sortable: true },
                { field: 'titulo', align: 'left', valign: 'middle', title: 'Título', sortable: true },
                { field: 'url', align: 'left', valign: 'middle', title: 'Url', sortable: true, cellStyle:truncarColumna },
                { field: 'nombre_tipo', align: 'left', valign: 'middle', title: 'Tipo', sortable: true },
                { field: 'tipo', align: 'left', valign: 'middle', title: 'Tipo', sortable: true, visible: false },
                { field: 'fecha', align: 'center', valign: 'middle', title: 'Fecha', sortable: true },
                { field: 'nombre_estado', align: 'center', valign: 'middle', title: 'Estado', sortable: true, formatter: colorEstado },
                { field: 'editar', align: 'center', valign: 'middle', title: 'Acciones', sortable: false, events: accionesFila, formatter: iconosFila },
                { field: 'url_tipo', visible: false },
                { field: 'url_target', visible: false },
                { field: 'nombre_pagina', visible: false },
                { field: 'id_pagina', visible: false }
            ]
        ]
    });

    $('#agregar').click(function() {
        $('#modalLabel').html('Agregar Menú');
        $('#formulario').attr('action', 'cargar');

        $('#checkEnlance').change(function() {
            if (this.checked) {
                $('#contenEnlance').show();
                $('#contenTarget').show();
                $('#contenPagina').hide();
                $('#pagina').val('').trigger('change');
            }
        });
        $('#checkPagina').change(function() {
            if (this.checked) {
                $('#contenPagina').show();
                $('#contenTarget').show();
                $('#contenEnlance').hide();
                $('#url').val('');
            }
        });
    });

    $('#modal_principal').on('show.bs.modal', function(e) {
        menus();
        paginas();
        if ($('#formulario').attr('action') == "cargar") {
            limpiarModal(e);
            $('#eliminar').hide();
        }
    });

    $('#modal_principal').on('shown.bs.modal', function(e) {
        $("form input[type!='hidden']:first").focus();
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header"
    });

    function limpiarModal() {
        $(document).find('form').trigger('reset');
        $('#pagina').val('').trigger('change');
        $('#menu_padre').val('').trigger('change');
        $("#checkPagina").prop("checked", false);
        $("#checkEnlance").prop("checked", false);
        $('#contenEnlance').hide();
        $('#contenPagina').hide();
        $('#contenTarget').hide();
    }

    //GUARDAR NUEVO REGISTRO O CAMBIOS EDITADOS
    $("#formulario").submit(function(e) {
        e.preventDefault();
        var data = $(this).serializeArray();
        $.ajax({
            url: 'inc/menu-pagina-data?q=' + $(this).attr("action"),
            dataType: 'html',
            type: 'post',
            contentType: 'application/x-www-form-urlencoded',
            data: data,
            beforeSend: function() {
                NProgress.start();
            },
            success: function(datos, textStatus, jQxhr) {
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
            error: function(jqXhr, textStatus, errorThrown) {
                NProgress.done();
                alertDismissJS($(jqXhr.responseText).text().trim(), "error");
            }
        });
    });


    //ELIMINAR
    $('#eliminar').click(function() {
        var menu = $("#menu").val();
        swal({
            title: "¿Eliminar Menu " + menu + "?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "var(--primary)",
            confirmButtonText: "Eliminar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false
        }, function() {
            $.ajax({
                dataType: 'html',
                async: false,
                type: 'POST',
                url: 'inc/menu-pagina-data',
                cache: false,
                data: { q: 'eliminar', id_menu_pagina: $("#hidden_id_menu").val(), menu: menu },
                beforeSend: function() {
                    NProgress.start();
                },
                success: function(data, status, xhr) {
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
                error: function(jqXhr) {
                    NProgress.done();
                    alertDismissJS($(jqXhr.responseText).text().trim(), "error");
                }
            });
        });
    });

    //Altura de tabla automatica
    $(document).ready(function() {
        $(window).bind('resize', function(e) {
            if (window.RT) clearTimeout(window.RT);
            window.RT = setTimeout(function() {
                $("#tabla").bootstrapTable('refreshOptions', {
                    height: $(window).height() - 90,
                    pageSize: Math.floor(($(window).height() - 90) / 50),
                });
            }, 100);
        });
    });
    </script>
</body>

</html>