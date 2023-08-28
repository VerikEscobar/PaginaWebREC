<?php 
$title       = "Decretos | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';

if (!empty($_GET['buscar']) || isset($_GET['btn-buscar'])) {
    if (empty($_GET['buscar'])) {
        echo "<script>location.href ='" . url() . "decretos';</script>";
        exit;
    }
    $b = limpia_url($_GET['buscar']);
    header("location:" . url() . "decretos/buscar/$b");
}
if (!empty($_GET['desde']) && !empty($_GET['hasta'])) {
    $d = url_amigable(fechaLatinaURL($_GET['desde']));
    $h = url_amigable(fechaLatinaURL($_GET['hasta']));
    header("location:" . url() . "decretos/desde/$d/hasta/$h");
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
                                <h3>Decretos</h3>
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
                        <li>Decretos</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="blog-page-section sec-padding">
        <div class="container">
            <div class="row">
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
                                    <input type="text" id="buscar" class="form-control" name="buscar" placeholder="Buscar decreto" autocomplete="off">
                                    <div class="dytroit">
                                        <button type="submit" name="btn-buscar"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
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
                                <input type="date" id="desde" class="form-control mb-2" name="desde" autocomplete="off" required>
                                <label>HASTA</label>
                                <input type="date" id="hasta" class="form-control mb-2" name="hasta" autocomplete="off" required>
                                <button type="submit" id="submit" class="thm-btn text-left">Filtrar <i class="fa fa-filter"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php 
                include 'inc/documentos-data.php';
                traerDocumentos(3,"decretos", $pag, 12, $buscar, $desde, $hasta);
                ?>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
</body>

</html>