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
<script src="dist/js/bootstrap-table/bootstrap-table.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/tableExport.js"></script>
<script src="dist/js/bootstrap-table/locale/bootstrap-table-es-CL.min.js"></script>
<script src="dist/js/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js"></script>
<link href="dropzone/dropzone.css" rel="stylesheet" type="text/css">
<script src="dropzone/dropzone.js"></script>
<!--<link rel="stylesheet" href="dist/js/bootstrap-table/extensions/group-by-v2/bootstrap-table-group-by.css">
<script src="dist/js/bootstrap-table/extensions/group-by-v2/bootstrap-table-group-by.js"></script>-->
<script src="dist/js/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="dist/js/magnific-popup/jquery.magnific-popup-init.js"></script>
<link href="dist/js/magnific-popup/magnific-popup.css" rel="stylesheet">
</head>
<style type="text/css">
.dz-image img {
    width: 130px !important;
}
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
                <?php //include 'titulo.php'; ?>
                <div class="row">
                    <div class="col-12">
                        <div id="mensaje"></div>
                        <div id="toolbar">
                            <div class="form-inline" role="form">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="agregar" data-toggle="modal" data-target="#modal_principal">Agregar Banner</button>
                                </div>
                            </div>
                        </div>
                        <table id="tabla" data-url="inc/banner-principal-data?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="false" data-classes="table table-hover table-condensed" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
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
                                        <input type="hidden" id="dropurl" name="dropurl">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-8 form-group">
                                                    <label for="titulo">Titulo</label>
                                                    <input class="form-control input-sm" type="text" name="titulo" id="titulo" autocomplete="off">
                                                </div>
                                                <div id="menuOrdenConten" class="col-md-4 form-group">
                                                    <label for="orden">Orden del Banner</label>
                                                    <select id="orden" name="orden" class="form-control">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="descripcion">Descripción</label>
                                                    <textarea class="form-control input-sm" name="descripcion" id="descripcion" autocomplete="off" rows="3"></textarea>
                                                </div>
                                                <div id="cateConten" class="col-md-8 form-group">
                                                    <label for="url">Url del Banner</label>
                                                    <input class="form-control input-sm" type="text" name="url" id="url" autocomplete="off" required>
                                                </div>
                                                <div id="cateConten" class="col-md-4 form-group">
                                                    <label for="url">Boton Nombre</label>
                                                    <input class="form-control input-sm" type="text" name="url_nombre" id="url_nombre" autocomplete="off">
                                                </div>
                                                <div id="contenDrop" class="col-md-12">
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
    Dropzone.autoDiscover = false;
    Dropzone.prototype.defaultOptions.dictFileTooBig = "Archivo muy pesado ({{filesize}}MB). Tamaño Maximo: {{maxFilesize}}MB.";
    Dropzone.prototype.defaultOptions.dictRemoveFile = "Remover Archivo";
    Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "No puedes subir más archivos.";
    Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar Proceso";
    Dropzone.prototype.defaultOptions.dictResponseError = "Servidor no responde.";
    $("#formulario").submit(function(e) {
        e.preventDefault();
        if (myDropzone.getQueuedFiles().length === 0) {
            var blob = new Blob();
            blob.upload = { 'chunked': myDropzone.defaultOptions.chunking };
            myDropzone.uploadFile(blob);
        } else {
            myDropzone.processQueue();
        }
    });

    function iconosFila(value, row, index) {
        return [
            '<button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm cambiar-estado mr-1" title="Cambiar Estado"><i class="fas fa-sync-alt"></i></button><button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm editar" title="Editar datos"><i class="fa fa-pencil"></i>Editar</button>'
        ].join('');
    }

    function fotos(value, row, index) {
        if (row.imagen) {
            return [
                "<a class='image-popup-no-margins' href='../" + row.imagen + "'> <img width='32px' src='../" + row.imagen + "' alt='-'></a>"
            ].join('');
        } else {
            return [
                "<a class='image-popup-no-margins' href='../img/sin-foto.jpg'> <img width='32px' src='../img/sin-foto.jpg' alt='-'></a>"
            ].join('');
        }
    }
    window.accionesFila = {
        'click .cambiar-estado': function(e, value, row, index) {
            let nextStatus = (row.nombre_estado == "Activo" ? "Inactivo" : "Activo");
            let status = (row.nombre_estado == "Activo" ? 0 : 1);
            swal({
                title: `Cambiar Estado`,
                text: `¿Actualizar estado del Banner a '${nextStatus}'?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "var(--primary)",
                confirmButtonText: "Cambiar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            }, function() {
                $.ajax({
                    url: 'inc/banner-principal-data?q=cambiar-estado',
                    type: 'post',
                    data: { id: row.id_banner, estado: status },
                    beforeSend: function() {
                        NProgress.start();
                    },
                    success: function(datos, textStatus, jQxhr) {
                        NProgress.done();
                        var n = datos.toLowerCase().indexOf("error");
                        if (n == -1) {
                            swal.close();
                            alertDismissJS(datos, "ok");
                            $('#tabla').bootstrapTable('refresh', { url: 'inc/banner-principal-data?q=ver' });
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
            $('#modalLabel').html('Editar Banner');
            $('#formulario').attr('action', 'editar');
            $("#dropurl").val('editar');
            $("#contenDrop").load("./dropzone-banner.html");
            $('#eliminar').show();
            $('#modal_principal').modal('show');
            $('#estado').prop("disabled", false);
            $("#mensaje, #mensaje_modal").html("");
            //$('#contenDrop').show();
            $("#estado").select2('trigger', 'select', {
                data: { id: row.estado, text: row.nombre_estado }
            });
            $("#hidden_id").val(row.id_banner);
            $("#url").val(row.url);
            $("#url_nombre").val(row.url_nombre);
            $("#titulo").val(row.titulo);
            $("#descripcion").val(row.descripcion);
            $("#orden").select2('trigger', 'select', {
                data: { id: row.orden, text: row.orden }
            });
            $.ajax({
                url: 'inc/banner-principal-data',
                type: 'post',
                data: { q: 'leer_fotos', id_banner: row.id_banner },
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(key, value) {
                        if (value.name == "") {
                            $("#contenDrop").load("./dropzone-banner.html");
                        } else {
                            var mockFile = { name: value.name, size: value.size };
                            myDropzone.emit('addedfile', mockFile);
                            myDropzone.emit('thumbnail', mockFile, value.path);
                            myDropzone.emit('complete', mockFile);
                            myDropzone.files.push(mockFile);
                        }
                    });
                }
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
        height: $(window).height() - 90,
        pageSize: Math.floor(($(window).height() - 90) / 50),
        columns: [
            [
                { field: 'id_banner', align: 'left', valign: 'middle', title: 'ID', sortable: true, visible: false},
                { field: 'estado', visible: false },
                { field: 'imagen', visible: false },
                { field: 'url_nombre', visible: false },
                { field: 'url', align: 'left', valign: 'middle', title: 'Url Banner', sortable: true, visible: false },

                { field: 'orden', align: 'center', valign: 'middle', title: 'Orden', visible: true },
                { field: 'titulo', align: 'left', valign: 'middle', title: 'Titulo', visible: true },
                { field: 'descripcion', align: 'left', valign: 'middle', title: 'Orden', visible: true },

                { field: 'fotos', align: 'center', valign: 'middle', title: 'Foto', sortable: false, formatter: fotos },
                { field: 'fecha', align: 'left', valign: 'middle', width: 150, title: 'Fecha Creación', sortable: true, visible: true },
                { field: 'nombre_estado', align: 'center', width: 150, valign: 'middle', title: 'Estado', sortable: true, formatter: colorEstado },
                { field: 'editar', align: 'center', width: 150, valign: 'middle', title: ' Acciones', sortable: false, events: accionesFila, formatter: iconosFila }
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
    $("#estado, #orden").select2({
        theme: "bootstrap4",
        width: 'auto',
        minimumResultsForSearch: 15,
        selectOnClose: true,
        dropdownPosition: 'below',
    });
    $('#agregar').click(function() {
        $('#modalLabel').html('Agregar Banner');
        $('#formulario').attr('action', 'cargar');
        $('#estado').prop("disabled", true);
        $("#contenDrop").load("./dropzone-banner.html");
    });
    $('#modal_principal').on('show.bs.modal', function(e) {
        $("#mensaje").html("");
        if ($('#formulario').attr('action') == "cargar") {
            $('#eliminar').hide();
            limpiarModal(e);
            $("#dropurl").val('cargar');
        }
    });
    $('#modal_principal').on('shown.bs.modal', function(e) {
        $("form input[type!='hidden']:first").focus();
    });
    $('#modal_principal').on('hidden.bs.modal', function(e) {
        $("#contenDrop").load("./dropzone-banner.html");
        $('#orden').val(0).trigger('change');
        $('#estado').val(1).trigger('change');
    });
    $(".modal-dialog").draggable({
        handle: ".modal-header"
    });

    function limpiarModal() {
        $(document).find('form').trigger('reset');
        $("#mensaje_modal").html("");
        $('#orden').val(null).trigger('change');
        $('#estado').val(1).trigger('change');
    }
    //ELIMINAR
    $('#eliminar').click(function() {
        var nombre = "Banner";
        swal({
            title: "¿Eliminar " + nombre + "?",
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
                url: 'inc/banner-principal-data',
                cache: false,
                data: { q: 'eliminar', id: $("#hidden_id").val() },
                beforeSend: function() {
                    $("#mensaje_eliminar").html("<img src='dist/images/loading.gif'>");
                },
                success: function(data, status, xhr) {
                    var n = data.toLowerCase().indexOf("error");
                    if (n == -1) {
                        swal.close();
                        alertDismissJS(data, "ok");
                        $('#modal_principal').modal('hide');
                        $('#tabla').bootstrapTable('refresh', { url: 'inc/banner-principal-data?q=ver' });
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
    </script>
</body>

</html>