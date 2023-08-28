<?php
$pag_padre = basename($_SERVER['PHP_SELF']); 
include 'header.php'; 


?>
<script src="dist/js/sparkline/jquery.sparkline.min.js"></script>

</head>

<body class="<?php include 'menu-class.php';?> fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Cargando Ñamandú...</p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php include 'topbar.php'; include 'leftbar.php' ?>
        <div class="page-wrapper">
            <div class="container-fluid mt-2">
                <div class="row">
                   
                </div>
            </div>
        </div>
       <?php include 'footer.php'; ?>
    </div>

</body>
</html>