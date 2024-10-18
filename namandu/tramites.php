<?php
require __DIR__ . '/inc/auth/autoload.php';
$auth = new \Delight\Auth\Auth($db_auth);
if (!$auth->isLoggedIn()) {
    header("Location: login");
}

$pag_padre = basename($_SERVER['PHP_SELF']);
include 'header.php';
?>
<link rel="stylesheet" href="dist/js/bootstrap-table/bootstrap-table.css">
<link href="dist/js/summernote/summernote-bs4.min.css" rel="stylesheet">
<script src="dist/js/summernote/summernote-bs4.min.js"></script>
<script src="dist/js/summernote/lang/summernote-es-ES.min.js"></script>
<link href="dist/js/select2/css/select2.min.css" rel="stylesheet">
<link href="dist/js/select2/css/select2-bootstrap4.min.css" rel="stylesheet">
<script src="dist/js/select2/js/select2.full.min.js"></script>
<script src="dist/js/select2/js/select2-dropdownPosition.js"></script>
<script src="dist/js/select2/js/i18n/es.js"></script>
<script src="dist/js/bootstrap-table/bootstrap-table.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/tableExport.js"></script>
<script src="dist/js/bootstrap-table/locale/bootstrap-table-es-CL.min.js"></script>
<script src="dist/js/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js"></script>
</head>
<style>
</style>

