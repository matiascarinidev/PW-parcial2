<?php
require_once "conexion.php";
require_once "header.php";
require_once "nav.php";

// Validar que haya sesión iniciada
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// 1. Obtener datos del perfil actual
$stmt_perfil = $conexion->prepare("SELECT usuario, nombre, apellido, biografia FROM perfiles WHERE usuario_id = ?");
$stmt_perfil->bind_param("i", $usuario_id);
$stmt_perfil->execute();
$perfil = $stmt_perfil->get_result()->fetch_assoc();
$stmt_perfil->close();

// Si no hay perfil, redirigir a crearlo
if (!$perfil) {
    header("Location: crear_perfil.php");
    exit();
}

// 2. Obtener publicaciones del usuario (Orden: más reciente a más antigua)
$stmt_pubs = $conexion->prepare("SELECT id_publicacion, titulo, contenido, estado, fecha_creacion FROM publicaciones WHERE autor_id = ? ORDER BY fecha_creacion DESC");
$stmt_pubs->bind_param("i", $usuario_id);
$stmt_pubs->execute();
$resultado_pubs = $stmt_pubs->get_result();

// 3. Obtener lista de amigos (solo los que tienen estado 'aceptada')
// Se hace un UNION para buscar en ambas direcciones de la relación
$stmt_amigos = $conexion->prepare("
    SELECT perf.usuario 
    FROM amistades a
    JOIN perfiles perf ON a.amigo_id = perf.usuario_id
    WHERE a.usuario_id = ? AND a.estado = 'aceptada'
    
    UNION
    
    SELECT perf.usuario 
    FROM amistades a
    JOIN perfiles perf ON a.usuario_id = perf.usuario_id
    WHERE a.amigo_id = ? AND a.estado = 'aceptada'
");
$stmt_amigos->bind_param("ii", $usuario_id, $usuario_id);
$stmt_amigos->execute();
$resultado_amigos = $stmt_amigos->get_result();
?>

<h2>Mi Panel: <?php echo htmlspecialchars($perfil['usuario']); ?></h2>
<p><strong>Nombre completo:</strong> <?php echo htmlspecialchars($perfil['nombre'] . " " . $perfil['apellido']); ?></p>
<p><strong>Biografía:</strong> <?php echo nl2br(htmlspecialchars($perfil['biografia'] ?? 'Sin biografía')); ?></p>

<div style="display: flex; gap: 20px;">
    <!-- Sección de Publicaciones -->
    <div style="flex: 2;">
        <h3>Mis Publicaciones</h3>
        <?php if ($resultado_pubs->num_rows > 0): ?>
            <?php while ($pub = $resultado_pubs->fetch_assoc()): ?>
                <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <h4><?php echo htmlspecialchars($pub['titulo']); ?></h4>
                    <p><?php echo nl2br(htmlspecialchars($pub['contenido'])); ?></p>
                    <small>
                        Fecha: <?php echo $pub['fecha_creacion']; ?> |
                        Estado: <strong><?php echo htmlspecialchars($pub['estado']); ?></strong>
                    </small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No tienes publicaciones aún. <a href="crear_publicacion.php">Crea una aquí</a>.</p>
        <?php endif; ?>
    </div>
    <!-- Sección de Amigos -->
    <div style="flex: 1;">
        <h3>Mis Amigos</h3>
        <?php if ($resultado_amigos->num_rows > 0): ?>
            <ul>
                <?php while ($amigo = $resultado_amigos->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($amigo['usuario']); ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No tienes amigos agregados aún.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$stmt_pubs->close();
$stmt_amigos->close();
require_once "footer.php";
?>