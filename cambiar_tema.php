<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_tema = $_POST['tema'] ?? 'claro';
    setcookie('tema', $nuevo_tema, time() + (86400 * 30), '/');
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
