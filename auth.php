<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login()
{
    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['flash_message'] = "Debes iniciar sesión para acceder a esta página.";
        $_SESSION['flash_type'] = "error";
        header("Location: login.php");
        exit();
    }
}

function require_admin()
{
    require_login();;
    if ($_SESSION['usuario_rol'] !== 'admin') {
        $_SESSION['flash_message'] = "No tienes permisos para acceder a esta página.";
        $_SESSION['flash_type'] = "error";
        header("Location: index.php");
        exit();
    }
}
function require_moderador_o_admin()
{
    require_login();
    if (!in_array($_SESSION['usuario_rol'], ['admin', 'moderator'])) {
        $_SESSION['flash_message'] = "Acceso denegado. Solo administradores y moderadores pueden acceder a esta página.";
        $_SESSION['flash_type'] = "error";
        header("Location: index.php");
        exit();
    }
}
