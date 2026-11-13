<?php
require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Perfil.php";
require_once "includes/auth.php"; // Inclusión que faltaba
requireLogin();

$usuario_id = $_SESSION['usuario_id'];
$perfilObj = new Perfil();
$perfil = $perfilObj->obtenerPorUsuarioId($usuario_id);

if (!$perfil) {
    header("Location: " . BASE_URL . "crear_perfil.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $usuario = trim($_POST['usuario'] ?? "");
    $nombre = trim($_POST['nombre'] ?? "");
    $apellido = trim($_POST['apellido'] ?? "");
    $telefono = trim($_POST['telefono'] ?? "");
    $biografia = trim($_POST['biografia'] ?? "");

    if (empty($usuario) || empty($nombre) || empty($apellido)) {
        $_SESSION['flash_message'] = "Usuario, nombre y apellido son obligatorios.";
        $_SESSION['flash_type'] = "error";
        header("Location: " . BASE_URL . "editar_perfil.php");
        exit();
    } else {
        $exito = $perfilObj->actualizarPropio($usuario_id, $usuario, $nombre, $apellido, $telefono, $biografia);
        if ($exito) {
            $_SESSION['flash_message'] = "Perfil actualizado correctamente.";
            $_SESSION['flash_type'] = "success";
            header("Location: " . BASE_URL . "perfil.php?u=" . urlencode($usuario) . "&tab=configuracion");
            exit();
        } else {
            $_SESSION['flash_message'] = "Error al actualizar. El nombre de usuario ya está en uso.";
            $_SESSION['flash_type'] = "error";
            header("Location: " . BASE_URL . "editar_perfil.php");
            exit();
        }
    }
}
