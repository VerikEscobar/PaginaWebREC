<?php
include "./router.php";
// ##################################################

# VARIABLES GLOBALES (DESARROLLO)
$localPathIndex = '/registrocivil';
$localPath = '/registrocivil/';

// # VARIABLES GLOBALES (PRODUCCIÓN)
// $localPathIndex = '';
// $localPath = '/';


# PAGINA DE INICIO
get($localPathIndex, 'index.php');

# SECCIONES ESTATICAS
get($localPath.'socios', 'socios.php');
get($localPath.'alianzas', 'alianzas.php');
get($localPath.'comision-directiva', 'comision-directiva.php');
// get($localPath.'autoridades', 'autoridades.php');
get($localPath.'autoridades', 'autoridades_new.php');
get($localPath.'contacto', 'contacto.php');

#PUBLICACIONES
get($localPath.'publicaciones', 'publicaciones.php');
get($localPath.'publicaciones/$buscar', 'publicaciones.php');
get($localPath.'publicaciones/desde/$desde/hasta/$hasta', 'publicaciones.php');
get($localPath.'publicaciones/desde/$desde/hasta/$hasta/pagina/$pag', 'publicaciones.php');
get($localPath.'publicaciones/pagina/$pag', 'publicaciones.php');
get($localPath.'publicacion/$publicacion', 'publicacion-detalle.php');

#INFORMES DE GESTION
get($localPath.'informes-de-gestion', 'informe-gestion.php');
get($localPath.'informes-de-gestion/buscar/$buscar', 'informe-gestion.php');
get($localPath.'informes-de-gestion/desde/$desde/hasta/$hasta', 'informe-gestion.php');
get($localPath.'informes-de-gestion/pagina/$pag', 'informe-gestion.php');

#MANUALES
get($localPath.'manuales', 'manuales.php');
get($localPath.'manuales/buscar/$buscar', 'manuales.php');
get($localPath.'manuales/desde/$desde/hasta/$hasta', 'manuales.php');
get($localPath.'manuales/pagina/$pag', 'manuales.php');

#LEYES
get($localPath.'leyes', 'leyes.php');
get($localPath.'leyes/buscar/$buscar', 'leyes.php');
get($localPath.'leyes/desde/$desde/hasta/$hasta', 'leyes.php');
get($localPath.'leyes/pagina/$pag', 'leyes.php');

#DECRETOS
get($localPath.'decretos', 'decretos.php');
get($localPath.'decretos/buscar/$buscar', 'decretos.php');
get($localPath.'decretos/desde/$desde/hasta/$hasta', 'decretos.php');
get($localPath.'decretos/pagina/$pag', 'decretos.php');

#RESOLUCIONES
get($localPath.'resoluciones', 'resoluciones.php');
get($localPath.'resoluciones/buscar/$buscar', 'resoluciones.php');
get($localPath.'resoluciones/desde/$desde/hasta/$hasta', 'resoluciones.php');
get($localPath.'resoluciones/pagina/$pag', 'resoluciones.php');

#CIRCULARES
get($localPath.'circulares', 'circulares.php');
get($localPath.'circulares/buscar/$buscar', 'circulares.php');
get($localPath.'circulares/desde/$desde/hasta/$hasta', 'circulares.php');
get($localPath.'circulares/pagina/$pag', 'circulares.php');

#CIRCULARES
get($localPath.'convenios', 'convenios.php');
get($localPath.'convenios/buscar/$buscar', 'convenios.php');
get($localPath.'convenios/desde/$desde/hasta/$hasta', 'convenios.php');
get($localPath.'convenios/pagina/$pag', 'convenios.php');

#FORMULARIOS
get($localPath.'formularios', 'formularios.php');
get($localPath.'formularios/buscar/$buscar', 'formularios.php');
get($localPath.'formularios/pagina/$pag', 'formularios.php');

#GALERIA
get($localPath.'galerias', 'galeria.php');
get($localPath.'galerias/$buscar', 'galeria.php');
get($localPath.'galerias/desde/$desde/hasta/$hasta', 'galeria.php');
get($localPath.'galerias/pagina/$pag', 'galeria.php');
get($localPath.'galeria/$galeria', 'galeria-detalle.php');

#TRÁMITES
get($localPath.'tramites', 'tramites.php');
get($localPath.'oficina-registral', 'oficina-registral.php');
get($localPath.'mas-informacion', 'mas-informacion.php');
get($localPath.'certificado-electronico', 'certificado_electronico.php');
get($localPath.'consultar-tramites', 'consultar-tramites.php');
post($localPath.'consultar-tramites-data', 'inc/consultar-tramites-data.php');

get($localPath.'consultar-expedientes', 'consultar-expedientes.php');
post($localPath.'consultar-expedientes-data', 'inc/consultar-expedientes-data.php');


get($localPath.'consultar-certificados', 'consultar-certificados.php');
post($localPath.'consultar-certificados-data', 'inc/consultar-certificados-data.php');
//http://localhost/registrocivil/consultar-certificados?cod=CN1&pass=AUSUADJD

#SEDES
get($localPath.'sedes/$categoriaSede', 'sedes.php');

get($localPath.'sedes-data', 'inc/sedes-data.php');

#POST SOLICITUDES
post($localPath.'autoridades-data', 'inc/autoridades-data.php');
post($localPath.'contacto-data', 'inc/contacto-data.php');

//get($localPath.'prueba', 'sedes_csv.php');

# PAGINAS DINAMICAS
get($localPath.'$pagina', 'pagina.php');

# PAGINA 404 (Páginas que no existen redirecciona a 404.php)
any('error/404', '/404.php');
//get($localPath.'404', '404.php');

