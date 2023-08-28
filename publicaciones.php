<?php 
$title       = "Publicaciones | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';

if (!empty($_GET['buscar']) || isset($_GET['btn-buscar'])) {
    if (empty($_GET['buscar'])) {
        echo "<script>location.href ='" . url() . "publicaciones';</script>";
        exit;
    }
    $b = limpia_url($_GET['buscar']);
    header("location:" . url() . "publicaciones/$b");
}
if (!empty($_GET['desde']) && !empty($_GET['hasta'])) {
    $d = url_amigable(fechaLatinaURL($_GET['desde']));
    $h = url_amigable(fechaLatinaURL($_GET['hasta']));
    header("location:" . url() . "publicaciones/desde/$d/hasta/$h");
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
                                <h3>Publicaciones</h3>
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
                        <li>Publicaciones</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="blog-page-section sec-padding">
        <div class="contenedor2">
            <div class="row">
                <div class="col-md-3 right-box" style="margin-bottom:1rem;">
                    <div class="single-sidebar-widget mb-2">
                        <div class="search-widget contenedor">
                            <form>
                                <input type="text" class="form-control" name="buscar" placeholder="Buscar Publicaciones">
                                <button class="dytroit2" type="submit" name="btn-buscar"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="single-sidebar-widget">
                        <div class="category-widget">
                            <div class="title">
                                <h4>Filtrar por fecha</h4>
                            </div>
                            <form id="formBuscar">
                                <?php set_csrf(); ?>
                                <label>DESDE</label>
                                <input type="date" id="desde" class="form-control mb-2" name="desde" autocomplete="off" required value="<?php echo fechaMYSQLURL($desde); ?>">
                                <label>HASTA</label>
                                <input type="date" id="hasta" class="form-control mb-2" name="hasta" autocomplete="off" required value="<?php echo fechaMYSQLURL($hasta); ?>">
                                <button type="submit" id="submit" class="thm-btn text-left">Filtrar <i class="fa fa-filter"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php 
                        if ($buscar) {
                            $buscar = str_replace('-',' ',$buscar);
                            echo '<h4 class="mt-1">Resultados para “<b class="numeroD">'.$buscar.'</b>”</h4>';
                        }
                         ?>
                </div>
                <div class="col-md-9">
                    <?php 
                    include 'inc/publicaciones-data.php';
                    traerPublicaciones($pag, 12, $buscar, $desde, $hasta);
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
</body>

</html>