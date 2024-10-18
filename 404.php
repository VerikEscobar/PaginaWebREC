<?php 
include'head.php';
?>

<body>
    <div id="preloader"></div>
    <section class="error-page sec-padding" style="background: url('<?php echo url() ?>img/background/about-cta-bg.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="error-page">
                            <!-- map section -->
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="error-main-text text-center">
                                    <h2 class="error-easy-text">Página no Encontrada</h2>
                                    <h1 class="high-text">4<span class="color">0</span>4</h1>
                                    <h3 class="error-bot">¡UPS! El link donde accedió no existe.</h3>
                                    <a class="error-btn" href="<?php echo url(); ?>">IR AL INICIO</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="clearfix"></div>
    <!-- Start Footer bottom section -->
    <?php include'footer.php'; ?>
    <?php include'script.php'; ?>
</body>

</html>