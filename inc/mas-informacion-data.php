<?php
$db = DataBase::conectar();

$query = "SELECT t.id_tramite, t.titulo, t.descripcion, t.orden, tc.id_tramite_categoria, t.estado
FROM tramites t
LEFT JOIN tramites_categorias tc ON t.id_tramite_categoria = tc.id_tramite_categoria
WHERE 1=1 AND tc.id_tramite_categoria = 3 AND t.estado=1
ORDER BY t.orden";
$db->setQuery($query);
$rows = $db->loadObjectList();

if ($rows) {
    foreach ($rows as $r) {
        $id_tramite       = $r->id_tramite;
        $titulo           = $r->titulo;
        $descripcion      = $r->descripcion;
        $orden            = $r->orden;
        echo '
                    <div class="panel panel-default">
                      <div id="headingOne" class="panel-heading" style="position:relative;">
                        <h4 class="panel-title">
                            <a href="#'.$id_tramite.'" class="acordeon" data-toggle="collapse" data-parent="#accordion" aria-expanded="false" class="active collapsed">'.$orden.' - '.$titulo.'
                            <i style="position: absolute; top: 50%; left: 95%; transform: translate(-50%, -50%);" class="fa fa-chevron-down"></i></a>
                        </h4>
                      </div>

                      <div id="'.$id_tramite.'" class="panel-collapse collapse" aria-expanded="false">
                        <div class="panel-body" style="padding: 1.5rem;">
                            <ul style="padding-left: 1.5rem;">'.$descripcion.'</ul>
                        </div>
                      </div>

                    </div>
            ';
    }
} else {
    echo '<div class="alert alert-danger"role="alert">
                No se encontro registros de Trámites.
             </div>';
}

