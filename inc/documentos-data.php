<?php
function traerDocumentos($categoria = '', $tipo = "", $num = '', $cantidad = "", $buscar_url = "", $desde = "", $hasta = "")
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

        $q = "SELECT * FROM documentos d
        WHERE CONCAT_WS(' ', d.titulo, d.descripcion, d.numero) LIKE '%$buscar_url%' AND d.id_documento_categoria=$categoria AND d.estado=1
        ORDER BY d.fecha_documento DESC LIMIT $inicio, $cantidad";

        $q2 = "SELECT * FROM documentos d
        WHERE CONCAT_WS(' ', d.titulo, d.descripcion, d.numero) LIKE '%$buscar_url%' AND d.id_documento_categoria=$categoria AND d.estado=1
        ORDER BY d.fecha_documento DESC LIMIT
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

            $q = "SELECT * FROM documentos d
            WHERE fecha_documento BETWEEN '$desde' AND '$hasta' AND d.id_documento_categoria=$categoria AND d.estado=1
            ORDER BY d.fecha_documento DESC LIMIT $inicio, $cantidad";

            $q2 = "SELECT * FROM documentos d
            WHERE fecha_documento BETWEEN '$desde' AND '$hasta' AND d.id_documento_categoria=$categoria AND d.estado=1
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
        
    }
    else {

        $query = "SELECT * FROM documentos d
        WHERE d.id_documento_categoria=$categoria AND d.estado=1 
        ORDER BY d.fecha_documento desc
        LIMIT $inicio, $cantidad";

        $query_2 = "SELECT * FROM documentos d
        WHERE d.id_documento_categoria=$categoria AND d.estado=1
        ORDER BY d.fecha_documento desc
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
            $id        = $r->id_documento;
            if ($k < 5) {
                array_push($array_ids, $id);
            }

            $fecha_documento    = $r->fecha_documento;
            $titulo             = $r->titulo;
            $descripcion        = $r->descripcion;
            $numero             = $r->numero;
            $documento          = url() . $r->documento;

            //FOTO///
             echo '
                <div class="post-author mb-2">
                    <div class="inner">
                        <div class="text-box">
                            <h6><b class="numeroD">'.$numero.'</b> | '.$titulo.'</h6>
                            <p class="text-justify">'.$descripcion.'</p>
                            <a href="'.$documento.'" target="_black" class="thm-btn">Descargar <i class="fa fa-download"></i></a>
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
