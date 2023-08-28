<?php
function traerFormularios($num = '', $cantidad = "", $buscar_url = "")
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

        $q = "SELECT * FROM formularios f
        WHERE CONCAT_WS(' ', f.titulo, f.descripcion) LIKE '%$buscar_url%' AND f.estado=1
        ORDER BY f.fecha_formulario DESC, f.id_formulario
        DESC LIMIT $inicio,$cantidad";

        $q2 = "SELECT * FROM formularios f
        WHERE CONCAT_WS(' ', f.titulo, f.descripcion) LIKE '%$buscar_url%' AND f.estado=1
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

    } else {

        $query = "SELECT * FROM formularios f
        WHERE f.estado=1 
        ORDER BY f.id_formulario DESC
        LIMIT $inicio,$cantidad";

        $query_2 = "SELECT * FROM formularios f
        WHERE f.estado=1
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
            $id        = $r->id_formulario;
            if ($k < 5) {
                array_push($array_ids, $id);
            }

            $fecha_formulario   = $r->fecha_formulario;
            $titulo             = $r->titulo;
            $descripcion        = $r->descripcion;
            $archivo            = url() . $r->archivo;

            //FOTO///
             echo '
                <div class="post-author mb-2">
                    <div class="inner">
                        <div class="text-box">
                            <h6>'.$titulo.'</h6>
                            <p class="text-justify">'.$descripcion.'</p>
                            <a href="'.$archivo.'" target="_black" class="thm-btn">Descargar <i class="fa fa-download"></i></a>
                        </div>
                    </div>
                </div>
                ';
        }

        if ($id && $total_paginas > 1) {
            echo '
             <div class="col-md-12">
                <nav>
                <ul class="pagination theme-colored mt-0">
            ';
            if (($num - 1) > 0) {
                echo "<li><a href='" . url() . $tipo."/pagina/" . ($num - 1) . "'><i class='fa fa-caret-left'></i></a></li>";
            }

            for ($i = 1; $i <= $total_paginas and $i <= 10; $i++) {
                if ($num == $i) {
                    echo "<li class='active'><a href='" . url() . "leyes/pagina/$num'>$num</a></li>";
                } else {
                    echo "<li><a href='" . url() . $tipo."/pagina/$i'>$i</a></li>";
                }
            }

            if ($i < $total_paginas) {
                echo "<li><a href='" . url() . $tipo."/pagina/$total_paginas' title='Ultima Pagina'><b>$total_paginas</b></a></li>";
            }

            if (($num + 1) <= $total_paginas) {
                echo "<li><a href='" . url() . $tipo."/pagina/" . ($num + 1) . "' aria-label='Next'><i class='fa fa-caret-right'></i></a></li>";
            }
            echo '
                        </ul>
                    </nav>
                </div>';
        }

    } else {
        if ($buscar_url) {
            echo '<div class="alert alert-danger"role="alert">
                No se encontro ningún documento.
             </div>';
        } else {
            echo '<div class="alert alert-danger"role="alert">
                No hay documentos que mostrar en esta sección.
             </div>';
        }
    }

}