<body class="<?php include 'menu-class.php';?> fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Cargando Ñamandú...</p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php include 'topbar.php';include 'leftbar.php'?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div id="mensaje"></div>
                        <div id="toolbar">
                            <div class="form-inline" role="form">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="agregar" data-toggle="modal" data-target="#modal_principal">Agregar Tabla Tramites</button>
                                </div>
                            </div>
                        </div>
                        <table id="tabla" data-url="inc/tramites-data?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="false" data-classes="table table-hover table-condensed" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
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
                                        <input type="hidden" name="hidden_id" id="hidden_id">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <label for="titulo">Titulo</label>
                                                    <input class="form-control input-sm" type="text" name="titulo" id="titulo" autocomplete="off" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label for="orden">Orden</label>
                                                    <input onkeypress="return soloNumeros(event)" class="form-control input-sm" type="text" name="orden" id="orden" autocomplete="off" required maxlength="2">
                                                </div>
                                                <div class="form-group col-md-6 col-sm-6">
                                                    <label for="categoria">Categoria</label>
                                                    <select id="categoria" name="categoria" class="select2" required>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12">
                                                    <label for="editor">Descripción</label>
                                                    <textarea class="summernote" id="editor" name="editor"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="container">
                                                <div class="row">
                                                    <span id="mensaje_modal"></span>
                                                    <div class="col">
                                                        <button id="eliminar" type="button" class="btn btn-danger" style="display:none">Eliminar</button>
                                                    </div>
                                                    <div class="col text-right">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php';?>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#editor').summernote({
            tabsize: 2,
            height: 250,
            lang: 'es-ES',
        });
    });

    $("#categoria").select2({
        theme: "bootstrap4",
        width: 'auto',
        minimumResultsForSearch: 10,
        selectOnClose: true,
        dropdownPosition: 'below',
    });

    $.ajax({
        dataType: 'json',
        async: true,
        cache: false,
        url: 'inc/tramites-data',
        type: 'POST',
        data: { q: 'ver_categorias' },
        beforeSend: function() {
            NProgress.start();
        },
        success: function(json) {
            $('#categoria').html("");
            $.each(json, function(key, value) {
                $('#categoria').append('<option value="' + value.id_tramite_categoria + '">' + value.categoria + '</option>');
            });
            NProgress.done();
        },
        error: function(jqXhr) {
            NProgress.done();
            alertDismissJS($(jqXhr.responseText).text().trim(), "error");
        }
    });

    function iconosFila(value, row, index) {
        return [
            '<button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm cambiar-estado mr-1" title="Cambiar Estado"><i class="fas fa-sync-alt"></i></button><button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm editar" title="Editar datos"><i class="fa fa-pencil"></i>&nbsp; Editar</button>'
        ].join('');
    }

    window.accionesFila = {
        'click .cambiar-estado': function(e, value, row, index) {
            let nextStatus = (row.nombre_estado == "Activo" ? "Inactivo" : "Activo");
            let status = (row.nombre_estado == "Activo" ? 0 : 1);
            swal({
                title: `Cambiar Estado`,
                text: `¿Actualizar estado a '${nextStatus}'?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "var(--primary)",
                confirmButtonText: "Cambiar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            }, function() {
                $.ajax({
                    url: 'inc/tramites-data?q=cambiar-estado',
                    type: 'post',
                    data: { id: row.id_tramite, estado: status },
                    beforeSend: function() {
                        NProgress.start();
                    },
                    success: function(datos, textStatus, jQxhr) {
                        NProgress.done();
                        var n = datos.toLowerCase().indexOf("error");
                        if (n == -1) {
                            swal.close();
                            alertDismissJS(datos, "ok");
                            $('#tabla').bootstrapTable('refresh', { url: 'inc/tramites-data?q=ver' });
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
            $('#modalLabel').html('Editar Tabla Tramite');
            $('#formulario').attr('action', 'editar');
            $('#eliminar').show();
            $('#modal_principal').modal('show');
            $("#mensaje, #mensaje_modal").html("");

            $("#hidden_id").val(row.id_tramite);
            $("#titulo").val(row.titulo);
            $('#editor').summernote('code', row.descripcion);
            $("#orden").val(row.orden);
            $("#categoria").select2('trigger', 'select', {
                data: { id: row.id_tramite_categoria, text: row.categoria }
            });

            //ELIMINAR
            $('#eliminar').click(function() {
                var titulo = row.titulo;
                swal({
                    title: "¿Eliminar: " + titulo + "?",
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
                        url: 'inc/tramites-data',
                        cache: false,
                        data: { q: 'eliminar', id: $("#hidden_id").val(), titulo: titulo },
                        beforeSend: function() {
                            $("#mensaje_eliminar").html("<img src='dist/images/loading.gif'>");
                        },
                        success: function(data, status, xhr) {
                            var n = data.toLowerCase().indexOf("error");
                            if (n == -1) {
                                swal.close();
                                alertDismissJS(data, "ok");
                                $('#modal_principal').modal('hide');
                                $('#tabla').bootstrapTable('refresh', { url: 'inc/tramites-data?q=ver' });
                            } else {
                                alertDismissJS(data, "error");
                            }
                        },
                        error: function(jqXhr) {
                            alertDismissJS($(jqXhr.responseText).text().trim(), "error");
                        }
                    });
                });
            });

        }
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
        sortName: "t.id_tramite",
        sortOrder: 'asc',
        height: $(window).height() - 90,
        pageSize: Math.floor(($(window).height() - 90) / 50),
        columns: [
            [
                { field: 'id_tramite', align: 'center', valign: 'middle', title: 'ID', sortable: true, visible: false },
                { field: 'titulo', align: 'left', valign: 'middle', title: 'Titulo', sortable: true },
                { field: 'categoria', align: 'left', valign: 'middle', title: 'Categoria', sortable: true, visible: true },
                { field: 'orden', align: 'left', valign: 'middle', title: 'Orden', sortable: true },
                { field: 'fecha', align: 'left', width: 200, valign: 'middle', title: 'Creacion', sortable: true },
                { field: 'nombre_estado', align: 'center', width: 150, valign: 'middle', title: 'Estado', sortable: true, formatter: colorEstado },
                { field: 'editar', align: 'center', valign: 'middle', width: 150, title: 'Editar', sortable: false, events: accionesFila, formatter: iconosFila },
                { field: 'id_tramite_categoria', visible: false },
                { field: 'descripcion', visible: false }
            ]
        ]
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

    $('#agregar').click(function() {
        $('#modalLabel').html('Agregar Tabla Tramite');
        $('#formulario').attr('action', 'cargar');
    });

    $('#modal_principal').on('show.bs.modal', function(e) {
        $("#mensaje").html("");
        if ($('#formulario').attr('action') == "cargar") {
            $('#eliminar').hide();
            limpiarModal(e);
            limpiarInputs();
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
        $("#mensaje_modal").html("");
    }

    function limpiarInputs() {
        $("#titulo").val("");
        $("#orden").val("");
        $('#editor').summernote('code', '');
        $('#categoria').val(null).trigger('change');
    }

    //GUARDAR NUEVO REGISTRO O CAMBIOS EDITADOS
    $("#formulario").submit(function(e) {
        e.preventDefault();
        $("#mensaje_modal").html("");
        var data = $(this).serializeArray();
        $.ajax({
            url: 'inc/tramites-data?q=' + $(this).attr("action"),
            dataType: 'html',
            type: 'post',
            contentType: 'application/x-www-form-urlencoded',
            data: data,
            beforeSend: function() {
                $("#mensaje_modal").html("<img src='dist/images/progress_bar.gif'>");
            },
            success: function(datos, textStatus, jQxhr) {
                $('#mensaje_modal').html("");
                var n = datos.toLowerCase().indexOf("error");
                if (n == -1) {
                    $('#modal_principal').modal('hide');
                    alertDismissJS(datos, "ok");
                    $('#tabla').bootstrapTable('refresh', { url: 'inc/tramites-data?q=ver' });
                } else {
                    alertDismissJS(datos, "error");
                }
            },
            error: function(jqXhr, textStatus, errorThrown) {
                $("#mensaje_modal").html("");
                alertDismissJS($(jqXhr.responseText).text().trim(), "error");
            }
        });
    });
    </script>
</body>

</html>