<?php
require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Publicacion.php";
require_once "includes/header.php";
require_once "includes/nav.php";

$pubObj = new Publicacion();
$rol_visitante = $_SESSION['usuario_rol'] ?? 'usuario';
$usuario_actual_id = $_SESSION['usuario_id'] ?? 0;
$publicaciones = $pubObj->obtenerPublicacionesIndex($rol_visitante, $usuario_actual_id);
?>

<h2 class="mb-4" style="text-align: center;">Feed Principal</h2>

<?php if (isset($_SESSION['usuario_id'])):
    $redirigir = "index.php";
    require "componentes/form_publicacion.php";
endif; ?>

<?php if (count($publicaciones) > 0): ?>
    <?php foreach ($publicaciones as $pub):
        $nombre_autor = $pub['usuario'];
        $redirigir = "index.php";
        require "componentes/tarjeta_publicacion.php";
    endforeach; ?>
<?php else: ?>
    <p class="text-muted" style="text-align: center;">No hay publicaciones para mostrar.</p>
<?php endif; ?>

<?php require_once "includes/footer.php"; ?>