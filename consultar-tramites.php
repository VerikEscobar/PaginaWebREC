<?php 
$title       = "Consultar Solicitud | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';

?>

<body>
    <?php include 'header.php'; ?>
    <section class="page-breadcrumb portada">
        <div class="page-section" style="background: url(<?php echo url() ."img/background/c.jpg"; ?>) ">
            <div class="breadcumb-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="breadcrumb text-center">
                            <div class="section-titleBar white-headline text-center">
                                <h3>Consulta de Solicitud de Trámite</h3>
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
                        <li>Consulta de Solicitud de Trámite</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="blog-page-section sec-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-9">
                    <div class="col-md-7">
                        <div class="single-sidebar-widget">
                            <div class="category-widget">
                                <form id="formBuscar" method="post" enctype="multipart/form-data" action="">
                                    <center>
                                    <input type="text" id="buscar" class="form-control" name="buscar" onkeypress="return soloNumeros(event)" placeholder="Nro. de Solicitud" autocomplete="off">
                                    <div class="g-recaptcha mt-2 captchap" data-sitekey="6LfbkEYUAAAAAHSiRWWW-hDV-8mzbMXDDyKbn5cJ"></div>
                                    <button type="submit" id="btn-buscar" name="btn-buscar" class="thm-btn text-left mt-2 boton-consulta">CONSULTAR</button>
                                    </center>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 col-md-12 mt-2">
                    <div id="aviso"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 right-box mb-2">
                    <table id="tabla" data-toolbar="#toolbar" data-search="false" data-show-refresh="false" data-show-toggle="false" data-show-fullscreen="false" data-show-columns="false" data-show-columns-toggle-all="false" data-detail-view="false" data-show-export="false" data-show-pagination-switch="false" data-pagination="true" data-id-field="id"></table>
                </div>
            </div>
            <div class="mt-3 col-md-12 mt-2">
                    <span style="text-transform: capitalize;" class="badge badge-pill badge-default">En proceso</span>
                    <p>&nbspEn proceso de verificación de solicitud.</p>
                    <span style="text-transform: capitalize;" class="badge badge-pill badge-success">Finalizado</span>
                    <p>&nbspSe dio cumplimiento a lo solicitado, favor acercarse a la oficina donde inició el trámite.</p>
                    <span style="text-transform: capitalize;" class="badge badge-pill badge-danger">Finalizado - No Procesable</span>
                    <p>&nbspLa gestión deberá ser efectuada personalmente ante la oficina donde inició el trámite.</p>
            </div>
        </div>
    </section>
    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
    <script src="<?php echo url(); ?>js/bootstrap-table.min.js"></script>
    <script src="<?php echo url(); ?>js/locale/bootstrap-table-es-AR.js"></script>
    <script src="<?php echo url(); ?>js/extensions/mobile/bootstrap-table-mobile.min.js"></script>
    <script type="text/javascript">
    var url = '<?php echo url(); ?>';

    function colorEstado(data) {
        switch (data) {
            case 'En Proceso':
                return '<span style="text-transform: capitalize;" class="badge badge-pill badge-default">' + data + '</span></b>';
                break;
            case 'Finalizado':
                return '<span style="text-transform: capitalize;" class="badge badge-pill badge-success">' + data + '</span></b>';
                break;
            case 'Finalizado - No Procesable':
                return '<span style="text-transform: capitalize;" class="badge badge-pill badge-danger">' + data + '</span></b>';
                break;
            default:
                return '<span style="text-transform: capitalize;" class="badge badge-pill badge-default">' + data + '</span></b>';
        }
    }

    $("#tabla").bootstrapTable({
        mobileResponsive: true,
        columns: [
            [
                { field: 'numero_solicitud', align: 'left', valign: 'middle', title: 'Nro. de Solicitud', sortable: true, visible: true, formatter: separadorMiles },
                { field: 'ci', align: 'left', valign: 'middle', title: 'Cédula', sortable: true, visible: true },
                { field: 'solicitante', align: 'left', valign: 'middle', title: 'Nombre del Solicitante', sortable: true, visible: true },
                { field: 'tramite', align: 'left', valign: 'middle', title: 'Tipo de Trámite', sortable: true, visible: true },
                { field: 'nombre_estado', align: 'center', valign: 'middle', title: 'Estado', sortable: true, visible: true, formatter: colorEstado },
            ]
        ]
    });

    $("#formBuscar").submit(function(e) {
        e.preventDefault();
        var data = $(this).serializeArray();
        $.ajax({
            url: 'consultar-tramites-data',
            dataType: 'json',
            type: 'POST',
            async: true,
            contentType: 'application/x-www-form-urlencoded',
            data: data,
            beforeSend: function() {
                $('#btn-buscar').text('Por favor espere...').prop('disabled', true);
            },
            success: function(data) {
                $('#btn-buscar').text('CONSULTAR').prop('disabled', false);
                var status = data.status;
                var mensaje = data.mensaje;
                var datos = data.datos;
                if (status == 'success') {
                    // $('#aviso').html(alertDismissJS(mensaje, 'success'));
                    //$('#tabla').bootstrapTable('refresh', { url: 'inc/consultar-tramites-data' });
                    $('#tabla').bootstrapTable('removeAll');
                    $("#tabla").bootstrapTable('insertRow', {index: 0 , row: datos});

                    setTimeout(function() {
                        $("#aviso").html("");
                        grecaptcha.reset();
                        $("#buscar").val('');
                    }, 2000);
                } else {
                    $('#aviso').html(alertDismissJS(mensaje, 'error'));
                    grecaptcha.reset();
                    setTimeout(function() {
                        $("#mensaje").html("");
                    }, 3000);
                }
            },
            error: function(jqXHR) {
                $('#btn-buscar').text('CONSULTAR').prop('disabled', false);
                var mensaje = null;
                grecaptcha.reset();
                mensaje = ('Mensaje de error: ' + jqXHR.responseText);
                $('#aviso').html(alertDismissJS(mensaje, 'error')).show();
                setTimeout(function() {
                    $('#aviso').html('').hide();
                }, 5000);
            }
        });
    });
    </script>
</body>

</html>