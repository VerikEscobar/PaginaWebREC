<?php 
include'head.php';
?>

<body>
    <?php include'header.php';?>
    <section class="page-breadcrumb portada">
        <div class="page-section" style="background: url(<?php echo url() . "img/background/c.jpg"; ?>) ">
            <div class="breadcumb-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="breadcrumb text-center">
                            <div class="section-titleBar white-headline text-center">
                                <h3>
                                    <?php echo $titulo; ?>
                                </h3>
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
                        <li>
                            <?php echo $titulo; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="single-services-page sec-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-8 col-xs-12">
                    <div class="row paginaSeccion">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php echo $html ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include'footer.php'; ?>
    <?php include'script.php'; ?>
</body>

</html>