<?php
require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Publicacion.php";
require_once "includes/auth.php";
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $contenido = trim($_POST['contenido'] ?? "");
    $redirigir = $_POST['redirigir'] ?? "index.php";

    if (empty($contenido)) {
        $_SESSION['flash_message'] = "El contenido es obligatorio.";
        $_SESSION['flash_type'] = "error";
        header("Location: " . BASE_URL . $redirigir);
        exit();
    }

    $pubObj = new Publicacion();
    if ($pubObj->crear($_SESSION['usuario_id'], $contenido)) {
        $_SESSION['flash_message'] = "Publicación creada exitosamente.";
        $_SESSION['flash_type'] = "success";
        header("Location: " . BASE_URL . $redirigir);
        exit();
    } else {
        $_SESSION['flash_message'] = "Error al crear la publicación.";
        $_SESSION['flash_type'] = "error";
        header("Location: " . BASE_URL . $redirigir);
        exit();
    }
}

header("Location: " . BASE_URL . "index.php");
exit();
