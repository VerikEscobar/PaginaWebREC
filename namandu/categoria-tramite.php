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
                                    <button type="button" class="btn btn-primary" id="agregar" data-toggle="modal" data-target="#modal_principal">Agregar Categoria</button>
                                </div>
                            </div>
                        </div>
                        <table id="tabla" data-url="inc/categoria-tramite-data?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="false" data-classes="table table-hover table-condensed" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
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
                                                    <label for="categoria">Categoria</label>
                                                    <input class="form-control input-sm" type="text" name="categoria" id="categoria" autocomplete="off" required>
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
                    url: 'inc/categoria-tramite-data?q=cambiar-estado',
                    type: 'post',
                    data: { id: row.id_tramite_categoria, estado: status },
                    beforeSend: function() {
                        NProgress.start();
                    },
                    success: function(datos, textStatus, jQxhr) {
                        NProgress.done();
                        var n = datos.toLowerCase().indexOf("error");
                        if (n == -1) {
                            swal.close();
                            alertDismissJS(datos, "ok");
                            $('#tabla').bootstrapTable('refresh', { url: 'inc/categoria-tramite-data?q=ver' });
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
            $('#modalLabel').html('Editar Descripcion Footer');
            $('#formulario').attr('action', 'editar');
            $('#eliminar').show();
            $('#modal_principal').modal('show');
            $("#mensaje, #mensaje_modal").html("");

            $("#hidden_id").val(row.id_tramite_categoria);
            $("#categoria").val(row.categoria);

            //ELIMINAR
            $('#eliminar').click(function() {
                var nombre = row.categoria;
                swal({
                    title: "¿Eliminar: " + nombre + "?",
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
                        url: 'inc/categoria-tramite-data',
                        cache: false,
                        data: { q: 'eliminar', id: $("#hidden_id").val(), nombre: nombre },
                        beforeSend: function() {
                            $("#mensaje_eliminar").html("<img src='dist/images/loading.gif'>");
                        },
                        success: function(data, status, xhr) {
                            var n = data.toLowerCase().indexOf("error");
                            if (n == -1) {
                                swal.close();
                                alertDismissJS(data, "ok");
                                $('#modal_principal').modal('hide');
                                $('#tabla').bootstrapTable('refresh', { url: 'inc/categoria-tramite-data?q=ver' });
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
        sortName: "id_tramite_categoria",
        sortOrder: 'asc',
        height: $(window).height() - 90,
        pageSize: Math.floor(($(window).height() - 90) / 50),
        columns: [
            [
                { field: 'id_tramite_categoria', align: 'center', valign: 'middle', title: 'ID', sortable: true, visible: false },
                { field: 'categoria', align: 'left', valign: 'middle', title: 'Categoria', sortable: true },
                { field: 'fecha', align: 'left', width: 200, valign: 'middle', title: 'Creacion', sortable: true },
                { field: 'nombre_estado', align: 'center', width: 150, valign: 'middle', title: 'Estado', sortable: true, formatter: colorEstado },
                { field: 'editar', align: 'center', valign: 'middle', width: 150, title: 'Editar', sortable: false, events: accionesFila, formatter: iconosFila },
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
        $('#modalLabel').html('Agregar Categoria');
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
        $("#categoria").val("");
    }

    //GUARDAR NUEVO REGISTRO O CAMBIOS EDITADOS
    $("#formulario").submit(function(e) {
        e.preventDefault();
        $("#mensaje_modal").html("");
        var data = $(this).serializeArray();
        $.ajax({
            url: 'inc/categoria-tramite-data?q=' + $(this).attr("action"),
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
                    $('#tabla').bootstrapTable('refresh', { url: 'inc/categoria-tramite-data?q=ver' });
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