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
<link rel="stylesheet" href="../mapaModal/Leaflet Mapa/css/leaflet.css">
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
                <div class="row">
                    <div class="col-12">
                        <div id="mensaje"></div>
                        <div id="toolbar">
                            <div class="form-inline" role="form">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary mr-4" id="agregar" data-toggle="modal" data-target="#modal_principal">Agregar Sede</button>
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
                        <table id="tabla" data-url="inc/sedes-data?q=ver" data-toolbar="#toolbar" data-show-export="true" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search-align="right" data-buttons-align="right" data-toolbar-align="left" data-pagination="true" data-side-pagination="server" data-classes="table table-hover table-condensed" data-page-list="[100, 150, 200, 300, 400, All]" data-striped="true" data-icons="icons" data-show-fullscreen="true"></table>
                        <!-- MODA PRINCIPAL -->
                        <div class="modal fade" id="modal_principal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
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
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-sm-12">
                                                            <label for="categoria">Categoria</label>
                                                            <select id="categoria" name="categoria" class="select2" required></select>
                                                        </div>
                                                        <div class="form-group col-md-12 col-sm-12">
                                                            <label for="pais">País</label>
                                                            <select id="pais" name="pais" class="select2" required></select>
                                                        </div>
                                                        <div class="form-group col-md-12 col-sm-12">
                                                            <label for="departamento">Departamento</label>
                                                            <select id="departamento" name="departamento" class="select2" required></select>
                                                        </div>
                                                        <div id="numerooficina" class="col-md-12 form-group">
                                                            <label for="nro_oficina">Nro. Oficina</label>
                                                            <input onkeypress="return soloNumeros(event)" class="form-control input-sm" type="text" name="nro_oficina" id="nro_oficina" autocomplete="off" maxlength="5">
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <label for="oficina">Oficina</label>
                                                            <input class="form-control input-sm upper" type="text" name="oficina" id="oficina" autocomplete="off" required>
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <label for="responsable">Responsable</label>
                                                            <select id="responsable" name="responsable" class="select2"></select>
                                                        </div>
                                                       
                                                        <div class="col-md-12 form-group">
                                                            <label for="direccion">Dirección</label>
                                                            <input class="form-control input-sm" type="text" name="direccion" id="direccion" autocomplete="off">
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <label for="telefono">Teléfono</label>
                                                            <input class="form-control input-sm" type="text" name="telefono" id="telefono" autocomplete="off">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="lon_carga">Longitud</label>
                                                            <input class="form-control" id="lon_carga" name="lon_proceso" class="input_maps">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="lat_carga">Latitud</label>
                                                            <input class="form-control" name="lat_proceso" id="lat_carga" class="input_maps">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <button type="button" class="btn btn-success" id="buscar_coordenadas"><i class="fas fa-map-marker-alt mr-1"></i>Buscar Ubicación</button>
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <div class="custom-control custom-checkbox mr-sm-2">
                                                                <input type="checkbox" class="custom-control-input" id="interino" name="interino" value="1" onChange="showContent(this);">
                                                                <label class="custom-control-label" for="interino">Interino</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <label for="obs_interino">Observacion</label>
                                                            <textarea class="form-control input-sm" name="obs_interino" id="obs_interino"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <!-- <div id="mapa_carga" style="width:100%;height:500px;margin:auto"></div>-->
                                                    <div id="myMap" style="width:100%;height:500px;margin:auto"></div>
                                                    <!-- <input type="hidden" id="lon_carga" name="lon_proceso" class="input_maps">
                                                    <input type="hidden" name="lat_proceso" id="lat_carga" class="input_maps"> -->
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
    <script src="../mapaModal/Leaflet Mapa/js/leaflet.js"></script>
    <script src="../mapaModal/script.js"></script>
    <script type="text/javascript">
        /*
        function showContent(obj) { 
        var elemento = document.getElementById('obs_interino');  
        if (obj.checked)
        elemento.readOnly = false;  
            else
        elemento.readOnly = true;    
        elemento.value = "";     
        }
        */
    $("#categoria").select2({
        theme: "bootstrap4",
        width: 'auto',
        minimumResultsForSearch: 10,
        selectOnClose: false,
        dropdownPosition: 'below',
    });

    $.ajax({
        dataType: 'json',
        async: true,
        cache: false,
        url: 'inc/sedes-data',
        type: 'POST',
        data: { q: 'ver_categorias' },
        beforeSend: function() {
            NProgress.start();
        },
        success: function(json) {
            $('#categoria').html("<option value=''>[SELECCIONAR]</option>");
            $.each(json, function(key, value) {
                $('#categoria').append('<option value="' + value.id_sede_categoria + '">' + value.categoria + '</option>');
            });
            NProgress.done();
        },
        error: function(jqXhr) {
            NProgress.done();
            alertDismissJS($(jqXhr.responseText).text().trim(), "error");
        }
    });

    function formatResult(node) {
        //console.log(node);
        $result = node.text;
        if (node.loading != true) {}
        return $result;
    };

    var $id_pais = 0;
    function paises() {
        $paises = $('#pais').select2({
            dropdownParent: $('#modal_principal'),
            placeholder: 'Buscar País',
            allowClear: true,
            language: "es",
            theme: "bootstrap4",
            width: 'style',
            selectOnClose: false,
            dropdownPosition: 'below',
            maximumResultsForSearch: 10,
            ajax: {
                url: 'inc/sedes-data.php',
                dataType: 'json',
                delay: 50,
                data: function(params) {
                    return { q: 'ver_paises', term: params.term, page: params.page || 1 }
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.pais,
                                text: obj.pais,
                                pais: obj.id_pais,
                                id_pais: obj.id_pais
                            };
                        }),
                        pagination: { more: (params.page * 5) <= data[0].total_count }
                    };
                },
                cache: true
            },
            templateResult: formatResult
        }).on('select2:select', function (e) {
            $id_pais = e.params.data.id_pais;
            console.log($('#pais').val());
            $('#departamento').val(null).trigger('change');
        });
    }
    paises();
    
    function departamentos() {
        $departamentos = $('#departamento').select2({
            dropdownParent: $('#modal_principal'),
            placeholder: 'Buscar Departamento',
            allowClear: true,
            language: "es",
            theme: "bootstrap4",
            width: 'style',
            selectOnClose: false,
            dropdownPosition: 'below',
            maximumResultsForSearch: 10,
            ajax: {
                url: 'inc/sedes-data.php',
                dataType: 'json',
                delay: 50,
                data: function(params) {
                    return { q: 'ver_departamentos', term: params.term, page: params.page || 1, id_pais: $id_pais }
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.departamento,
                                text: obj.departamento,
                                departamento: obj.departamento
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
    departamentos();

    function responsable() {
        $responsable = $('#responsable').select2({
            dropdownParent: $('#modal_principal'),
            placeholder: 'Buscar Responsable',
            allowClear: true,
            language: "es",
            theme: "bootstrap4",
            width: 'style',
            selectOnClose: false,
            dropdownPosition: 'below',
            maximumResultsForSearch: 10,
            ajax: {
                url: 'inc/sedes-data.php',
                dataType: 'json',
                delay: 50,
                data: function(params) {
                    return { q: 'ver_responsables', term: params.term, page: params.page || 1 }
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id_sede_responsable,
                                text: obj.nombre,
                                nombre: obj.nombre
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
    responsable();

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
                    url: 'inc/sedes-data?q=cambiar-estado',
                    type: 'post',
                    data: { id: row.id_sede, estado: status },
                    beforeSend: function() {
                        NProgress.start();
                    },
                    success: function(datos, textStatus, jQxhr) {
                        NProgress.done();
                        var n = datos.toLowerCase().indexOf("error");
                        if (n == -1) {
                            swal.close();
                            alertDismissJS(datos, "ok");
                            $('#tabla').bootstrapTable('refresh', { url: 'inc/sedes-data?q=ver' });
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
            resetForm('#formulario');
            $('#categoria').val('').trigger('change');
            $('#pais').val('').trigger('change');
            $('#departamento').val('').trigger('change');
            $('#responsable').val('').trigger('change');
            $('#modalLabel').html('Editar Categoria');
            $('#formulario').attr('action', 'editar');
            $('#eliminar').show();
            $('#modal_principal').modal('show');
            $("#mensaje, #mensaje_modal").html("");

            $("#hidden_id").val(row.id_sede);
            $("#categoria").select2('trigger', 'select', {
                data: { id: row.id_sede_categoria, text: row.categoria }
            });

            
            if (row.interino == 0) {
                $("#interino").prop("checked", false);
                $("#obs_interino").attr('readonly', false);
            } else {
                $("#interino").prop("checked", true);
                $("#obs_interino").attr('readonly', false);
            }
            
            $("#departamento").select2('trigger', 'select', {
                data: { id: row.departamento, text: row.departamento }
            });
            $("#pais").select2('trigger', 'select', {
                data: { id: row.pais, text: row.pais }
            });
            $("#responsable").select2('trigger', 'select', {
                data: { id: row.id_sede_responsable, text: row.nombre }

            });

            $("#nro_oficina").val(row.nro_oficina);
            $("#oficina").val(row.oficina);
            $("#direccion").val(row.direccion);
            $("#telefono").val(row.telefono);
            $("#obs_interino").val(row.obs_interino);

            if (row.coordenadas) {
                var coordenadas = row.coordenadas.split(',');
                var lat = coordenadas[0];
                var log = coordenadas[1];
                //googleMaps('mapa_carga', '#lat_carga', '#lon_carga', lat, log);
                $("#lat_carga").val(lat);
                $("#lon_carga").val(log);
                BuscarEnMapa(lat, log);
            } else {
                //googleMaps('mapa_carga', '#lat_carga', '#lon_carga', '-25.305515819286462', '-57.61126781015959');
                dibujarMapa('-25.305515819286462', '-57.61126781015959', 'myMap');
            }

           
            //ELIMINAR
            $('#eliminar').click(function() {
                var nombre = row.nro_oficina;
                swal({
                    title: "¿Eliminar: Sede Nro: " + nombre + "?",
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
                        url: 'inc/sedes-data',
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
                                $('#tabla').bootstrapTable('refresh', { url: 'inc/sedes-data?q=ver' });
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
    function resetForm(form) {
        $(form).trigger('reset');
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
        sortName: "s.nro_oficina",
        sortOrder: 'DESC',
        height: $(window).height() - 90,
        pageSize: Math.floor(($(window).height() - 90) / 50),
        columns: [
            [
                { field: 'id_sede', align: 'center', valign: 'middle', title: 'ID', sortable: true, visible: false },
                { field: 'nro_oficina', align: 'left', valign: 'middle', title: 'Nro. Oficina', sortable: true },
                { field: 'oficina', align: 'left', valign: 'middle', title: 'Oficina', sortable: true },
                { field: 'pais', align: 'left', valign: 'middle', title: 'País', sortable: true },
                { field: 'departamento', align: 'left', valign: 'middle', title: 'Departamento', sortable: true },
                { field: 'categoria', align: 'left', valign: 'middle', title: 'Categoria', sortable: true },
                { field: 'nombre_estado', align: 'center', width: 80, valign: 'middle', title: 'Estado', sortable: true, formatter: colorEstado },
                { field: 'fecha', align: 'left', valign: 'middle', title: 'Fecha Alta', width: 160, sortable: true, visible: true },
                { field: 'editar', align: 'center', valign: 'middle', width: 150, title: 'Editar', sortable: false, events: accionesFila, formatter: iconosFila },
                /*{ field: 'id_sede_categoria', visible: false },
                { field: 'coordenadas', visible: false },
                { field: 'direccion', visible: false },
                { field: 'telefono', visible: false },*/
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
        $('#modalLabel').html('Agregar Sede');
        $('#formulario').attr('action', 'cargar');
        //googleMaps('mapa_carga', '#lat_carga', '#lon_carga', '-25.305515819286462', '-57.61126781015959');
        BuscarEnMapa('-25.305515819286462', '-57.61126781015959');
    });

    $('#modal_principal').on('show.bs.modal', function(e) {
        $("#mensaje").html("");
        if ($('#formulario').attr('action') == "cargar") {
            $('#eliminar').hide();
            limpiarModal(e);
        }
    });

    
    $('#categoria').on('change', function() {
        var seleccionado = $(this).val();
        if(seleccionado == 1){
           $('#numerooficina').addClass('d-none');  
        }
        else{
            $('#numerooficina').removeClass('d-none'); 
        }
    });

    $('#buscar_coordenadas').click(function() {
        var longitud = $("#lon_carga").val();
        var latitud  = $("#lat_carga").val();
        //googleMaps('mapa_carga', '#lat_carga', '#lon_carga', latitud, longitud);
        BuscarEnMapa(latitud, longitud);
    });

    $('#modal_principal').on('shown.bs.modal', function(e) {
        $("form #categoria,input[type!='hidden']:first").focus();
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header"
    });

    function limpiarModal() {
        $(document).find('form').trigger('reset');
        $("#mensaje_modal").html("");
        $('#categoria').val('').trigger('change');
        $('#pais').val('').trigger('change');
        $('#departamento').val('').trigger('change');
        $('#responsable').val('').trigger('change');
    }

    //GUARDAR NUEVO REGISTRO O CAMBIOS EDITADOS
    $("#formulario").submit(function(e) {
        e.preventDefault();
        $("#mensaje_modal").html("");
        var data = $(this).serializeArray();
        $.ajax({
            url: 'inc/sedes-data?q=' + $(this).attr("action"),
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
                    $('#tabla').bootstrapTable('refresh', { url: 'inc/sedes-data?q=ver' });
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

    /*function googleMaps(id, lat_input, lon_input, lat, lon) {
        var marker;
        position = {
            coords: {
                latitude: lat,
                longitude: lon
            }
        }
        success(position);

        function success(position) {
            var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            var myOptions = {
                zoom: 15,
                center: coords,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            var map = new google.maps.Map(document.getElementById(id), myOptions);
            addMarker(coords, 'Mi ubicación', map);
            google.maps.event.addListener(map, 'click', function(event) {
                addMarker(event.latLng, 'Click Generated Marker', map);
            });

            function addMarker(latlng, title, map) {
                if (!marker) {
                    marker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                        title: title,
                        draggable: true
                    });
                } else {
                    marker.setPosition(latlng);
                    $(lat_input).val(latlng.lat());
                    $(lon_input).val(latlng.lng());
                }
                google.maps.event.addListener(marker, 'drag', function(event) {
                    $(lat_input).val(event.latLng.lat());
                    $(lon_input).val(event.latLng.lng());
                });
                google.maps.event.addListener(marker, 'dragend', function(event) {
                    $(lat_input).val(event.latLng.lat());
                    $(lon_input).val(event.latLng.lng());
                });
            }
            $(lat_input).val(position.coords.latitude);
            $(lon_input).val(position.coords.longitude);
        }
    }
    */
    </script>
</body>

</html>