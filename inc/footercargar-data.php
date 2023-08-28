<?php
$db = DataBase::conectar();

$query = "SELECT f.id_footer, f.descripcion, f.estado 
FROM footer f
WHERE 1=1 AND f.estado=1
ORDER BY f.id_footer";
$db->setQuery($query);
$rows = $db->loadObjectList();

if ($rows) {
    foreach ($rows as $r) {
        $id_footer        = $r->id_footer;
        $descripcion      = $r->descripcion;
        echo '
                <div class="col-md-12" style="padding: 3px 0px 4px 0px  !important;">
                    <div class="text" style="padding: 3px 0px 4px 0px !important;">
                        <p>'.$descripcion.'</p>
                    </div>
                </div>
            ';
    }
} else {
    echo '<div class="alert alert-danger"role="alert">
                No se encontro registros de Footer.
             </div>';
}

