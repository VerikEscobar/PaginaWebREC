<?php 
$title       = "Certificado Electrónico | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';
?>
<style type="text/css">
</style>
<style>
    h2 {color:#fff;}
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    
    td h2 {color:#000;}


    img {
      max-width: 100%;
      height: auto;
    }

    .video-container {
      position: relative;
      width: 70%;
      padding-bottom: 40%; /* Proporción del aspecto 16:9 (para videos de YouTube) */
      height: 0;
    }
    
    .video-container iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }
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
                                <h2>Certificado de Acta de Nacimiento Electrónico</h2>
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
                        <li>Certificado de Acta de Nacimiento Electrónico</li>
                    </ul>
                </div>
                
            </div>
        </div>
        <div style="padding-left:5%; padding-right:5%;">
            <img src="http://registrocivil.gov.py/img/CertificadoElectronicoWeb.png" alt="Imagen"></td>
            <center><h3>¿Como gestionar mi Certificado de Acta de Nacimiento Electrónico?</h3>
            <br>
            <div class="video-container">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/mQJbTsrCMmk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe><br>
            </div>
            <a class="load-more-btn" href="https://www.paraguay.gov.py/">Tramite aquí</a></center>
            <br>
        </div>
    </section>

    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
    <script type="text/javascript">

    </script>
</body>

</html>