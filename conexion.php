<?php

$servidor = "localhost";
$usuario_db = "root";
$clave_db = "";
$base_datos = "parcial2";

$conexion = new mysqli($servidor, $usuario_db, $clave_db, $base_datos , 3306);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");
