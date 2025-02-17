<?php
$pag_padre = basename($_SERVER['PHP_SELF']);
include 'header.php';

if ($auth->hasRole(\Delight\Auth\Role::ADMIN)) {
    $rol = "1";
} else {
    $rol = "2";
}

?>
<link rel="stylesheet" href="dist/js/bootstrap-table/bootstrap-table.css">
<script src="dist/js/bootstrap-table/bootstrap-table.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/tableExport.js"></script>
<script src="dist/js/bootstrap-table/locale/bootstrap-table-es-CL.min.js"></script>
<script src="dist/js/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js"></script>
<link href="dist/js/select2/css/select2.min.css" rel="stylesheet">
<link href="dist/js/select2/css/select2-bootstrap4.min.css" rel="stylesheet">
<script src="dist/js/select2/js/select2.full.min.js"></script>
<script src="dist/js/select2/js/select2-dropdownPosition.js"></script>
<script src="dist/js/select2/js/i18n/es.js"></script>
<script src="dist/js/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js"></script>
<script src="dist/js/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="dist/js/magnific-popup/jquery.magnific-popup-init.js"></script>
<link href="dist/js/magnific-popup/magnific-popup.css" rel="stylesheet">
<link href="dropzone/dropzone.css" rel="stylesheet" type="text/css">
<script src="dropzone/dropzone.js"></script>
<link href="dist/js/summernote/summernote-bs4.min.css" rel="stylesheet">
<script src="dist/js/summernote/summernote-bs4.min.js"></script>
<script src="dist/js/summernote/lang/summernote-es-ES.min.js"></script>
<script src="dist/js/bootstrap-table/extensions/editable/bootstrap-table-editable.js"></script>
<script src="dist/js/bootstrap-table/extensions/editable/bootstrap-editable.js"></script>
<link rel="stylesheet" href="dist/js/bootstrap-table/extensions/editable/css/bootstrap-editable.css">
<style>
    /*.select2-dropdown {
    background-color:#f0f0f0 !important;
}*/
.ck-editor__editable {
    min-height: 400px;
}
.ck-rounded-corners .ck.ck-balloon-panel, .ck.ck-balloon-panel.ck-rounded-corners {
    z-index: 10055 !important;
}
.dropzone {
    min-height: 300px;
    border: 2px solid rgba(0, 0, 0, 0.3);
    background: white;
    padding: 20px 20px;
}
.ck-content .table {
    width: auto;
}
.dz-image img {
    width: 130px !important;
}
.zoom_img {
    height: 120%;
    display: inline-block;
    z-index: 99999999;
    max-width: 350px;
    max-height: 250px;
}
#zoom_modal {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    max-width: 350px;
    max-height: 250px;
    z-index: 99999999;
    border: ridge 2px black;
    background-color: #fff;
    text-align:center;
}
.zoom_image{
    display: inline-block;
    width: auto;
height: 24px;
    z-index: 99999999;
    max-width: 350px;
    max-height: 250px;
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
        <?php include 'topbar.php';include 'leftbar.php'?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <?php //include 'titulo.php'; ?>
                <div class="row">
                    <div class="col-12">
                        <div id="mensaje"></div>
                        <div id="toolbar">
                            <div class='form-inline' role='form'>
                                <div class='form-group'>
                                    <button type='button' class='btn btn-primary' id='agregar' data-toggle='modal' data-target='#modal_principal'>Agregar Noticia</button>
                                </div>
                            </div>
                        </div>
                        <table id="tabla" data-url="inc/noticias-data?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="true" data-side-pagination="server" data-classes="table table-hover table-condensed" data-page-list="[100, 150, 200, 300, 400, All]" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
                        <div id='zoom_modal'></div>
                        <!-- MODA PRINCIPAL -->
                        <div class="modal fade" id="modal_principal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="true">
                            <div class="modal-dialog  modal-dialog-centered" role="document">
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
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="form-group col-md-9 col-sm-12">
                                                            <label for="titulo">Titulo</label>
                                                            <input class="form-control input-sm" type="text" name="titulo" id="titulo" autocomplete="off" required maxlength="150">
                                                        </div>
                                                        <div class="form-group col-md-3 col-sm-6">
                                                            <label for="fecha">Fecha</label>
                                                            <div class="input-group">
                                                                <input class="form-control input-sm" type="date" name="fecha" id="fecha" autocomplete="off" required>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="form-group col-md-3 col-sm-6">
                                                            <label for="hora">Hora</label>
                                                            <div class="input-group">
                                                                <input class="form-control input-sm" type="time" name="hora" id="hora" autocomplete="off" required>
                                                            </div>
                                                        </div> -->
                                                        <div class="form-group col-md-12 col-sm-12">
                                                            <label for="copete">Breve Descripcíon</label>
                                                            <input class="form-control input-sm" type="text" name="copete" id="copete" autocomplete="off" required>
                                                        </div>
                                                        <div class="form-group col-md-7 col-sm-12">
                                                            <label for="editor">Descripción</label>
                                                            <textarea class="summernote" id="editor" name="editor"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-5 col-sm-12">
                                                            <div id="contenDrop"></div>
                                                        </div>
                                                        <div class="form-group col-md-2 col-sm-12">
                                                            <div style="margin-top: 0.5rem" class="custom-control custom-checkbox mr-sm-2">
                                                                <input type="checkbox" class="custom-control-input" id="destacado" name="destacado" value="1">
                                                                <label class="custom-control-label" for="destacado">DESTACAR</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <?php if ($permisos->eliminar) { ?><button id="eliminar" type="button" class="btn btn-danger mr-auto" style="display:none">Eliminar</button><?php } ?>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="guardar_producto">Guardar</button>
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
    Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar Proceso";
    Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "No puedes subir más archivos.";
    Dropzone.prototype.defaultOptions.dictResponseError = "Servidor no responde.";
    // var theEditor;


    $(document).ready(function() {
        $('#editor').summernote({
            tabsize: 2,
            height: 250,
            lang: 'es-ES'
        });
    });

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


    function icoFilaEditar(value, row, index) {
        return [
            '<button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm cambiar-estado mr-1" title="Cambiar Estado"><i class="fas fa-sync-alt"></i></button><button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm editar mr-1" title="Editar datos"><i class="fas fa-edit"></i>&nbsp; Editar</button>'
            /*,
                        '<button type="button" onclick="javascript:void(0)" class="btn btn-success btn-sm imprimir" title="Imprimir Etiqueta"><i class="fa fa-barcode"></i></button>'*/
        ].join('');
    }

    function iconosFila(value, row, index) {
        return [
            '<button type="button" onclick="javascript:void(0)" class="btn btn-danger btn-sm remove" title="Eliminar"><i class="fas fa-trash"></i></button>'
        ].join('');
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

    window.filaEditar = {
        'click .cambiar-estado': function(e, value, row, index) {
            let nextStatus = (row.nombre_estado == "Activo" ? "Inactivo" : "Activo");
            let status = (row.nombre_estado == "Activo" ? 0 : 1);
            swal({
                title: `Cambiar Estado`,
                text: `¿Actualizar estado de la noticia a '${nextStatus}'?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "var(--primary)",
                confirmButtonText: "Cambiar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            }, function() {
                $.ajax({
                    url: 'inc/noticias-data?q=cambiar-estado',
                    type: 'post',
                    data: { id: row.id_noticia, estado: status },
                    beforeSend: function() {
                        NProgress.start();
                    },
                    success: function(datos, textStatus, jQxhr) {
                        NProgress.done();
                        var n = datos.toLowerCase().indexOf("error");
                        if (n == -1) {
                            swal.close();
                            alertDismissJS(datos, "ok");
                            $('#tabla').bootstrapTable('refresh', { url: 'inc/noticias-data?q=ver' });
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
            editarFila(e, value, row, index)
        },

    }

    function fotos(value, row, index) {
        bhash = Math.floor((Math.random() * 50000) + 1);
        if (row.foto) {
            var ubicacion = row.foto.split('.').length;
            var extension = row.foto.split('.')[ubicacion-1];
            var foto = row.foto.replace('.' +extension, '') + '.' + extension.toLowerCase() ;
            return [
                "<div class='zoom_image mb-1' style='min-width:75px;'><img class='zoom_img' src='../" + foto + "?" + bhash + "' alt='-' style='object-fit: cover;'/></div>"

            ].join('');
        } else {
            return [
                "<div class='zoom_image mb-1' style='min-width:75px;'><img class='zoom_img' src='./dist/images/sin-foto.jpg' alt='-' style='object-fit: cover;'/></div>"
            ].join('');
        }

    }

    $("#tabla").bootstrapTable({
        mobileResponsive: true,
        sortName: "n.fecha_noticia",
        sortOrder: 'desc',
        trimOnSearch: false,
        height: $(window).height() - 90,
        pageSize: Math.floor(($(window).height() - 90) / 50),
        columns: [
            [
                { field: 'id_noticia', visible: false },
                { field: 'titulo', align: 'left', valign: 'middle', width: 450, title: 'Titulo', sortable: true, visible: true },
                { field: 'copete', align: 'left', valign: 'middle', width: 350, title: 'Descripción', sortable: true, cellStyle: truncarColumna },
                { field: 'foto', align: 'center', valign: 'middle', width: 80, title: 'Foto', sortable: false, formatter: fotos },
                { field: 'fecha', align: 'center', valign: 'middle', width: 170, title: 'Fecha Creación', sortable: false, visible:false },
                { field: 'fecha_noticia', align: 'center', valign: 'middle', width: 110, title: 'Fecha Noticia', visible: false },
                { field: 'usuario', align: 'center', valign: 'middle', width: 100, title: 'User Creacion', visible: false },
                { field: 'usuario_modifica', align: 'center', valign: 'middle', width: 100, title: 'User Modificacion', visible: false },
                { field: 'nombre_estado', align: 'center', valign: 'middle', width: 100, title: 'Estado', sortable: true, formatter: colorEstado },
                { field: 'nombre_destacado', align: 'center', valign: 'middle', width: 50, title: 'Destacado', visible: false },
                { field: 'editar', align: 'center', valign: 'middle', width: 150, title: 'Acciones', sortable: false, events: filaEditar, formatter: icoFilaEditar },
                { field: 'descripcion', visible: false },
                { field: 'estado', visible: false },
                { field: 'destacado', visible: false, switchable: false },
                
            ]
        ]
    });

    function truncarColumna(value, row, index, field) {
        return {
            classes: 'verTooltip',
            css: { "max-width": "400px", "white-space": "pre", "overflow": "hidden", "text-overflow": "ellipsis" }
        };
    }

    function editarFila(e, value, row, index) {
        $('#formulario').attr('action', 'editar');
        $('#modalLabel').html('Editar Noticia');
        $('#eliminar').show();
        $("#dropurl").val('editar');
        $("#contenDrop").load("./dropzone-noticias.html");
        $('#modal_principal').modal('show');
        $("#hidden_id").val(row.id_noticia);
        $('#editor').summernote('code', row.descripcion);
        $("#titulo").val(row.titulo);
        $("#copete").val(row.copete);
        $("#fecha").val(row.fecha_noticia);

        if (row.destacado == 1) {
            $("#destacado").prop("checked", true);
        } else {
            $("#destacado").prop("checked", false);
        }
        
        $.ajax({
            url: 'inc/noticias-data',
            type: 'post',
            data: { q: 'leer_fotos', id: row.id_noticia },
            dataType: 'json',
            success: function(response) {
                $.each(response, function(key, value) {
                    if (value.name == "") {
                        $("#contenDrop").load("./dropzone-noticias.html");
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


    $('#tabla').on('dbl-click-row.bs.table', function(row, $element, field) {
        editarFila(null, null, $element);
    });

    $('#agregar').click(function() {
        $('#modalLabel').html('Agregar Noticia');
        $('#formulario').attr('action', 'cargar');
        $("#dropurl").val('cargar');
        $("#contenDrop").load("./dropzone-noticias.html");
    });


    //ENTER VA AL SIGUIENTE INPUT
    $("#formulario input,select").keydown(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            nextFocus($(this), $('#formulario'));
        }
    });
    $('#modal_principal').on('shown.bs.modal', function(e) {
        if ($('#formulario').attr('action') == "cargar") {
            $('#eliminar').hide();
            limpiarModal();
            limpiarInputs();
        }
        $("form input[type!='hidden']:first").focus();
    });


    function limpiarModal() {
        $(document).find('form').trigger('reset');
    }

    function limpiarInputs() {
        $("#hidden_id").val("");
        $("#nombre").val("");
        $("#titulo").val("");
        $("#destacado").prop("checked", false);
        $('#editor').summernote('code', '')
    }

    //ELIMINAR
    $('#eliminar').click(function() {
        var nombre = $("#titulo").val();
        var id = $("#hidden_id").val();
        swal({
            title: "Eliminar",
            text: "¿Desea eliminar la Noticia: " + nombre + "?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "var(--primary)",
            confirmButtonText: "Eliminar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false
        }, function() {
            $.ajax({
                dataType: 'html',
                type: 'POST',
                url: 'inc/noticias-data',
                cache: false,
                data: { q: 'eliminar', id: id, nombre: nombre },
                beforeSend: function() {
                    NProgress.start();
                },
                success: function(data, status, xhr) {
                    NProgress.done();
                    var n = data.toLowerCase().indexOf("error");
                    if (n == -1) {
                        swal.close();
                        $('#modal_principal').modal('hide');
                        alertDismissJS(data, "ok");
                        $('#tabla').bootstrapTable('refresh', { url: 'inc/noticias-data?q=ver' });
                    } else {
                        alertDismissJS(data, "error");
                    }
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
        $('.select2').on('select2:focus, select2:select', function(e) {
            if ($(this).val() === "" || $(this).val() === null) {
                //$(this).select2('open');
            } else {
                nextFocus($(this), $('#formulario'), 1);
            }
        });
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

    $('#tabla').on('load-success.bs.table', function(e) {
        var currentMousePos = { x: -1, y: -1 };
        $(document).mousemove(function(event) {
            currentMousePos.x = event.pageX;
            currentMousePos.y = event.pageY;
            if ($('#zoom_modal').css('display') != 'none') {
                $('#zoom_modal').css({
                    top: currentMousePos.y - 100,
                    left: currentMousePos.x - 600,
                });
            }
        });
        $('.zoom_image').on('mouseover', function() {

            var image = $(this).find('img');
            //  image = image[0].currentSrc;
            $('#zoom_modal').html(image.clone());
            $('#zoom_modal').css({
                top: currentMousePos.y - 170,
                left: currentMousePos.x - 600
            });
            $('#zoom_modal').show();

        });
        $('.zoom_image').on('mouseleave', function() {
            $('#zoom_modal').hide();
        });

        $("#tabla").bootstrapTable('resetView');
        $("#tabla").bootstrapTable('resetWidth');
    });
    </script>
</body>

</html>