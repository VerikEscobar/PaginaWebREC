<?php
include 'inc/postgres.php';
function file_get_contents_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
if (isset($_REQUEST['g-recaptcha-response']) && !empty($_REQUEST['g-recaptcha-response'])) {

    $secret = '6LfbkEYUAAAAAPzMBlLJqrrns4t031LsV9LKXr8t';

    $verifyResponse = file_get_contents_curl('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_REQUEST['g-recaptcha-response']);
    $responseData   = json_decode($verifyResponse);

    if ($responseData->success) {
        $db = new Database;
        $numero              = $db->clearText($_POST['buscar']);
        $numeros_solicitud   = preg_match('@[0-9]@', $numero);

        if (!$numeros_solicitud) {
            $salida = ["status" => "error", "mensaje" => "Error, Debe Buscar por el Número de Trámite"];
            echo json_encode($salida);
            exit;
        }

        ///Consulta
        $query = $db->prepare("SELECT ll.cedula AS ci, ll.solicitud AS numero_solicitud, ll.nombre_solicitante AS solicitante,  
        CASE ll.estado_bodega_id 
        WHEN 1 THEN 'En Proceso'
        WHEN 2 THEN 'En Proceso'
        WHEN 3 THEN 'En Proceso'
        WHEN 4 THEN 'Finalizado - No Procesable'
        WHEN 5 THEN 'En Proceso'
        WHEN 6 THEN 'En Proceso'
        WHEN 7 THEN 'Finalizado'
        WHEN 10 THEN 'En Proceso'
        WHEN 11 THEN 'En Proceso'
        WHEN 12 THEN 'En Proceso'
        WHEN 13 THEN 'En Proceso'
        WHEN 14 THEN 'En Proceso'
        WHEN 15 THEN 'Finalizado - No Procesable'
        END AS nombre_estado,
        CONCAT (s.nombre, ' / ',lt.nombre) AS tramite
        FROM libros_localizaciones ll
        LEFT JOIN libros_tipos lt ON ll.tipo_id = lt.id 
        LEFT JOIN solicitudes s ON ll.tipo_solicitud = s.id 
        WHERE solicitud=$numero");
        $query->execute();
        $row = $query->fetch(PDO::FETCH_OBJ);

        if ($row) {
            $salida = ["status" => "success", "mensaje" => "Consulta exitosa", "datos" => $row];
            echo json_encode($salida);
            exit;
        } else {
            $salida = ["status" => "error", "mensaje" => "No existe Trámite con el número de solicitud ingresado", "datos" => $row];
            echo json_encode($salida);
            exit;
        }
    } else {
        $salida = ["status" => "error", "mensaje" => "Token no Válido Favor Verificar"];
        echo json_encode($salida);
        exit;
    }
} else {
    $salida = ["status" => "error", "mensaje" => "Error de Recaptcha Favor Verificar"];
    echo json_encode($salida);
    exit;
}