<?php
include 'inc/mysql_sgrpy.php';

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
        $db = DataBase_sgrpy::conectar();
        $c_imp = $db->clearText($_POST['cod_impresion']);
        $numero = $db->clearText($_POST['buscar']);
        $cer = substr($c_imp, 0, 2);
        
        // $numeros_solicitud   = preg_match('@[0-9]@', $numero);

        // if (!$numeros_solicitud) {
        //     $salida = ["status" => "error", "mensaje" => "Error, Debe Buscar por el Número de Trámite"];
        //     echo json_encode($salida);
        //     exit;
        // }
        $query = "SELECT li.d_certificado FROM log_impresiones li WHERE li.c_impresion = '$c_imp' AND li.c_token = '$numero'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        
        if (!empty($rows)) {
                $datos = json_decode($rows[0]->d_certificado, true);
            
                // Obtener el valor de CODOFICINA del JSON
                $codOficina = $datos['CODOFICINA'];
            
                // Consulta SQL para obtener la DESCRIPCION de la oficina
                $query1 = "SELECT DESCRIPCION AS NOMBREOFICINA FROM oficina WHERE CODOFICINA = '$codOficina'";
                $db->setQuery($query1);
                $rows1 = $db->loadObjectList();
            
                if (!empty($rows1)) {
                    // Crear un nuevo objeto JSON con los resultados
                    $resultado = [
                        "status" => "success",
                        "tipo" => $cer,
                        "data" => $datos,
                        "NOMBREOFICINANEW" => $rows1[0]->NOMBREOFICINA
                    ];
            
                    $jsonResultado = json_encode($resultado);
                    echo $jsonResultado;
                    exit;
                } 
        } else {
            $salida = ["status" => "error", "mensaje" => "No existe"];
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