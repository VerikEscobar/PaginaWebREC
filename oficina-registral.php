<?php 
$title       = "Trámites en Oficinas Registrales | Registro del Estado Civil";
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
</style>

<body>
    <?php include 'header.php'; ?>
    <section class="page-breadcrumb portada">
        <div class="page-section" style="background: url(<?php echo url() . "img/background/c.jpg"; ?>);">
            <div class="breadcumb-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="breadcrumb text-center">
                            <div class="section-titleBar white-headline text-center">
                                <h3>Trámites en Oficinas Registrales</h3>
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
                        <li>Trámites en Oficinas Registrales</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="blog-page-section sec-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div style="margin-bottom: 2rem; text-align: center;">
                        <img class="botones-tramites" src="img/botones_tramites/boton_1.png">
                        <img class="botones-tramites" src="img/botones_tramites/boton_2.png">
                        <img class="botones-tramites" src="img/botones_tramites/boton_3.png">
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <h3>Trámites y Requisitos en Oficinas Registrales</h3>
                        </div>
                        <div class="col-sm-6 space">
                            <ul class="lista-tramites">
                                <li><a class="blan" href="tramites"><button class="btn colorsito">Sede Central</button></a></li>
                                <li><a class="blan" href="tarifas"><button class="btn colorsito">Tarifas</button></a></li>
                                <!--<li><a class="blan" href="formularios"><button class="btn colorsito">Formularios</button></a></li>-->
                                <li><a class="blan" href="sedes/coordinacion-departamentales"><button class="btn colorsito">Sedes</button></a></li>
                           </ul>
                        </div>
                        <div class="col-sm-12">
                            <div id="accordion" class="panel-group">
                                <?php include 'inc/oficina-registral-data.php'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
    <script type="text/javascript">

    $('#accordion').on('show.bs.collapse', function(e) {
        $(e.target).parent().find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');   
    });

    $('#accordion').on('hide.bs.collapse', function(e) {
        $(e.target).parent().find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down'); 
    });
    </script>
</body>

</html>