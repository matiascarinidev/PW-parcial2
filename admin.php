<?php
require_once "conexion.php";
require_once "auth.php";
require_admin();
require_once "header.php";
require_once "nav.php";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['cambiar_rol'])) {
    $id_usuario = (int)$_POST['id_usuario'];
    $nuevo_rol = $_POST['nuevo_rol'] ?? '';

    if (in_array($nuevo_rol, ['admin', 'moderator', 'usuario'])) {
        $stmt = $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevo_rol, $id_usuario);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Rol actualizado correctamente.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Error al actualizar el rol.";
            $_SESSION['flash_type'] = "error";
        }
        $stmt->close();
    }
    header("Location: admin.php");
    exit();
}


$resultado = $conexion->query("SELECT id, usuario, rol, nombre, apellido, email FROM usuarios");
?>
<h2>Panel de administración - Gestion de roles</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>usuario</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>-Email</th>
        <th>Rol</th>
        <th>Accion</th>
    </tr>
    <tr>
        <?php while ($fila = $resultado->fetch_assoc()) : ?>
            <td><?php echo htmlspecialchars($fila['id']); ?></td>
            <td><?php echo htmlspecialchars($fila['usuario']); ?></td>
            <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
            <td><?php echo htmlspecialchars($fila['apellido']); ?></td>
            <td><?php echo htmlspecialchars($fila['email']); ?></td>
            <td><?php echo htmlspecialchars($fila['rol']); ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_usuario" value="<?php echo $fila['id']; ?>">
                    <select name="nuevo_rol" type="text" value=<?php echo htmlspecialchars($fila['rol']); ?>>
                        <option value="usuario" <?php if ($fila['rol'] === 'usuario') echo 'selected'; ?>>Usuario</option>
                        <option value="moderator" <?php if ($fila['rol'] === 'moderador') echo 'selected'; ?>>Moderador</option>
                        <option value="admin" <?php if ($fila['rol'] === 'admin') echo 'selected'; ?>>Administrador</option>
                    </select>
                    <button type="submit" name="cambiar_rol">Cambiar Rol</button>
                </form>
            </td>
    </tr>
<?php endwhile; ?>
</table>
<?php require_once "footer.php"; ?>