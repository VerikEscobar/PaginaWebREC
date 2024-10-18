<div id="preloader"></div>
<header class="main-menu-one finance-navbar">
    <nav id="main-navigation-wrapper" class="navbar navbar-default Agency-navbar">
        <div class="container">
            <div class="float-left logo-movil">
                <div class="logo logo_principal pull-left"><a href="<?php echo url(); ?>"><img src="<?php echo url(); ?>img/logo/Registro-Civil.png" alt="Logo del Registro Civil"></a></div>
            </div>

            <div class="float-left logo-pc">
                <div class="logo logo_principal pull-left"><a href="<?php echo url(); ?>"><img src="<?php echo url(); ?>img/logo/Registro-Civil.png" alt="Logo del Registro Civil"></a></div>
            </div>
            
            <div class="float-right logo-movil">
                <div class="logo_justicia logo_principal pull-right"><img src="<?php echo url(); ?>img/logo/m_justicia_e2.png" alt="Logo del Registro Civil"></div>
            </div>

            <div class="navbar-header">
                <button type="button" data-toggle="collapse" data-target="#main-navigation" aria-expanded="false" class="navbar-toggle collapsed"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
        </div>
    </nav>
</header>
<section class="main-menu-three finance-navbar">
        <nav id="main-navigation-wrapper" class="navbar navbar-default Agency-navbar">
            <div class="container">
                <div class="float-left">
                    <div id="main-navigation" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="<?php echo url(); ?>"><i class="fa fa-home fa-lg"></i></a></li>
                            <?php
								include 'inc/menu-data.php';
								echo html(0, $menus);
							?>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
</section>