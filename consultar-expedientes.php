<?php 
$title       = "Consultar Expediente | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';

?>
<style>
    .table-info {
        width: 70%;
        border-collapse: collapse;
        margin-bottom: 20px;
        margin-top: -30px;
        background-color: #f9f9f9;
        box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
    }

    .table-info td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    .table-info td:first-child {
        font-weight: bold;
        background-color: #f1f1f1;
        width: 40%;
    }

    .table-striped {
        width: 100%;
        margin-bottom: 1rem;
        background-color: #fff;
        box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: left;
    }

    .right-box {
        padding: 15px;
        background-color: #fff;
        border-radius: 5px;
    }

    .foto1 {
        display:flex;
        justify-content:center;
        align-items: center;
        width: 300px;
        margin-left: 50px;
        margin-right: 30px;
    }

    .foto2 {
        width: 350px;
        padding-top: 120px;
    }

    .contenido {
        display:flex;
        justify-content:center;
        align-items: center;
        padding-top: 85px;
    }

    @media (max-width: 1199px) {
        .foto2 {
            width: 260px;
            padding-top: 150px;
        }
    }

    @media (max-width: 991px) {
        .contenido {
            padding-top: 0px;
        }
        .foto2 {
            padding-top: 0px;
        }
        .foto1 img {
            display: none;
        }
        .foto2 img {
            display: none;
        }
        .table-info {
            width: 100%;
        }
    }


