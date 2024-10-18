<?php
function traerPublicaciones($num = '', $cantidad = "", $buscar_url = "", $desde = "", $hasta = "")
{

    if (empty($num)) {
        $num    = 1;
        $inicio = 0;
    } else {
        $inicio = ($num - 1) * $cantidad;
    }

    $db = DataBase::conectar();

    if ($buscar_url) {
        $buscar = $db->clearText($buscar_url);

        $q = "SELECT * FROM noticias n
        WHERE CONCAT_WS(' ', n.titulo, n.descripcion) LIKE '%$buscar_url%' AND n.estado=1
        ORDER BY n.fecha_noticia DESC LIMIT $inicio, $cantidad";

        $q2 = "SELECT * FROM noticias n
        WHERE CONCAT_WS(' ', n.titulo, n.descripcion) LIKE '%$buscar_url%' AND n.estado=1
        LIMIT 1000";

        $db = DataBase::conectar();
        $db->setQuery($q);
        $rows = $db->loadObjectList();

        $db->setQuery($q2);
        $db->loadObjectList();
        $db->setQuery("SELECT FOUND_ROWS() as total");
        $total_registros = $db->loadObject()->total;
        $total_paginas   = ceil($total_registros / $cantidad);
        $array_ids       = array();

    } else if (!empty($desde) &&  !empty($hasta)) {

        if (validateDate($desde) && validateDate($hasta)) {
            $desde = $db->clearText(fechaMYSQLURL($desde));
            $hasta = $db->clearText(fechaMYSQLURL($hasta));

            $q = "SELECT * FROM noticias n
            WHERE n.fecha_noticia BETWEEN '$desde' AND '$hasta' AND n.estado=1
            ORDER BY n.fecha_noticia DESC LIMIT $inicio, $cantidad";

            $q2 = "SELECT * FROM noticias n
            WHERE n.fecha_noticia BETWEEN '$desde' AND '$hasta' AND n.estado=1
            LIMIT 1000";

            $db = DataBase::conectar();
            $db->setQuery($q);
            $rows = $db->loadObjectList();

            $db->setQuery($q2);
            $db->loadObjectList();
            $db->setQuery("SELECT FOUND_ROWS() as total");
            $total_registros = $db->loadObject()->total;
            $total_paginas   = ceil($total_registros / $cantidad);
            $array_ids       = array();

        }else{
            echo "<script>location.href ='" . url() . "error/404';</script>";
        }
        
    }else {

        $query = "SELECT * FROM noticias n
        WHERE n.estado=1 $ex
        ORDER BY n.fecha_noticia DESC
        LIMIT $inicio, $cantidad";

        $query_2 = "SELECT * FROM noticias n
        WHERE n.estado=1 $ex
        lIMIT 1000";

        $db = DataBase::conectar();
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $db->setQuery($query_2);
        $db->loadObjectList();
        $db->setQuery("SELECT FOUND_ROWS() as total");
        $total_registros = $db->loadObject()->total;
        $total_paginas   = ceil($total_registros / $cantidad);
        $array_ids       = array();
    }

    if ($rows) {

        foreach ($rows as $k => $r) {
            $fecha_hoy = date("Y-m-d");
            $id        = $r->id_noticia;
            if ($k < 5) {
                array_push($array_ids, $id);
            }

            $fecha_noticia = $r->fecha_noticia;
            $titulo        = cortar_titulo($r->titulo, 30);
            $descripcion   = cortar_titulo($r->copete, 70);
            $url           = url() . "publicacion/" . url_amigable($r->titulo)."-".$id;

            //FOTO////
            $db->setQuery("SELECT foto FROM noticias_fotos WHERE id_noticia='$id'");
            $foto  = verificaFoto($db->loadObject()->foto);

            echo '
                <div class="col-md-4 col-sm-6 col-xs-12 lift">
                    <a href="' . $url . '">
                        <div class="single-services contiene">
                            <div class="item">
                                <figure class="img-box">
                                    <a href="' . $url . '"><img  src="' . $foto . '" alt="' . $titulo . '"> </a>
                                </figure>
                                <div class="text-content">
                                    <h4><a href="' . $url . '">' . $titulo . '</a></h4>
                                    <p style="margin-bottom: .5rem;">' . fechaLatina($fecha_noticia) . '</p>
                                    <p>' . $descripcion . '</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                ';
        }

        
        
        if(isset($desde) && !empty($desde) && isset($hasta) && !empty($hasta)){
            $url_pagina = "publicaciones/desde/".fechaLatinaURL($desde)."/hasta/".fechaLatinaURL($hasta)."/pagina/";
        }else{
            $url_pagina = 'publicaciones/pagina/';
        }

        if ($id && $total_paginas > 1) {
            echo '
             <div class="col-md-12">
                <nav>
                <ul class="pagination theme-colored mt-0">
            ';
            if (($num - 1) > 0) {
                echo "<li><a href='" . url() . $url_pagina . ($num - 1) . "'><i class='fa fa-caret-left'></i></a></li>";
            }

            for ($i = 1; $i <= $total_paginas and $i <= 10; $i++) {
                if ($num == $i) {
                    echo "<li class='active'><a href='" . url() . "$url_pagina$num'>$num</a></li>";
                } else {
                    echo "<li><a href='" . url() . $url_pagina. $i."'>$i</a></li>";
                }
            }

            if ($i < $total_paginas) {
                echo "<li><a href='" . url() . "$url_pagina$total_paginas' title='Ultima Pagina'><b>$total_paginas</b></a></li>";
            }

            if (($num + 1) <= $total_paginas) {
                echo "<li><a href='" . url() . $url_pagina . ($num + 1) . "' aria-label='Next'><i class='fa fa-caret-right'></i></a></li>";
            }
            echo '
                        </ul>
                    </nav>
                </div>';
        }




    } else {
        if ($buscar_url) {
            echo '<div class="alert alert-danger"role="alert">
                No se encontro lo que buscabas.
             </div>';
        } else {
            echo '<div class="alert alert-danger"role="alert">
                No hay publicaciones en esta sección.
             </div>';
        }
    }

}