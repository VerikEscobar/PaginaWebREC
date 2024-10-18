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
<link rel="stylesheet" href="dist/css/custom.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<script src="dist/js/bootstrap-table/bootstrap-table.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<script src="dist/js/bootstrap-table/extensions/export/tableExport.js"></script>
<script src="dist/js/bootstrap-table/locale/bootstrap-table-es-CL.min.js"></script>
<script src="dist/js/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1NW86t_rj3bXEDBYplwbqE4ufPJohf34" type="text/javascript"></script>
</head>
<style type="text/css">
.file-upload {
    position: absolute;
    display: none;
    width: 20%;
    z-index: 99999;
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
    text-align: center;
}

.zoom_image {
    display: inline-block;
    width: auto;
    height: 24px;
    z-index: 99999999;
    max-width: 350px;
    max-height: 250px;
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
                <div class="row">
                    <div class="col-12">
                        <div id="mensaje"></div>
                        <div id="toolbar">
                            <div class="form-inline" role="form">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary mr-4" id="agregar" data-toggle="modal" data-target="#modal_principal">Agregar Responsable</button>
                                </div>
                                <!--                                 <div class="form-group row ml-2">
                                    <label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">FILTRAR: </label>
                                    <div class="col-sm-10">
                                        <select id="filtro_departamento" name="filtro_departamento" class="select2" required>
                                        </select>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <table id="tabla" data-url="inc/sedes-responsables-data?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="true" data-side-pagination="server" data-classes="table table-hover table-condensed" data-page-list="[100, 150, 200, 300, 400, All]" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
                        <div id='zoom_modal'></div>
                        <!-- MODA PRINCIPAL -->
                        <div class="modal fade" id="modal_principal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm" role="document">
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
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="datos-tab" data-toggle="tab" href="#datos" role="tab" aria-controls="datos" aria-selected="true">DATOS BÁSICOS</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">FOTO</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content " id="myTabContent">
                                                <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="datos-tab">
                                                    <div class="row mt-3">
                                                        <div class="form-group col-md-12 col-sm-12">
                                                            <div class="row">
                                                                <div class="form-group col-md-12 col-sm-12">
                                                                    <label for="nombre">Nombre y Apellido</label>
                                                                    <input class="form-control input-sm upper" type="text" name="nombre" id="nombre" autocomplete="off" required>
                                                                </div>
                                                                <div class="form-group col-md-12 col-sm-12">
                                                                    <label for="cargo">Cargo</label>
                                                                    <input class="form-control input-sm upper" type="text" name="cargo" id="cargo" autocomplete="off" required>
                                                                </div>
                                                                <div class="form-group col-md-12 col-sm-12">
                                                                    <label for="email">E-mail</label>
                                                                    <input class="form-control input-sm" type="email" name="email" id="email" autocomplete="off">
                                                                </div>
                                                                <!--<div class="form-group col-md-12 col-sm-12">
                                                                    <label for="sede">Sedes</label>
                                                                    <select id="sede" name="sede" class="select2"></select>
                                                                </div>-->
                                                                <!-- <div class="form-group col-md-12 col-sm-12">
                                                                    <label for="direccion">Dirección</label>
                                                                    <input class="form-control input-sm upper" type="text" name="direccion" id="direccion" autocomplete="off" required>
                                                                </div> -->
                                                                <!-- <div class="col-md-12 form-group">
                                                                    <label for="telefono">Teléfono / Celular</label>
                                                                    <input class="form-control input-sm" type="text" name="telefono" id="telefono" autocomplete="off" required>
                                                                </div> -->
                                                                <div class="col-md-12 form-group">
                                                                    <button id="eliminarfoto" type="button" class="btn btn-danger btn-propio btn-sm" style="display:none"><i class="bi bi-x-circle-fill"></i></button>
                                                                    <div id="contenFotoPerfil"></div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                    <div class="row mt-3">
                                                        <div class="col-md-12 form-group mb-3 text-center">
                                                            <input class="file-upload" type="file" id="subir_imagen_perfil" accept="image/*" />
                                                            <button type="button" class="btn btn-primary text-2 font-weight-semibold text-uppercase mt-3 upload-button">Subir Foto <i class="fas fa-camera ml-1"></i></button>
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <div class="col-md-12 text-center">
                                                                <div id="image_demo"></div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div id="mensajeImg"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="eliminar" type="button" class="btn btn-danger mr-auto" style="display:none">Eliminar</button>
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
    $(".upload-button").on('click', function() {
        $(".file-upload").click();
    });
    function formatResult(node) {
        //console.log(node);
        $result = node.text;
        if (node.loading != true) {}
        return $result;
    };

   /* function sedes() {
        $sedes = $('#sede').select2({
            dropdownParent: $('#modal_principal'),
            placeholder: 'Buscar Sede',
            allowClear: true,
            language: "es",
            theme: "bootstrap4",
            width: 'style',
            selectOnClose: false,
            dropdownPosition: 'below',
            maximumResultsForSearch: 10,
            ajax: {
                url: 'inc/sedes-responsables-data.php',
                dataType: 'json',
                delay: 50,
                data: function(params) {
                    return { q: 'ver_sede', term: params.term, page: params.page || 1 }
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id_sede,
                                text: obj.oficina,
                                oficina: obj.oficina
                            };
                        }),
                        pagination: { more: (params.page * 5) <= data[0].total_count }
                    };
                },
                cache: true
            },
            templateResult: formatResult
        });
    }
    sedes();*/

    $image_crop = $('#image_demo').croppie({
        enableExif: true,
        showZoomer: false,
        viewport: {
            width: 200,
            height: 200,
            type: 'square' //square
        },
        boundary: {
            width: 300,
            height: 300
        },
        enableOrientation: true
    });

    $('#subir_imagen_perfil').on('change', function() {
        var fileTypes = ['jpg', 'jpeg', 'png'];
        var reader = new FileReader();
        var file = this.files[0];
        var fileExt = file.type.split('/')[1];
        if (reader) {
            if (fileTypes.indexOf(fileExt) !== -1) {
                reader.onload = function(event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function() {
                        //console.log('Llegue hasta aca capo');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                $('#subirimageModal').modal('show');
            } else {
                alert('Archivo no soportado');
            }

        }
    });

    function iconosFila(value, row, index) {
        return [
            '<button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm cambiar-estado mr-1" title="Cambiar Estado"><i class="fas fa-sync-alt"></i></button><button type="button" onclick="javascript:void(0)" class="btn btn-info btn-sm editar" title="Editar datos"><i class="fa fa-pencil"></i>Editar</button>'
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
                    url: 'inc/sedes-responsables-data?q=cambiar-estado',
                    type: 'post',
                    data: { id: row.id_sede_responsable, estado: status },
                    beforeSend: function() {
                        NProgress.start();
                    },
                    success: function(datos, textStatus, jQxhr) {
                        NProgress.done();
                        var n = datos.toLowerCase().indexOf("error");
                        if (n == -1) {
                            swal.close();
                            alertDismissJS(datos, "ok");
                            $('#tabla').bootstrapTable('refresh', { url: 'inc/sedes-responsables-data?q=ver' });
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
            //$('#sede').val('').trigger('change');
            $('#datos-tab').tab('show');
            $('#modalLabel').html('Editar Categoria');
            $('#formulario').attr('action', 'editar');
            $('#eliminar').show();
            
            $('#modal_principal').modal('show');
            $("#mensaje, #mensaje_modal").html("");

            if (row.oficina) {
                $("#sede").select2('trigger', 'select', {
                    data: { id: row.id_sede, text: row.oficina }
                });
            }
            $("#hidden_id").val(row.id_sede_responsable);
            $("#nombre").val(row.nombre);
            $("#cargo").val(row.cargo);
            $("#email").val(row.email);
            if (row.foto) {
                $("#contenFotoPerfil").html("<img width='100' class='rounded' src='../" + row.foto + "' alt='" + row.nombre + "'>");
                $('#eliminarfoto').show();
            }else{
                $("#contenFotoPerfil").html("");
                $('#eliminarfoto').hide();
            }
            $image_crop.croppie('bind', {
                url: 'dist/images/croppie.jpg'
            }).then(function() {
                console.log('Limpio');
            });

            //Eliminar foto
            $('#eliminarfoto').click(function() {
                var nombre = row.nombre;
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
                        url: 'inc/sedes-responsables-data',
                        cache: false,
                        data: { q: 'borrar_fotos', id: $("#hidden_id").val(), nombre: nombre },
                        beforeSend: function() {
                            $("#mensaje_eliminar").html("<img src='dist/images/loading.gif'>");
                        },
                        success: function(data, status, xhr) {
                            var n = data.toLowerCase().indexOf("error");
                            if (n == -1) {
                                swal.close();
                                alertDismissJS(data, "ok");
                                $('#modal_principal').modal('hide');
                                $('#tabla').bootstrapTable('refresh', { url: 'inc/sedes-responsables-data?q=ver' });
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


            //ELIMINAR
            $('#eliminar').click(function() {
                var nombre = row.nombre;
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
                        url: 'inc/sedes-responsables-data',
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
                                $('#tabla').bootstrapTable('refresh', { url: 'inc/sedes-responsables-data?q=ver' });
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

    function fotos(value, row, index) {
        bhash = Math.floor((Math.random() * 50000) + 1);;
        if (row.foto) {
            return [
                "<div class='zoom_image mb-1' style='width:50px;'><img class='zoom_img' src='../" + row.foto + "?" + bhash + "' alt='-' style='object-fit: cover;'/></div>"

            ].join('');
        } else {
            return [
                "<div class='zoom_image mb-1' style='width:50px;'><img class='zoom_img' src='./dist/images/sin-foto.jpg' alt='-' style='object-fit: cover;'/></div>"
            ].join('');
        }
    }
    $("#tabla").bootstrapTable({
        mobileResponsive: true,
        sortName: "sr.id_sede_responsable",
        sortOrder: 'DESC',
        height: $(window).height() - 90,
        pageSize: Math.floor(($(window).height() - 90) / 50),
        columns: [
            [
                { field: 'id_sede_responsable', align: 'center', valign: 'middle', title: 'ID', sortable: true, visible: false },
                { field: 'nro_oficina', align: 'left', valign: 'middle', title: 'Nro. Oficina', sortable: true , visible: false},
                { field: 'nombre', align: 'left', valign: 'middle', title: 'Oficina', sortable: true },
                { field: 'cargo', align: 'left', valign: 'middle', title: 'Departamento', sortable: true },
                { field: 'fotos', align: 'center', valign: 'middle', title: 'Foto', sortable: false, formatter: fotos },
                { field: 'nombre_estado', align: 'center', width: 150, valign: 'middle', title: 'Estado', sortable: true, formatter: colorEstado },
                { field: 'fecha', align: 'left', valign: 'middle', title: 'Fecha Alta', width: 160, sortable: true, visible: false },
                { field: 'editar', align: 'center', valign: 'middle', width: 150, title: 'Editar', sortable: false, events: accionesFila, formatter: iconosFila },
                { field: 'interino', visible: false },
                { field: 'telefono', visible: false },
                { field: 'id_sede', visible: false },
                { field: 'oficina', visible: false },
                { field: 'direccion', visible: false },
                { field: 'foto', visible: false }
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
        $('#datos-tab').tab('show');
        $('#modalLabel').html('Agregar Responsable');
        $('#formulario').attr('action', 'cargar');
    });

    $('#modal_principal').on('show.bs.modal', function(e) {
        $("#mensaje").html("");
        if ($('#formulario').attr('action') == "cargar") {
            $('#eliminar').hide();
            $('#eliminarfoto').hide();
            limpiarModal(e);
        }
    });

    $('#modal_principal').on('shown.bs.modal', function(e) {
        $("form select,input[type!='hidden']:first").focus();
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header"
    });

    function limpiarModal() {
        $(document).find('form').trigger('reset');
        $("#mensaje_modal").html("");
        $('#categoria').val('').trigger('change');
        $('#departamento').val('').trigger('change');
        $('#subir_imagen_perfil').val('');
        $('#sede').val('').trigger('change');
        $('#sede2').val('').trigger('change');

        $image_crop.croppie('bind', {
            url: 'dist/images/croppie.jpg'
        }).then(function() {
            console.log('Limpio');
        });
        $("#contenFotoPerfil").html("");

    }

    //GUARDAR NUEVO REGISTRO O CAMBIOS EDITADOS
    $("#formulario").submit(function(e) {
        e.preventDefault();
        $("#mensaje_modal").html("");
        var data = $(this).serializeArray();
        var foto = "";
        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function(response) {
            if (response) {
                foto = response;
                data.push({ name: 'foto', value: foto });
            }
            $.ajax({
                url: 'inc/sedes-responsables-data?q=' + $("#formulario").attr("action"),
                dataType: 'html',
                type: 'POST',
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
                        $('#tabla').bootstrapTable('refresh', { url: 'inc/sedes-responsables-data?q=ver' });
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
        //console.log(data);

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