<?php 
$title       = "Contacto | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';
 ?>

<style type="text/css">
.opaco{
    border: 1px solid #cccccc !important;
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
                                <h3>Contacto</h3>
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
                        <li class="home-bread">Inicio</li>
                        <li>Contacto</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-page sec-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <div class="contact-head">
                        <h3>Información</h3>
                        <div class="contact-icon">
                            <div class="contact-inner">
                                <a><i class="fa fa-map"></i><span>Concepción entre Lapacho y Cedro, Asunción</span></a>
                                <a><i class="fa fa-phone"></i><span>+(595 21) 560 404</span></a>
                                <a><i class="fa fa-envelope"></i><span>contacto@registrocivil.gov.py</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <div class="contact-form">
                        <div class="row">
                            <form id="contactoForm" method="POST" class="contact-form">
                                <?php set_csrf() ?>
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre y Apellido" maxlength="70">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="email" class="email form-control" id="email" name="email" placeholder="Email" maxlength="60">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" maxlength="15" onkeypress="return soloNumeros(event)">
                                </div>
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <input type="text" id="asunto" name="asunto" class="form-control" placeholder="Asunto" maxlength="70">
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <textarea id="mensaje" rows="5" name="mensaje" placeholder="Mensaje" class="form-control opaco"></textarea>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 g-recaptcha mt-2" data-sitekey="6LfbkEYUAAAAAHSiRWWW-hDV-8mzbMXDDyKbn5cJ"></div>
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center mt-2">
                                    <button type="submit" id="submit" class="load-more-btn">Enviar</button>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 mt-2 mb-2">
                                    <div id="mensajeForm"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="map-section">
        <div class="container-full">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="map-section">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14426.120359818682!2d-57.5914209!3d-25.3199874!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xd1f88e2895a4c347!2sRegistro%20del%20Estado%20Civil%20Archivo%20Central!5e0!3m2!1ses-419!2spy!4v1646944822053!5m2!1ses-419!2spy" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <?php include 'script.php'; ?>
    <script type="text/javascript">
    function alertDismissJS(msj, tipo) {
        var salida;
        switch (tipo) {
            case 'error':
                salida = `<div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>${msj}</strong>
                    </div>`;
                break;

            case 'success':
                salida = `<div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>${msj}</strong>
                    </div>`;
                break;

        }
        return salida;
    }

    $("#contactoForm").submit(function(e) {
        var url = '<?php echo url(); ?>';
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: url + 'contacto-data',
            dataType: 'json',
            type: 'POST',
            data: formData,
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#submit').text('Por favor espere...').prop('disabled', true);
            },
            success: function(data) {
                $('#submit').text('Enviar').prop('disabled', false);
                var status = data.status;
                var mensaje = data.mensaje;
                if (status == "success") {
                    $('#mensajeForm').html(alertDismissJS(mensaje, 'success'));
                    setTimeout(function() {
                        $('#mensajeForm').html("");
                        location.reload();
                    }, 5000);
                }
                if (status == "error") {
                    $('#mensajeForm').html(alertDismissJS(mensaje, 'error'));
                    setTimeout(function() {
                        $('#mensajeForm').html("");
                    }, 5000);
                }
            },
            error: function(jqXHR, textStatus) {
                $('#submit').text('Enviar').prop('disabled', false);
                $('#mensajeForm').html(alertDismissJS(jqXHR.responseText, 'error'));
                setTimeout(function() {
                    $('#mensajeForm').html("");
                }, 5000);
            }
        });
    });
    </script>
</body>

</html>