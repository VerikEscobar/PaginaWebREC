<?php
include 'head.php';
if (!empty($_GET['buscar']) || isset($_GET['btn-buscar'])) {
    if (empty($_GET['buscar'])) {
        echo "<script>location.href ='" . url() . "galerias';</script>";
        exit;
    }
    $b = limpia_url($_GET['buscar']);
    header("location:" . url() . "galerias/$b");
}
?>

<body>
    <?php include 'header.php';?>
    <section class="blog-page-section darle-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-4 espacioSide">
                    <div class="right-box">
                        <div class="single-sidebar-widget">
                            <div class="search-widget">
                                <form>
                                    <div class="contenedor">
                                        <input type="text" placeholder="Buscar galería" name="buscar">
                                        <div class="dytroit">
                                            <button type="submit" name="btn-buscar"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="single-sidebar-widget">
                            <div class="post-widget">
                                <div class="title">
                                    <h4>GALERIAS RELACIONADAS</h4>
                                    <div class="decor-line"></div>
                                </div>
                                <ul class="ulwidgetDetalle">
                                    <?php 
                                    include 'inc/galerias-relacionadas-data.php';
                                    traerRelacionadas($titulo, $id_galeria);
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 mt-2">
                    <h3 style="margin-bottom: 3rem" class="mt-2">
                        <?php echo $titulo; ?>
                    </h3>
                    <div class="left-box">
                        <div style="margin-bottom: 3rem" class="single-blog-post">
                            <p>
                                <?php echo $descripcion; ?>
                            </p>
                            <div style="margin-bottom: 3rem" class="single-blog-post">
                                <?php echo $video; ?>
                            </div>
                            <div style="margin-bottom: 3rem" class="row">
                                <?php echo $fotoConten; ?>
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

    </script>
</body>

</html>