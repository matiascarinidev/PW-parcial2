<?php
require_once "conexion.php";
require_once "header.php";
require_once "nav.php";

$stmt = $conexion->query("
    SELECT p.titulo, p.contenido, p.fecha_creacion, perf.usuario 
    FROM publicaciones p 
    JOIN perfiles perf ON p.autor_id = perf.usuario_id 
    JOIN usuarios u ON p.autor_id = u.id
    WHERE p.oculta = 0 AND u.baneado = 0 
    ORDER BY p.fecha_creacion DESC
");
?>
<h2>Publicaciones Recientes</h2>
<?php while ($pub = $stmt->fetch_assoc()): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <h3><?php echo htmlspecialchars($pub['titulo']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($pub['contenido'])); ?></p>
        <small>Publicado por <?php echo htmlspecialchars($pub['usuario']); ?> el <?php echo $pub['fecha_creacion']; ?></small>
    </div>
<?php endwhile; ?>
<?php require_once "footer.php"; ?>