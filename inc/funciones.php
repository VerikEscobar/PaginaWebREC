<?php
include "mysql.php";

function url()
{
    $host = $_SERVER['HTTP_HOST'];
    return "http://$host/registrocivil/";
}

function obtenerURL() {
    $URLactual = jQuery(location).attr('href');
    return $URLactual;
}

function font($palabra = "", $tipo = "")
{
    if ($tipo == "mayus") {
        $tipo = MB_CASE_UPPER;
    } else if ($tipo == "titulo") {
        $tipo = MB_CASE_TITLE;
    } else {
        $tipo = MB_CASE_LOWER;
    }
    $resultado = mb_convert_case("$palabra", $tipo, "UTF-8");
    return $resultado;
}
function cortar_titulo($titulo, $len)
{
//BORRAMOS LOS TAGS HTML Y DE CONTROL (RETORNO DE CARRO, TABS, ETC)
    $intro  = preg_replace('~[[:cntrl:]]~', '', trim(strip_tags($titulo)));
    $maxPos = $len;
    if (strlen($intro) > $maxPos) {
        $lastPos = ($maxPos - 3) - strlen($intro);
        $intro   = substr($intro, 0, strrpos($intro, ' ', $lastPos)) . '...';
    }
    return $intro;
}
function postToDiscord($message)
{
    $data = array(
        "content" => $message,
    );
    $curl = curl_init("https://discordapp.com/api/webhooks/715366053164875848/YUZJYiaAtlHbJ7SlS1T_4pnCHp_U7Km1N0PQXCdweCLUilkTzS9W-ug7PZCGsBTW9n3H");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-type: application/json',
    ));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    return curl_exec($curl);
}
function don_crypt($input, $rounds = 4)
{
    $salt       = "";
    $salt_chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
    for ($i = 0; $i < 22; $i++) {
        $salt .= $salt_chars[array_rand($salt_chars)];
    }
    return crypt($input, sprintf($rounds) . $salt);
}
///Encripta los campos que le pases
class Password
{
    const SALT = 'freelancerpy';
    public static function hash($password)
    {
        return hash('sha512', self::SALT . $password);
    }
    public static function verify($password, $hash)
    {
        return ($hash == self::hash($password));
    }
}
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
function url_amigable($url_tmp)
{
    $url_utf8 = mb_strtolower($url_tmp, 'UTF-8');
    $find     = array(
        ' ',
        '&',
        '\r\n',
        '\n',
        '+',
    );
    $url_utf8 = str_replace($find, '-', $url_utf8);
    $url_utf8 = strtr(utf8_decode($url_utf8), utf8_decode('_àáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'), '-aaaaaaaceeeeiiiionoooooouuuuyy');
    $find     = array(
        '/[^a-z0-9\-<>]/',
        '/[\-]+/',
        '/<[^>]*>/',
    );
    $repl = array(
        '-',
        '-',
        '',
    );
    $url = preg_replace($find, $repl, $url_utf8);
    return $url;
}
function limpia_url($url_tmp)
{
    $url_utf8 = mb_strtolower($url_tmp, 'UTF-8');
    $find     = array(
        ' ',
        '&',
        '\r\n',
        '\n',
        '+',
    );
    $url_utf8 = str_replace($find, '-', $url_utf8);
    $url_utf8 = strtr(utf8_decode($url_utf8), utf8_decode('_àáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'), '-aaaaaaaceeeeiiiionoooooouuuuyy');
    //Ya que usamos TRANSLIT en el comando iconv, tenemos que limpiar los simbolos que quedaron
    $find = array(
        '/[^a-z0-9.\-<>]/',
        '/[\-]+/',
        '/<[^>]*>/',
    );
    $repl = array(
        '',
        '-',
        '',
    );
    $url = preg_replace($find, $repl, $url_utf8);
    return $url;
}
function mesEspanol($mes)
{
    $meses = array(
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
    );
    return $meses[$mes - 1];
}
function verificaLogin($token)
{
    $db    = DataBase::conectar();
    $query = "SELECT id_cliente FROM clientes WHERE token='$token'";
    $db->setQuery($query);
    $u = $db->loadObject();
    if (empty($u->id_cliente)) {
        session_destroy();
        header('Location:' . url() . '');
    }
}
function datosUsuario($token)
{
    $db    = DataBase::conectar();
    $query = "SELECT c.id_cliente, c.nombre, c.ci, c.ruc, c.razon_social, c.telefono, c.email, c.ultimo_login, cd.id_direccion, cd.direccion, cd.id_ciudad, cd.referencia, cd.longitud, cd.latitud from clientes c LEFT JOIN clientes_direcciones cd ON cd.id_cliente=c.id_cliente WHERE c.token='$token'";
    $db->setQuery($query);
    $u = $db->loadObject();
    if ($u) {
        return $u;
    } else {
        session_destroy();
        header('Location:' . url() . '');
    }
}
function fechaLatina($fecha)
{
    $fecha = substr($fecha, 0, 10);
    /*$date = new DateTime($fecha);
    return $date->format('d/m/Y');*/
    list($anio, $mes, $dia) = explode("-", $fecha);
    if (!$anio) {
        return "";
    } else {
        return $dia . "/" . $mes . "/" . $anio;
    }
}
function validateDate($date, $format = 'd-m-Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function fechaLatinaURL($fecha)
{
    $fecha = substr($fecha, 0, 10);
    /*$date = new DateTime($fecha);
    return $date->format('d/m/Y');*/
    list($anio, $mes, $dia) = explode("-", $fecha);
    if (!$anio) {
        return "";
    } else {
        return $dia . "-" . $mes . "-" . $anio;
    }
}
function fechaLatinaHora($fecha)
{
    /*$date = new DateTime($fecha);
    return $date->format('d/m/Y H:i');*/
    list($anio, $mes, $dia) = explode("-", $fecha);
    $hora                   = substr($fecha, 11, 5);
    if (!$anio) {
        return "";
    } else {
        return substr($dia, 0, 2) . "/" . $mes . "/" . $anio . " " . $hora;
    }
}
function fechaMYSQL($fecha)
{
    $fecha                  = substr($fecha, 0, 10);
    list($dia, $mes, $anio) = explode("/", $fecha);
    return $anio . "-" . $mes . "-" . $dia;
}
function fechaMYSQLURL($fecha)
{
    $fecha                  = substr($fecha, 0, 10);
    list($dia, $mes, $anio) = explode("-", $fecha);
    return $anio . "-" . $mes . "-" . $dia;
}
function fechaMYSQLHora($fecha)
{
    $fecha_sola             = substr($fecha, 0, 10);
    $fecha_hora             = substr($fecha, 11, 16);
    list($dia, $mes, $anio) = explode("/", $fecha_sola);
    list($hora, $min)       = explode(":", $fecha_hora);
    return $anio . "-" . $mes . "-" . $dia . " " . $hora . ":" . $min;
}
function getAutoincrement($table)
{
    $db = DataBase::conectar();
    $db->setQuery("SELECT LPAD(`AUTO_INCREMENT`,9,'0') as auto FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '$table'");
    $r = $db->loadObject()->auto;
    return $r;
}
function redondearGs($gs)
{
    if (strlen($gs) >= 4) {
        $a = (int) $gs / 100;
        $b = round($a);
        $c = $b * 100;
        return $c;
    } else if (strlen($gs) <= 3) {
        $a = (int) $gs / 100;
        $b = round($a);
        $c = $b * 100;
        return $c;
    }
}
function separadorMiles($number)
{
    if (is_numeric($number)) {
        $nro = number_format($number, 0, ".", ".");
        return $nro;
    }
}
function separadorMilesDecimales($number)
{
    if (is_numeric($number)) {
        $nro = number_format($number, 2, ",", ".");
        return $nro;
    }
}
function quitaSeparadorMiles($x)
{
    if ($x) {
        return str_replace('.', '', $x);
    } else {
        return 0;
    }
}
function fechaEspanol($x)
{
    $dias = array(
        "Domingo",
        "Lunes",
        "Martes",
        "Miércoles",
        "Jueves",
        "Viernes",
        "Sábado",
    );
    $meses = array(
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
    );
    if ($x == "dia") {
        return $dias[date('w')];
    } else {
        return $dias[date('w')] . ", " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
    }
}
function precio_decimal($precio)
{
    $precio = number_format($precio, 2);
    $precio = str_replace(',', '', $precio);
    return $precio;
}

function descuento($precio, $descuento)
{
    $descuento_temp   = $descuento / 100;
    $precio_descuento = $precio * $descuento_temp;
    $precio           = $precio - $precio_descuento;
    return $precio;
}

function verificaFoto($path_foto){
    if(!empty($path_foto) && file_exists("./" . $path_foto)){
        return url() . $path_foto;
    } else {
        return url() . "img/sin-foto.jpg";
    }
}