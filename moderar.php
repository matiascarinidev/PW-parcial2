<?php
require_once 'conexion.php';
require_once 'auth.php';
require_moderador_o_admin(); // Permite admin y moderador
require_once 'header.php';
require_once 'nav.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ocultar_pub'])) {
        $id_pub = (int)$_POST['id_publicacion'];
        $oculta = (int)$_POST['estado_oculta'];
        $stmt = $conexion->prepare("UPDATE publicaciones SET oculta = ? WHERE id_publicacion = ?");
        $stmt->bind_param("ii", $oculta, $id_pub);
        $stmt->execute();
        $_SESSION['flash_message'] = $oculta ? "Publicación ocultada al público." : "Publicación visible nuevamente.";
    } elseif (isset($_POST['banear_usuario_mod'])) {
        // Moderador solo puede banear (no desbanear ni borrar)
        $id_user = (int)$_POST['id_usuario'];
        $stmt = $conexion->prepare("UPDATE usuarios SET baneado = 1 WHERE id = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $_SESSION['flash_message'] = "Usuario suspendido (acción de moderador).";
    }
    $_SESSION['flash_type'] = "success";
    header("Location: moderar.php");
    exit;
}

// 1. Listar publicaciones
$res_pubs = $conexion->query("SELECT p.id_publicacion, p.titulo, p.oculta, perf.usuario 
                              FROM publicaciones p JOIN perfiles perf ON p.autor_id = perf.usuario_id");
// 2. Listar usuarios (solo para banear)
$res_users = $conexion->query("SELECT u.id, u.email, perf.usuario FROM usuarios u LEFT JOIN perfiles perf ON u.id = perf.usuario_id WHERE u.rol != 'admin'");
?>
<h2>Moderación de Contenido</h2>
<h3>Publicaciones</h3>
<table border="1">
    <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Estado</th>
        <th>Acción</th>
    </tr>
    <?php while ($pub = $res_pubs->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($pub['titulo']); ?></td>
            <td><?php echo htmlspecialchars($pub['usuario']); ?></td>
            <td><?php echo $pub['oculta'] ? 'OCULTA' : 'Visible'; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_publicacion" value="<?php echo $pub['id_publicacion']; ?>">
                    <input type="hidden" name="estado_oculta" value="<?php echo $pub['oculta'] ? 0 : 1; ?>">
                    <button type="submit" name="ocultar_pub"><?php echo $pub['oculta'] ? 'Mostrar' : 'Ocultar'; ?></button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<h3>Usuarios (Banear para el público)</h3>
<table border="1">
    <tr>
        <th>Email</th>
        <th>Usuario</th>
        <th>Acción</th>
    </tr>
    <?php while ($user = $res_users->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['usuario'] ?? 'Sin perfil'); ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_usuario" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="banear_usuario_mod">Banear</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<?php require_once 'footer.php'; ?>