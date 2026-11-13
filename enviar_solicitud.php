<?php
require_once 'includes/config.php';
require_once 'clases/Database.php';
require_once 'clases/Amistad.php';
require_once 'includes/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_amigo'])) {
    $amigo_id = (int)$_POST['id_amigo'];
    $mi_id = $_SESSION['usuario_id'];

    if ($amigo_id !== $mi_id) {
        $amistadObj = new Amistad();
        if ($amistadObj->enviarSolicitud($mi_id, $amigo_id)) {
            $_SESSION['flash_message'] = "Solicitud de amistad enviada.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "No se pudo enviar la solicitud.";
            $_SESSION['flash_type'] = "error";
        }
    }
}

$param = $_POST['u'] ?? $_SESSION['usuario_email'] ?? '';
$tab_actual = $_POST['tab_actual'] ?? 'publicaciones';
header("Location: " . BASE_URL . "perfil.php?u=" . urlencode($param) . "&tab=" . urlencode($tab_actual));
exit();
