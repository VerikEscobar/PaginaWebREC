<?php 
$title       = "Formularios | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';

if (!empty($_GET['buscar']) || isset($_GET['btn-buscar'])) {
    if (empty($_GET['buscar'])) {
        echo "<script>location.href ='" . url() . "formularios';</script>";
        exit;
    }
    $b = limpia_url($_GET['buscar']);
    header("location:" . url() . "formularios/buscar/$b");
}
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
                                <h3>Formularios</h3>
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
                        <li>Formularios</li>
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
                <div class="col-sm-12 space">
                    <div style="margin-bottom: 1rem;">
                        <ul class="lista-tramites">
                            <li><a class="blan " href="oficina-registral"><button class="btn colorsito">Oficina Registral</button></a></li>
                            <li><a class="blan " href="tarifas"><button class="btn colorsito">Tarifas</button></a></li>
                            <li><a class="blan " href="tramites"><button class="btn colorsito">Ofincina Central</button></a></li>
                            <li><a class="blan " href="sedes/coordinacion-departamentales"><button class="btn colorsito">Sedes</button></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <?php 
                        if ($buscar) {
                            $buscar = str_replace('-',' ',$buscar);
                            echo '<h4>Resultados para “<b class="numeroD">'.$buscar.'</b>”</h4>';
                        }
                     ?>
                </div>
                <div class="col-md-3 right-box">
                    <div class="single-sidebar-widget mb-2">
                        <div class="search-widget">
                            <form id="formBuscar">
                                <div class="contenedor">
                                    <input type="text" id="buscar" class="form-control" name="buscar" placeholder="Buscar Convenios" autocomplete="off">
                                    <div class="dytroit">
                                        <button class="" type="submit" name="btn-buscar"><i class="fa fa-search"></i></button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php 
                include 'inc/formularios-data.php';
                traerFormularios($pag, 12, $buscar);
                ?>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
</body>

</html>