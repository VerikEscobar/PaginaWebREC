<?php 
$title       = "Sedes | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';

// if (!empty($_GET['buscar']) || isset($_GET['btn-buscar'])) {
//     if (empty($_GET['buscar'])) {
//         echo "<script>location.href ='" . url() . "publicaciones';</script>";
//         exit;
//     }
//     $b = limpia_url($_GET['buscar']);
//     header("location:" . url() . "publicaciones/$b");
// }
?>
<style type="text/css">

.paratitulo{
    font-size: 18px !important;
}
</style>

<body>
    <?php include 'header.php'; ?>
    <section class="page-breadcrumb portada">
         <div class="page-section" style="background: url(<?php echo url() . "img/background/c.jpg"; ?>) ">
            <div class="breadcumb-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="breadcrumb text-center">
                            <div class="section-titleBar white-headline text-center">
                                <h3>Sedes</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-path blanquito">
            <div class="container">
                <div class="row">
                    <ul>
                        <li class="home-bread"><a href="<?php echo url(); ?>">Inicio</a></li>
                        <li>Sedes</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="blog-page-section sec-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="toolbar">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <select id="tipo_filtro" name="tipo_filtro" class="form-control" required>
                                    <?php include 'inc/sedes-categoria-data.php'; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <table id="tabla" data-toolbar="#toolbar" data-search="true" data-show-refresh="false" data-show-toggle="false" data-show-fullscreen="false" data-show-columns="false" data-show-columns-toggle-all="false" data-detail-view="false" data-show-export="false" data-show-pagination-switch="false" data-pagination="true" data-id-field="id" data-page-list="[10, 25, 50, 100, all]" data-side-pagination="server"></table>
                </div>
            </div>
        </div>
    </section>
    <div id="modal_detalle" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 id="headerOficina" class="modal-title text-white paratitulo"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>UBICACIÓN</h5>
                            <!--<div id="mapa_carga" style="width:100%; height:405px; margin:auto; margin-bottom: 1.5rem;"></div>-->
                            <div id="myMap" style="width:100%; height:405px; margin:auto; margin-bottom: 1.5rem;"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="single-service-contact">
                                <div class="contact-inner">
                                    <h5>DETALLE</h5>
                                    <a id="nro_sede" href="#"><span id="NROoficinaSede"></span></a>
                                    <a href="#"><i class="fa fa-building"></i><span id="oficinaSede"></span></a>
                                    <a href="#"> <i class="fa fa-map-marker"></i><span id="departamentoSede"></span></a>
                                    <a id="direcsede" href="#"><i class="fa fa-map"></i><span id="direccionSede"></span></a>
                                    <a id="telesede" href="#"><i class="fa fa-phone"></i><span id="telefonoSede"></span></a>  
                                    <div class="row">
                                        <div class="col-md-4" id="imgRes"></div>
                                        <div class="col-md-12">
                                            <a href="#" id="nombre"><i class="fa fa-user"></i><span></span></a>
                                            <a href="#" id="obs_interino"><i class="fa fa-exclamation"></i><span></span></a>
                                            <a href="#" id="cargo"><i class="fa fa-pencil"></i><span></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
    <script src="<?php echo url(); ?>js/bootstrap-table.min.js"></script>
    <script src="<?php echo url(); ?>js/locale/bootstrap-table-es-AR.js"></script>
    <script src="<?php echo url(); ?>js/extensions/mobile/bootstrap-table-mobile.min.js"></script>
    <script src="../mapaModal/Leaflet Mapa/js/leaflet.js"></script>
    <script src="../mapaModal/script.js"></script>
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1NW86t_rj3bXEDBYplwbqE4ufPJohf34" type="text/javascript"></script>-->
    <script type="text/javascript">
    var url = '<?php echo url(); ?>';
    var categoria = '<?php echo $categoriaSede; ?>';


    function icoFilaEditar(value, row, index) {
        return [
            '<button type="button" class="ver-more-btn ver" title="DETALLE">DETALLE</button>'
        ].join('');
    }

    window.filaVer = {
        'click .ver': function(e, value, row, index) {
            $('#modal_detalle').modal('show');
            $('#headerOficina').html(row.oficina);
            if (row.coordenadas) {
                var coordenadas = row.coordenadas.split(',');
                var lat = coordenadas[0];
                var log = coordenadas[1];
                //googleMaps('mapa_carga', lat, log);
                BuscarEnMapa(lat, log);
            } else {
                //googleMaps('mapa_carga', '-25.305515819286462', '-57.61126781015959');
                dibujarMapa('-25.305515819286462', '-57.61126781015959', 'myMap');
            }

            if(row.nro_oficina) {
                $('#NROoficinaSede').html('<p class="numOfi">N°</p>' + row.nro_oficina);
            }else{
                $('#NROoficinaSede').html('');
            }

            if(row.direccion) {
                $('#direcsede').html('<i class="fa fa-map"></i>' +row.direccion);
            }else{
                $('#direcsede').html('');
            }

            if(row.telefono){
                if(row.cargo == "COORDINADORA DEPARTAMENTAL" || row.cargo == "COORDINADOR DEPARTAMENTAL"){
                    $('#telesede').html('<i class="fa fa-phone"></i>' +row.telefono);
                }else{
                    $('#telesede').html('');
                }
            }else{
                $('#telesede').html('');
            }

            if(row.nombre) {
                $('#nombre').html('<i class="fa fa-user"></i>' + row.nombre);
            } else {
                $('#nombre').html('');
            }

            if(row.obs_interino) {
                $('#obs_interino').html('<i class="fa fa-exclamation"></i>' + row.obs_interino);
            } else {
                $('#obs_interino').html('');
            }

            if(row.cargo) {
                $('#cargo').html('<i class="fa fa-pencil"></i>' + row.cargo);
            } else {
                $('#cargo').html('');
            }
            $('#oficinaSede').html(row.oficina);
            $('#departamentoSede').html(" " + row.departamento);
            if(row.foto){
                $('#imgRes').html('<img class="img-rounded img-fluid" src="' + url + row.foto + '">');   
            }else{
                $('#imgRes').html('');
            }
            
        },

    }

    $('#tabla').bootstrapTable({
        mobileResponsive: true,
        sortName: "s.departamento, s.nro_oficina",
        sortOrder: 'ASC',
        columns: [
            { field: 'nro_oficina', align: 'left', valign: 'middle', title: 'Nº de oficina', sortable: true, visible: true },
            { field: 'oficina', align: 'left', valign: 'middle', title: 'OFICINA', sortable: true, visible: true },
            { field: 'departamento', align: 'center', valign: 'middle', title: 'DEPARTAMENTO', sortable: false, width: 150, visible: true },
            { field: 'ver', align: 'center', valign: 'middle', width: 150, title: '', sortable: false, formatter: icoFilaEditar, events: filaVer },
            { field: 'coordenadas', visible: false },
            { field: 'direccion', visible: false },
            { field: 'telefono', visible: false },
            { field: 'nombre', visible: false },
            { field: 'cargo', visible: false },
            { field: 'foto', visible: false }
        ]
    });

    $("#tipo_filtro").change(function() {
        var id_categoria = $("#tipo_filtro").val();
        $('#tabla').bootstrapTable('refresh', { url: url + 'sedes-data?categoria=' + id_categoria });
    });

    $('#tabla').bootstrapTable('refresh', { url: url + 'sedes-data?categoria='+ categoria });

    /*function googleMaps(id, lat, lon) {
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
                }
                google.maps.event.addListener(marker, 'drag', function(event) {});
                google.maps.event.addListener(marker, 'dragend', function(event) {});
            }
        }
    }
    */
    </script>
</body>

</html>