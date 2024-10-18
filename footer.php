<footer class="footer sec-padding-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
			
                <div class="footer-widget links-widget links-widget-pac">
                    <!-- <div class="title"> -->
                        <!-- <h4>OTROS ENLACES</h4> -->
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
							<div class="footer-widget about-widget centro"><a class="logo" href="<?php echo url(); ?>"><img src="<?php echo url(); ?>img/logo/footer_banner.png" alt="Logo"></a></div>            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                 <div class="footer-widget links-widget links-widget-pac centro" style="margin-top:5%;">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <ul>
                                 <?php
                                    include 'inc/menu-footer.php';
                                    echo html_footer(0, $menu_footer);
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="footer-widget subscribe-widget contenContactIn centro">
                    <div class="tel-box">
                        <?php
                            include 'inc/footercargar-data.php';
                        ?>
                    </div>

                    <ul class="social list-inline">
                        <?php
                            include 'inc/footerredes-data.php';
                        ?>
                              
                    </ul>                    
                </div>
            </div>

        </div>
    </div>
</footer>
<section class="footer-bottom">
    <div class="container clearfix">
        <div class="colorFooterI">
            <p> © <?php echo date("Y") ?>. Todos los derechos reservados. </p>
        </div>
        <div class="colorFooterD">
            <p><a href="https://registrocivil.gov.py/" target="_blank"></a></p>
        </div>
    </div>
</section>