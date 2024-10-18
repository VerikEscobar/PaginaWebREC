<?php
$db = DataBase::conectar();
$db->setQuery("SELECT
  id_sede_categoria,
  categoria
FROM
  sedes_categorias
  WHERE estado=1");
$rows = $db->loadObjectList();

if ($rows) {
  echo '<option value=""> [TODOS] </option>';
    foreach ($rows as $r) {
        $id_sede_categoria = $r->id_sede_categoria;
        $categoria = $r->categoria;
        echo '<option value="'.$id_sede_categoria.'">'.$categoria.'</option>';
    }
} else {
    echo '<option value=""> [TODOS] </option>';
}