<?php 
$title       = "Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include'head.php';
?>

<body>
    <?php include'header.php';?>
    <section class="slider-section">
        <div class="slider-overly"></div>
        <div class="intro-carousel">
            <?php include 'inc/banner-principal.php'; ?>
        </div>
    </section>
    <div>
    	<?php include 'inc/botones-data.php'; ?>
        <br>
    </div>
    <section class="services-section sec-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="section-titleBar text-center">
                        <h3><span class="color">Noticias Destacadas</span></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="our-services">
                    <?php include 'inc/publicaciones-destacadas.php'; ?>
                </div>
            </div>
        </div>
    </section>
    <?php include'footer.php'; ?>
    <?php include'script.php'; ?>
</body>

</html>