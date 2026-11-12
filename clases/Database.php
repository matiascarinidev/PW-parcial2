<?php

class Database
{
    private static $instancia = null;
    private $conexion;

    private function __construct()
    {
        // Detectar si estamos en local (XAMPP) o en producción (Infinity Free)
        $es_local = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.1');

        if ($es_local) {
            // Credenciales locales (XAMPP)
            $servidor = "localhost";
            $usuario_db = "root";
            $clave_db = "";
            $base_datos = "parcial2"; // El nombre de tu base local
            $puerto = 3306;
        } else {
            // Credenciales de producción (Infinity Free)
            $servidor = "sql306.infinityfree.com";
            $usuario_db = "if0_43221022";
            $clave_db = "lufqLpMyAVzbus";
            $base_datos = "if0_43221022_parcial2";
            $puerto = 3306;
        }

        $this->conexion = new mysqli($servidor, $usuario_db, $clave_db, $base_datos, $puerto);
        if ($this->conexion->connect_error) {
            if ($es_local) {
                die("Error de conexión: " . $this->conexion->connect_error);
            } else {
                die("Error de conexión a la base de datos. Contacte al administrador.");
            }
        }
    }
    public static function getInstance()
    {
        if (self::$instancia === null) {
            self::$instancia = new Database();
        }
        return self::$instancia;
    }

    public function getConexion()
    {
        return $this->conexion;
    }
}
