<?php

require_once  "../clases/Database.php";
require_once  "../clases/Usuario.php";
require_once  "../clases/Publicacion.php";
require_once  "../includes/config.php";
require_once  "../includes/auth.php";
requireModeradorOAdmin();
require_once  "../includes/header.php";
require_once  "../includes/nav.php";

$pubObj = new Publicacion();
$usuarioObj = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ocultar_pub'])) {
        $id_pub = (int)$_POST['id_publicacion'];
        $oculta = (int)$_POST['estado_oculta'];
        $pubObj->cambiarVisibilidad($id_pub, $oculta);
        $_SESSION['flash_message'] = $oculta ? "Publicación ocultada al público." : "Publicación visible nuevamente.";
    } elseif (isset($_POST['banear_usuario_mod'])) {
        $id_user = (int)$_POST['id_usuario'];
        $usuarioObj->cambiarEstadoBaneo($id_user, 1); // Solo banea (1)
        $_SESSION['flash_message'] = "Usuario suspendido (acción de moderador).";
    }
    $_SESSION['flash_type'] = "success";
    header("Location: " . BASE_URL . "moderar.php");
    exit;
}

$publicaciones = $pubObj->obtenerTodasModeracion();
$usuarios = $usuarioObj->obtenerTodos();
// Nota: filtrar en PHP o agregar método específico si se requiere excluir admins
?>
<h2>Moderación de Contenido</h2>
<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="flash-message flash-<?php echo $_SESSION['flash_type'] ?? 'success'; ?>">
        <?php
        echo htmlspecialchars($_SESSION['flash_message']);
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        ?>
    </div>
<?php endif; ?>

<h3>Publicaciones</h3>
<table border="1">
    <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Estado</th>
        <th>Acción</th>
    </tr>
    <?php foreach ($publicaciones as $pub): ?>
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
    <?php endforeach; ?>
</table>

<h3>Usuarios (Banear para el público)</h3>
<table border="1">
    <tr>
        <th>Email</th>
        <th>Usuario</th>
        <th>Acción</th>
    </tr>
    <?php foreach ($usuarios as $user): ?>
        <?php if ($user['rol'] !== 'admin'): ?>
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
        <?php endif; ?>
    <?php endforeach; ?>
</table>
<?php require_once  "../includes/footer.php"; ?>