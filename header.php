<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tema = $_COOKIE['tema'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/<?php echo $tema; ?>.css">
    <title>Sistema de Usuarios</title>
</head>

<body>