<?php

require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Publicacion.php";
require_once "includes/header.php";
require_once "includes/nav.php";

$pubObj = new Publicacion();
$publicaciones = $pubObj->obtenerPublicas();
?>
<h2>Publicaciones Recientes</h2>
<?php foreach ($publicaciones as $pub): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <h3><?php echo htmlspecialchars($pub['titulo']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($pub['contenido'])); ?></p>
        <small>Publicado por <?php echo htmlspecialchars($pub['usuario']); ?> el <?php echo $pub['fecha_creacion']; ?></small>
    </div>
<?php endforeach; ?>
<?php require_once "includes/footer.php"; ?>