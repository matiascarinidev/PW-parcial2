<?php
require_once 'conexion.php';
require_once 'auth.php';
require_moderador_o_admin();
require_once 'header.php';
require_once 'nav.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_datos'])) {
    $id_usuario = (int)$_POST['id_usuario'];
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if (empty($nombre) || empty($apellido) || empty($email)) {
        $_SESSION['flash_message'] = "Nombre, apellido y email son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } else {
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $id_usuario);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Datos del usuario actualizados.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Error al actualizar datos.";
            $_SESSION['flash_type'] = "error";
        }
        $stmt->close();
    }
    header("Location: moderar.php");
    exit;
}

$resultado = $conexion->query("SELECT id, usuario, nombre, apellido, email, telefono FROM usuarios");
?>
<h2>Panel de Moderación - Edición de Datos</h2>
<?php while ($fila = $resultado->fetch_assoc()): ?>
    <form method="POST" style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <input type="hidden" name="id_usuario" value="<?php echo $fila['id']; ?>">
        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($fila['usuario']); ?></p>
        <label>Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($fila['nombre']); ?>" required></label><br>
        <label>Apellido: <input type="text" name="apellido" value="<?php echo htmlspecialchars($fila['apellido']); ?>" required></label><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($fila['email']); ?>" required></label><br>
        <label>Teléfono: <input type="text" name="telefono" value="<?php echo htmlspecialchars($fila['telefono']); ?>"></label><br>
        <button type="submit" name="editar_datos">Guardar cambios</button>
    </form>
<?php endwhile; ?>
<?php require_once 'footer.php'; ?>