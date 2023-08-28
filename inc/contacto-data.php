<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

include 'funciones.php';
if (is_csrf_valid()) {

    $nombre       = mb_convert_case(strip_tags($_POST['nombre']), MB_CASE_UPPER, "UTF-8");
    $email        = strip_tags($_POST['email']);
    $telefono     = strip_tags($_POST['telefono']);
    $asunto       = strip_tags($_POST['asunto']);
    $mensaje      = strip_tags($_POST['mensaje']);
    $email_domain = preg_replace('/^[^@]++@/', '', $email);
    $numeros_tel  = preg_match('@[0-9]@', $telefono);

    if (empty($nombre)) {
        $salida = ["status" => "error", "mensaje" => "Error. Por favor especifique un Nombre y Apellido"];
        echo json_encode($salida);
        exit;
    }

    if (empty($email)) {
        $salida = ["status" => "error", "mensaje" => "Error. Por favor especifique un Correo Electrónico"];
        echo json_encode($salida);
        exit;
    }

    if ((bool) checkdnsrr($email_domain, 'MX') == false) {
        $salida = ["status" => "error", "mensaje" => "Error. El correo " . $email . " no es válido"];
        echo json_encode($salida);
        exit;
    }

    if (empty($telefono)) {
        $salida = ["status" => "error", "mensaje" => "Error. Por favor especifique un Teléfono"];
        echo json_encode($salida);
        exit;
    }

    if (!$numeros_tel) {
        $salida = ["status" => "error", "mensaje" => "Error. El campo teléfono solo acepta números"];
        echo json_encode($salida);
        exit;
    }

    if (empty($asunto)) {
        $salida = ["status" => "error", "mensaje" => "Error. Por favor especifique un Asunto"];
        echo json_encode($salida);
        exit;
    }

    if (empty($mensaje)) {
        $salida = ["status" => "error", "mensaje" => "Error. Por favor especifique un Mensaje"];
        echo json_encode($salida);
        exit;
    }

    require 'PHPMailer/vendor/autoload.php';
    $mail   = new PHPMailer(true);
    $numero = date("YmdHi");
    try {

        switch (true) {
            case (!empty($_SERVER['HTTP_X_REAL_IP'])): $ip       = $_SERVER['HTTP_X_REAL_IP'];
            case (!empty($_SERVER['HTTP_CLIENT_IP'])): $ip       = $_SERVER['HTTP_CLIENT_IP'];
            case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])): $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            default:$ip                                          = $_SERVER['REMOTE_ADDR'];
        }

        $mail->isSMTP(); // Send using SMTP
        $mail->Host       = 'mail.freelancerpy.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'app@freelancerpy.com';
        $mail->Password   = 'y4d]kt[IHt9P';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        $mail->setLanguage('es');
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('app@freelancerpy.com', 'Registro Civil');             
        //$mail->addAddress("prensa@registrocivil.gov.py");
        $mail->addAddress("prensa@registrocivil.gov.py");
        $mail->addReplyTo($email, $nombre);

        $mail->isHTML(true);

        $mail->Body = "
                            <h3 style='font-size:12px;font-family:tahoma;font-weight:bold;border-bottom:1px dotted #ccc;'>Mensaje desde la web de Registro Civil</h3>\r\n
                            <p style='font-size:12px;font-family:tahoma;'><b>Nombre: </b>" . $nombre . "</p>
                            <p style='font-size:12px;font-family:tahoma;'><b>Email: </b>" . $email . "</p>
                            <p style='font-size:12px;font-family:tahoma;'><b>Teléfono: </b>" . $telefono . "</p>
                            <p style='font-size:12px;font-family:tahoma;'><b>Asunto: </b>" . $asunto . "</p>
                            <p style='font-size:12px;font-family:tahoma;'><b>IP: </b>" . $ip . "</p>
                            <p style='font-size:12px;font-family:tahoma;'><b>Mensaje: </b><br>" . nl2br($mensaje) . "</p>
                        ";

        $mail->send();
        $salida = ["status" => "success", "mensaje" => "Gracias por escribirnos! En breve nos pondremos en contacto con usted"];
        echo json_encode($salida);
        exit;

    } catch (Exception $e) {
        $salida = ["status" => "error", "mensaje" => "Error. Correo no pudo enviarse. {$mail->ErrorInfo}"];
        echo json_encode($salida);
        exit;
    }

} else {
    $salida = ["status" => "error", "mensaje" => "Error. Faltan algunos parametros"];
    echo json_encode($salida);
    exit;
}
