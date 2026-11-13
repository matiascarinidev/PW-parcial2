<?php
require_once __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tema = $_COOKIE['tema'] ?? 'claro';
$body_class = $tema === 'oscuro' ? 'dark' : '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/global.css">
    <title>Foro PW2</title>
</head>

<body class="<?php echo $body_class; ?>">