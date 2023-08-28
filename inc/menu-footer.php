<?php 
$db    = DataBase::conectar();
$db->setQuery("SELECT
  m.id_menu_pagina,
  IFNULL (m.id_menu_padre, 0) AS id_menu_padre,
  m.menu,
  m.url,
  m.url_tipo,
  m.url_target,
  m.orden
FROM
  menus_pagina m
WHERE m.estado = 1 AND m.tipo = 2
UNION
SELECT
  mp.id_menu_pagina,
  IFNULL (mp.id_menu_padre, 0) AS id_menu_padre,
  mp.menu,
  mp.url,
  mp.url_tipo,
  mp.url_target,
  mp.orden
FROM
  menus_pagina m
  JOIN menus_pagina mp
    ON m.id_menu_padre = mp.id_menu_pagina
WHERE mp.estado = 1 AND mp.tipo = 2
ORDER BY orden + 1");

$rows = $db->loadObjectList();
if (!empty($rows)) {
    $menu_footer = [];
    foreach ($rows as $menu) {
        $menu_footer[$menu->id_menu_padre][] = $menu;
    }
}
function html_footer($id_menu_pagina, $menus) {
    $html = "";
    if (isset($menus[$id_menu_pagina])) {
        foreach ($menus[$id_menu_pagina] as $menu) {
            // Menus
            $target = '';
            if ($menu->url_target == 2) {
                $target = 'target="_blank"';
            }

            if (!isset($menus[$menu->id_menu_pagina])) {
                if ($menu->url_tipo == 1) {
                    $html .= '<li><a ' . $target . ' href="' . url() . $menu->url . '">' . $menu->menu . '</a></li>';
                }
                if ($menu->url_tipo == 2) {
                    if(stristr($menu->url,'http')==true){
                        $url = $menu->url;
                    }else{
                        $url = url() . $menu->url;
                    }
                    $html .= '<li><a ' . $target . ' href="' . $url . '">' . $menu->menu . '</a></li>';
                }
            }
        }
    }
    return $html;
}
 ?>