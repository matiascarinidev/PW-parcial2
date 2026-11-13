<?php
require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Publicacion.php";
require_once "includes/auth.php";
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_publicacion'])) {
    $id_pub = (int)$_POST['id_publicacion'];
    $pubObj = new Publicacion();
    $pubObj->toggleLike($id_pub, $_SESSION['usuario_id']);
}

$redirigir = $_POST['redirigir'] ?? 'index.php';
header("Location: " . BASE_URL . $redirigir);
exit();
