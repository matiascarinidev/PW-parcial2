<?php
require_once 'conexion.php';
require_once 'auth.php';
require_admin(); // Función que verifica $_SESSION['usuario_rol'] === 'admin'
require_once 'header.php';
require_once 'nav.php';

// Procesar acciones del Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cambiar_rol'])) {
        $id_user = (int)$_POST['id_usuario'];
        $nuevo_rol = $_POST['nuevo_rol'] ?? 'usuario';
        if (in_array($nuevo_rol, ['admin', 'moderador', 'usuario'])) {
            $stmt = $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
            $stmt->bind_param("si", $nuevo_rol, $id_user);
            $stmt->execute();
            $_SESSION['flash_message'] = "Rol actualizado.";
        }
    } elseif (isset($_POST['banear'])) {
        $id_user = (int)$_POST['id_usuario'];
        $estado_ban = (int)$_POST['estado_ban'];
        $stmt = $conexion->prepare("UPDATE usuarios SET baneado = ? WHERE id = ?");
        $stmt->bind_param("ii", $estado_ban, $id_user);
        $stmt->execute();
        $_SESSION['flash_message'] = $estado_ban ? "Usuario baneado." : "Usuario desbaneado.";
    } elseif (isset($_POST['eliminar_cuenta'])) {
        $id_user = (int)$_POST['id_usuario'];
        // El ON DELETE CASCADE en la BD eliminará su perfil y publicaciones
        $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $_SESSION['flash_message'] = "Cuenta de usuario eliminada del sistema.";
    } elseif (isset($_POST['editar_perfil_admin'])) {
        $id_perfil = (int)$_POST['id_perfil'];
        $usuario = trim($_POST['usuario']);
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $stmt = $conexion->prepare("UPDATE perfiles SET usuario = ?, nombre = ?, apellido = ? WHERE id_perfil = ?");
        $stmt->bind_param("sssi", $usuario, $nombre, $apellido, $id_perfil);
        $stmt->execute();
        $_SESSION['flash_message'] = "Perfil modificado por administrador.";
    }
    $_SESSION['flash_type'] = "success";
    header("Location: admin.php");
    exit;
}

// Consulta uniendo usuarios y perfiles
$query = "SELECT u.id, u.email, u.rol, u.baneado, p.id_perfil, p.usuario, p.nombre, p.apellido 
          FROM usuarios u LEFT JOIN perfiles p ON u.id = p.usuario_id";
$resultado = $conexion->query($query);
?>
<h2>Panel de Administración (Usuarios)</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>usuario/Perfil</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo htmlspecialchars($fila['email']); ?></td>
            <td>
                <?php if ($fila['id_perfil']): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_perfil" value="<?php echo $fila['id_perfil']; ?>">
                        <input type="text" name="usuario" value="<?php echo htmlspecialchars($fila['usuario']); ?>" required size="10">
                        <input type="text" name="nombre" value="<?php echo htmlspecialchars($fila['nombre']); ?>" required size="10">
                        <input type="text" name="apellido" value="<?php echo htmlspecialchars($fila['apellido']); ?>" required size="10">
                        <button type="submit" name="editar_perfil_admin">Editar</button>
                    </form>
                <?php else: echo "Sin perfil";
                endif; ?>
            </td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_usuario" value="<?php echo $fila['id']; ?>">
                    <select name="nuevo_rol">
                        <option value="usuario" <?php if ($fila['rol'] == 'usuario') echo 'selected'; ?>>Usuario</option>
                        <option value="moderador" <?php if ($fila['rol'] == 'moderador') echo 'selected'; ?>>Moderador</option>
                        <option value="admin" <?php if ($fila['rol'] == 'admin') echo 'selected'; ?>>Admin</option>
                    </select>
                    <button type="submit" name="cambiar_rol">Cambiar Rol</button>
                </form>
            </td>
            <td><?php echo $fila['baneado'] ? 'BANEADO' : 'Activo'; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_usuario" value="<?php echo $fila['id']; ?>">
                    <input type="hidden" name="estado_ban" value="<?php echo $fila['baneado'] ? 0 : 1; ?>">
                    <button type="submit" name="banear"><?php echo $fila['baneado'] ? 'Desbanear' : 'Banear'; ?></button>
                </form>
                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar cuenta permanentemente?');">
                    <input type="hidden" name="id_usuario" value="<?php echo $fila['id']; ?>">
                    <button type="submit" name="eliminar_cuenta">Borrar Cuenta</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<?php require_once 'footer.php'; ?>