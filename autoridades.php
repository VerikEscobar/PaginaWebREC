<?php 
$title       = "Autoridades | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';
 ?>

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
                                <h3>Directores</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-path">
            <div class="container">
                <div class="row">
                    <ul>
                        <li class="home-bread"><a href="<?php echo url(); ?>">Inicio</a></li>
                        <li>Directores</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="blog-page-section sec-padding" style="
    background: url(<?php echo url() . "img/background/py.jpg"; ?>) no-repeat center center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;">
        <div class="container">
            <div class="row mb-2">
                <div class="col-md-8"></div>
                <div class="col-md-4 right-box">
                    <div class="single-sidebar-widget mb-2">
                        <div class="search-widget">
                            <?php set_csrf(); ?>
                            <div class="posi-derecha">
                                <a class="btn directores" href="director-general">DIRECTOR GENERAL</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="contenAutoridades" class="team-member"></div>
            </div>
        </div>
    </section>
    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
    <script>
    var url = '<?php echo url(); ?>';
    var buscar = '';
    autoridades(buscar);


    $("#formBuscar").submit(function(e) {
        e.preventDefault();
        var buscar = $("#autoridad").val();
        autoridades(buscar);
    });

    function autoridades(buscar) {
        var token = $(".set_csrf").val();
        $.ajax({
            url: url + 'autoridades-data',
            dataType: 'json',
            type: 'POST',
            async: true,
            contentType: 'application/x-www-form-urlencoded',
            data: { buscar: buscar, csrf: token },
            beforeSend: function() {
                $("#contenAutoridades").html("");
            },
            success: function(data) {
                status = data.status;
                mensaje = data.mensaje;
                html = data.html;
                if (status == "ok") {
                    $("#contenAutoridades").html(html);
                } else {
                    $("#contenAutoridades").html("<div class='col-md-8 col-sm-4 col-xs-12'>" + mensaje + "</div>");
                }
            },
            error: function(jqXhr) {
                console.log(jqXhr);
            }
        });
    }
    </script>
</body>

</html>