</style>
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
                                <h3>Consulta de Expedientes Electrónicos</h3>
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
                        <li>Consulta de Expedientes Electrónicos</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-page-section sec-padding">
        <div class="container">
            <div class="row">
                <!-- Columna 1: Primera foto -->
                <div class="col-md-4 foto1">
                    <img src="<?php echo url() . 'img/expediente.jpg'; ?>">
                </div>
                
                <!-- Columna 2: Segunda foto -->
                <div class="col-md-4 foto2">
                    <img src="<?php echo url() . 'img/ingrese.jpg'; ?>">
                </div>
                
                <!-- Columna 3: Campos de formulario -->
                <div class="col-md-4 contenido">
                    <form id="formBuscar" method="post" enctype="multipart/form-data" action="">
                    <div class="form-group text-center">
                        <select id="anio" class="form-control" name="anio" onkeypress="return soloNumeros(event)" autocomplete="off">
                            <option value="2024">2024</option>    
                        </select>
                    </div>
                    <div class="form-group text-center">
                        <input type="text" id="coso_medio" class="form-control" name="coso_medio" onkeypress="return soloNumeros(event)" placeholder="12009015" value="12009015" autocomplete="off" readonly>
                    </div>
                    <div class="form-group text-center">
                        <input type="text" id="nro_expediente" class="form-control" name="nro_expediente" onkeypress="return soloNumeros(event)" placeholder="Ingrese el Nro. de Expediente" autocomplete="off">
                    </div>
                    <div class="text-center">
                        <div class="g-recaptcha mt-2 cacha" data-sitekey="6LfbkEYUAAAAAHSiRWWW-hDV-8mzbMXDDyKbn5cJ"></div>
                        <button type="submit" id="btn-buscar" name="btn-buscar" class="thm-btn text-center mt-2">CONSULTAR</button>
                    </div>
                    </form>
                </div>
            </div>

            <div class="mt-3 col-md-12">
                <br>
                <h4><center>Datos del Expediente</center></h4>
                <hr>
            </div>

            <div class="mt-3 col-md-12">
                <div id="aviso"></div>
            </div>

            <div class="row">
                <div class="col-md-12 right-box mb-2">
                    <table class="table-info mb-4">
                        <tbody>
                            <tr>
                                <td><b>Número de expediente:</b></td>
                                <td id="nro_exp"></td>
                            </tr>
                            <tr>
                                <td><b>Asunto:</b></td>
                                <td id="asunto"></td>
                            </tr>
                            <tr>
                                <td><b>N° de Documento:</b></td>
                                <td id="nro_titular"></td>
                            </tr>
                            <tr>
                                <td><b>Titular:</b></td>
                                <td id="titular"></td>
                            </tr>
                            <tr>
                                <td><b>Oficina actual:</b></td>
                                <td id="ofactual"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table id="tabla" class="table-striped table-bordered"></table>
                </div>
            </div>
            <br><br><br>
        </div>
    </section>


    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
    <script src="<?php echo url(); ?>js/bootstrap-table.min.js"></script>
    <script src="<?php echo url(); ?>js/locale/bootstrap-table-es-AR.js"></script>
    <script src="<?php echo url(); ?>js/extensions/mobile/bootstrap-table-mobile.min.js"></script>
    <script type="text/javascript">
        var url = '<?php echo url(); ?>';

        $("#tabla").bootstrapTable({
            mobileResponsive: true,
            pagination: false,
            showFooter: false,
            sortStable: true,
            columns: [
                [
                    { field: 'nro_actuacion', align: 'left', valign: 'middle', title: 'N°', sortable: false, visible: true, formatter: separadorMiles },
                    { field: 'OficinaActuacion', align: 'left', valign: 'middle', title: 'Oficina de origen', sortable: false, visible: true, formatter: separadorMiles },
                    { field: 'destino', align: 'left', valign: 'middle', title: 'Oficina de destino', sortable: false, visible: true, formatter: separadorMiles },
                    { field: 'actuantestr', align: 'left', valign: 'middle', title: 'Nombre del actuante', sortable: false, visible: false },
                    { field: 'fechafinactuacion', align: 'left', valign: 'middle', title: 'Fecha de actuación', sortable: false, visible: true },
                ]
            ]
        });

        $("#formBuscar").submit(function(e) {
            e.preventDefault();
            var datos = $(this).serializeArray();
            let nro_exp = datos[0].value + '-' + datos[1].value + '-' + datos[2].value.padStart(6, '0');
            $.ajax({
                url: 'consultar-expedientes-data',
                dataType: 'json',
                type: 'POST',
                async: true,
                contentType: 'application/x-www-form-urlencoded',
                data: datos,
                beforeSend: function() {
                    $('#btn-buscar').text('Por favor espere...').prop('disabled', true);
                },
                success: function(data) {
                    $('#btn-buscar').text('CONSULTAR').prop('disabled', false);
                    var status = data.status;
                    var mensaje = data.mensaje;
                    var datos = data.datos;
                    
                    if(status != "error"){     
                        $('#nro_exp').text(data.nro_expediente);
                        $('#asunto').text(data.datos.resultQueryRows.asunto);
                        if (data.titular.resultQueryRows.nroDocTitular === undefined || 
                            data.titular.resultQueryRows.nroDocTitular === null || 
                            (typeof data.titular.resultQueryRows.nroDocTitular === 'object' && 
                            Object.keys(data.titular.resultQueryRows.nroDocTitular).length === 0)) {
                            $('#nro_titular').text("-");
                        } else {
                            $('#nro_titular').text(data.titular.resultQueryRows.nroDocTitular);
                        }

                        if (data.titular.resultQueryRows.nombreTitular === undefined || 
                            data.titular.resultQueryRows.nombreTitular === null || 
                            (typeof data.titular.resultQueryRows.nombreTitular === 'object' && 
                            Object.keys(data.titular.resultQueryRows.nombreTitular).length === 0)) {
                            $('#titular').text(data.titular.resultQueryRows.titular);
                        } else {
                            $('#titular').text(data.titular.resultQueryRows.nombreTitular.toUpperCase());
                        }

                        $('#tabla').bootstrapTable('removeAll');
                        
                        data.historial.resultQueryRows.forEach((row, index) => {
                            // Omitir el último elemento
                            if (index < data.historial.resultQueryRows.length - 1) {
                                row.nro_actuacion = parseInt(index) + 1;
                                
                                // Asignar la oficina del siguiente como destino
                                row.destino = data.historial.resultQueryRows[index + 1].OficinaActuacion;

                                // Insertar la fila en la tabla
                                $("#tabla").bootstrapTable('insertRow', {
                                    index: index,
                                    row: row
                                });
                            }else{
                                $('#ofactual').text(data.historial.resultQueryRows[index].OficinaActuacion);
                            }
                        });
                        setTimeout(function() {
                            $("#aviso").html("");
                            grecaptcha.reset();
                            $("#nro_expediente").val('');
                        }, 2000);
                    }else{
                        $('#aviso').html(alertDismissJS(mensaje, 'error'));
                        grecaptcha.reset();
                        setTimeout(function() {
                            $("#aviso").html("");
                        }, 2000);
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