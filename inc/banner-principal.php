<?php
$db = DataBase::conectar();
$db->setQuery("SELECT url, imagen, titulo, descripcion,url_nombre
                    FROM banner
                    WHERE estado=1 AND orden>0 and orden<=5
                    ORDER BY orden
                    LIMIT 5");
$rows = $db->loadObjectList();

if ($rows) {

    foreach ($rows as $r) {

        $id          = $r->id_banner;
        $titulo      = $r->titulo;
        $descripcion = $r->descripcion;
        $url_nombre  = $r->url_nombre;
        $url         = $r->url;
        if ($r->imagen) {
            $foto = $r->imagen;
        } else {
            $foto = url() . "img/background/b.jpg";
        }

    echo '<div class="intro-content">
                <div class="slider-images">
                    <img src="' . $foto . '" alt="' . $titulo . '">
                </div>';

            echo'<div class="slider-content">
                    <div class="display-table">
                        <div class="display-table-cell">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">';
                                        if ($titulo) {
                                           echo'<div class="layer-1-2">
                                                <h1 class="title2">' . $titulo . '</h1>
                                            </div>';
                                        }
                                        if ($descripcion) {
                                            echo'<div class="layer-1-1 ">
                                            <p>' . $descripcion . '</p>
                                            </div>';
                                        }
                                        if ($url_nombre && $url) {
                                              echo'<div class="layer-1-3">
                                                <a href="' . $url . '" class="ready-btn left-btn">' . $url_nombre . '</a>
                                            </div>';
                                        }

                                echo'</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        echo'</div>';
    }
} else {
    echo "";
}