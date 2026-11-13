<?php

require_once  "config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin()
{
    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['flash_message'] = "Debes iniciar sesión para acceder a esta página.";
        $_SESSION['flash_type'] = "error";
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

function requireAdmin()
{
    requireLogin();
    if ($_SESSION['usuario_rol'] !== 'admin') {
        $_SESSION['flash_message'] = "No tienes permisos para acceder a esta página.";
        $_SESSION['flash_type'] = "error";
        header("Location: " . BASE_URL . "index.php");
        exit();
    }
}
function requireModeradorOAdmin()
{
    requireLogin();
    if (!in_array($_SESSION['usuario_rol'], ['admin', 'moderador'])) {
        $_SESSION['flash_message'] = "Acceso denegado. Solo administradores y moderadores pueden acceder a esta página.";
        $_SESSION['flash_type'] = "error";
        header("Location: " . BASE_URL . "index.php");
        exit();
    }
}
