<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
date_default_timezone_set("America/Asuncion");

class Database extends PDO
{

    private $bd      = "recsys";
    private $host    = "10.0.1.53"; 
    private $usuario = "tramite";
    private $pass    = "DI@CT%2021REC_MMXXII";
    private $port    = 5432;
    private $instancia;

    public function __construct()
    {
        try {
            $this->instancia = parent::__construct("pgsql:host=$this->host;port=$this->port;dbname=$this->bd;user=$this->usuario;password=$this->pass");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    // Escapamos para evitar sqlinjection
    public function clearText($string)
    {
        return pg_escape_string(strip_tags($string));
    }
    // Cerramos la conexion
    public function close()
    {
        $this->instancia = null;
    }

}