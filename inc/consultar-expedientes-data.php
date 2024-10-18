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
        $anho = $db->clearText($_POST['anio']);
        $medio = $db->clearText($_POST['coso_medio']);
        $c_imp = $db->clearText($_POST['nro_expediente']);;
        $c_limpio = str_pad($c_imp, 6, '0', STR_PAD_LEFT);
        
        $nro_consulta   = $anho . "-" . $medio . "-" . $c_limpio;

        if (!$c_imp) {
            $salida = ["status" => "error", "mensaje" => "Error, Debe Buscar por el Número de Trámite"];
            echo json_encode($salida);
            exit;
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://168.90.177.213:8080/Apia/services/ApiaWSqry_consulta_datos_expediente_ws',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://www.apiasolutions.com/WebServices">
           <soapenv:Header/>
           <soapenv:Body>
              <web:qry_consulta_datos_expediente_ws>
                 <web:ApiaWSInput>
                    <web:Q_nroexpediente>'.$nro_consulta.'</web:Q_nroexpediente>
                 </web:ApiaWSInput>
              </web:qry_consulta_datos_expediente_ws>
           </soapenv:Body>
        </soapenv:Envelope>',
          CURLOPT_HTTPHEADER => array(
            'SOAPAction: http://168.90.177.213:8080/Apia/services/ApiaWSqry_consulta_datos_expediente_ws',
            'Content-Type: application/xml',
            'Cookie: JSESSIONID=85690E67DCA7E6DA9049BF1D6A980104'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://168.90.177.213:8080/Apia/services/ApiaWSqry_consulta_titulares_expediente_ws',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://www.apiasolutions.com/WebServices">
        <soapenv:Header/>
        <soapenv:Body>
            <web:qry_consulta_titulares_expediente_ws>
                <web:ApiaWSInput>
                    <web:Q_nroexpediente>'.$nro_consulta.'</web:Q_nroexpediente>
                </web:ApiaWSInput>
            </web:qry_consulta_titulares_expediente_ws>
        </soapenv:Body>
        </soapenv:Envelope>',
        CURLOPT_HTTPHEADER => array(
            'SOAPAction: http://168.90.177.213:8080/Apia/services/ApiaWSqry_consulta_datos_expediente_ws',
            'Content-Type: application/xml',
            'Cookie: JSESSIONID=66B7450522647F399716DEA4887099D5'
        ),
        ));

        $response1 = curl_exec($curl);

        curl_close($curl);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://168.90.177.213:8080/Apia/services/ApiaWSqry_consulta_historial_expediente_ws',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://www.apiasolutions.com/WebServices">
        <soapenv:Header/>
        <soapenv:Body>
            <web:qry_consulta_historial_expediente_ws>
                <web:ApiaWSInput>
                    <web:Q_nroexpediente>'.$nro_consulta.'</web:Q_nroexpediente>
                </web:ApiaWSInput>
            </web:qry_consulta_historial_expediente_ws>
        </soapenv:Body>
        </soapenv:Envelope>',
        CURLOPT_HTTPHEADER => array(
            'SOAPAction: http://168.90.177.213:8080/Apia/services/ApiaWSqry_consulta_datos_expediente_ws',
            'Content-Type: application/xml',
            'Cookie: JSESSIONID=50C0A0B7E5E35AEB8A365B4A85D8E016'
        ),
        ));

        $response2 = curl_exec($curl);

        curl_close($curl);

        libxml_use_internal_errors(true);  // Habilitar el manejo interno de errores para capturar posibles errores en el XML
        
        $xmlObject = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $xmlObject1 = simplexml_load_string($response1, "SimpleXMLElement", LIBXML_NOCDATA);
        $xmlObject2 = simplexml_load_string($response2, "SimpleXMLElement", LIBXML_NOCDATA);


        if ($xmlObject === false) {
            echo "Error al cargar el XML:\n";
            foreach(libxml_get_errors() as $error) {
                echo "\t", $error->message, "\n";
            }
        } else {
            // Registrar los namespaces utilizados en el XML
            $namespaces = $xmlObject->getNamespaces(true);
            $namespaces1 = $xmlObject1->getNamespaces(true);
            $namespaces2 = $xmlObject2->getNamespaces(true);

            // Definir el espacio de nombres de los elementos
            $body = $xmlObject->children($namespaces['soapenv'])->Body;
            $body1 = $xmlObject1->children($namespaces1['soapenv'])->Body;
            $body2 = $xmlObject2->children($namespaces2['soapenv'])->Body;

            // Acceder a los elementos dentro del cuerpo del XML
            $response = $body->children($namespaces[''])->qry_consulta_datos_expediente_wsResponse;
            $response1 = $body1->children($namespaces1[''])->qry_consulta_titulares_expediente_wsResponse;
            $response2 = $body2->children($namespaces2[''])->qry_consulta_historial_expediente_wsResponse;

            $response_final = [
                "nro_expediente" => $nro_consulta,
                "datos" => $response->ExecResult,
                "titular" => $response1->ExecResult,
                "historial" => $response2->ExecResult,
            ];
            
            echo json_encode($response_final);
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