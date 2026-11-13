<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../clases/Database.php';
require_once __DIR__ . '/../clases/Publicacion.php';
require_once __DIR__ . '/../includes/auth.php';
requireModeradorOAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_publicacion'])) {
    $id_pub = (int)$_POST['id_publicacion'];
    $nuevo_estado = $_POST['nuevo_estado'] === 'oculta' ? 'oculta' : 'visible';

    $pubObj = new Publicacion();
    if ($pubObj->cambiarVisibilidad($id_pub, $nuevo_estado)) {
        $_SESSION['flash_message'] = $nuevo_estado === 'oculta' ? "Publicación ocultada." : "Publicación visible.";
        $_SESSION['flash_type'] = "success";
    }
}

$redirigir = $_POST['redirigir'] ?? 'index.php';
header("Location: " . BASE_URL . $redirigir);
exit();
