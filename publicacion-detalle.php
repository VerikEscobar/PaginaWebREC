<?php
include 'head.php';
if (!empty($_GET['buscar']) || isset($_GET['btn-buscar'])) {
    if (empty($_GET['buscar'])) {
        echo "<script>location.href ='" . url() . "publicaciones';</script>";
        exit;
    }
    $b = limpia_url($_GET['buscar']);
    header("location:" . url() . "publicaciones/$b");
}
?>

<body>
    <?php include 'header.php';?>
    <section class="blog-page-section darle-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mt-2">
                    <h3 style="margin-bottom: 1.5rem" class="mt-2">
                        <?php echo $titulo; ?>
                    </h3>
                      <p class="mt-2">
                        <?php echo fechaLatina($fecha_noticia); ?>
                        </p>
                    <div class="left-box">
                        <div style="margin-bottom: 3rem" class="single-blog-post">
                            <div style="margin-bottom: 3rem" class="owl-carousel owl-theme">
                                <?php echo $fotoConten; ?>
                            </div>
                            <p>
                                <?php echo $descripcion; ?>
                            </p>
                        </div>
                        <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                            <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                            <a class="a2a_button_facebook"></a>
                            <a class="a2a_button_twitter"></a>
                            <a class="a2a_button_email"></a>
                            <a class="a2a_button_whatsapp"></a>
                            <a class="a2a_button_telegram"></a>
                            <a class="a2a_button_facebook_messenger"></a>
                        </div>
                        <script async src="https://static.addtoany.com/menu/page.js"></script>
                    </div>
                </div>
                <div class="col-md-4 espacioSide">
                    <div class="right-box">
                        <div class="single-sidebar-widget">
                            <div class="search-widget contenedor">
                                <form>
                                    <input type="text" placeholder="Buscar Publicación" name="buscar">
                                    <button class="dytroit2" type="submit" name="btn-buscar"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="single-sidebar-widget">
                            <div class="post-widget">
                                <div class="title">
                                    <h4>PUBLICACIONES RELACIONADAS</h4>
                                    <div class="decor-line"></div>
                                </div>
                                <ul class="ulwidgetDetalle">
                                    <?php 
                                    include 'inc/publicaciones-relacionadas-data.php';
                                    traerRelacionadas($titulo, $id_noticia);
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php';?>
    <?php include 'script.php';?>
    <script type="text/javascript">
    $('.owl-carousel').owlCarousel({
        loop: false,
        nav: true,
        autoplay: true,
        dots: false,
        navText: ["<i class='icon icon-chevron-left'></i>", "<i class='icon icon-chevron-right'></i>"],
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })
    </script>
</body>

</html>