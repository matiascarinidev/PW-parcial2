<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../clases/Database.php';
require_once __DIR__ . '/../clases/Usuario.php';
require_once __DIR__ . '/../clases/Perfil.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/nav.php';

$usuarioObj = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cambiar_rol'])) {
        $id_user = (int)$_POST['id_usuario'];
        $nuevo_rol = $_POST['nuevo_rol'] ?? 'usuario';
        if (in_array($nuevo_rol, ['admin', 'moderador', 'usuario'])) {
            $usuarioObj->actualizarRol($id_user, $nuevo_rol);
            $_SESSION['flash_message'] = "Rol actualizado correctamente.";
            $_SESSION['flash_type'] = "success";
        }
    } elseif (isset($_POST['banear'])) {
        $id_user = (int)$_POST['id_usuario'];
        $estado_ban = (int)$_POST['estado_ban'];
        $usuarioObj->cambiarEstadoBaneo($id_user, $estado_ban);
        $_SESSION['flash_message'] = $estado_ban ? "Usuario baneado." : "Usuario desbaneado.";
        $_SESSION['flash_type'] = "success";
    } elseif (isset($_POST['eliminar_cuenta'])) {
        $id_user = (int)$_POST['id_usuario'];
        $usuarioObj->eliminar($id_user);
        $_SESSION['flash_message'] = "Cuenta de usuario eliminada del sistema.";
        $_SESSION['flash_type'] = "success";
    }

    header("Location: " . BASE_URL . "admin/admin.php");
    exit;
}

$usuarios = $usuarioObj->obtenerTodos();
?>

<main style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <h2>Panel de Administración</h2>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="flash-message flash-<?php echo $_SESSION['flash_type'] ?? 'success'; ?>" style="padding: 10px; margin-bottom: 15px; border-radius: 4px; <?php echo ($_SESSION['flash_type'] ?? '') === 'error' ? 'background: #f8d7da; color: #721c24;' : 'background: #d4edda; color: #155724;'; ?>">
            <?php
            echo htmlspecialchars($_SESSION['flash_message']);
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            ?>
        </div>
    <?php endif; ?>

    <?php require __DIR__ . '/../componentes/tabla_admin_usuarios.php'; ?>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>