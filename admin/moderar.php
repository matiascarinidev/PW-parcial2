<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../clases/Database.php';
require_once __DIR__ . '/../clases/Publicacion.php';
require_once __DIR__ . '/../clases/Usuario.php';
require_once __DIR__ . '/../includes/auth.php';

requireModeradorOAdmin();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/nav.php';

$pubObj = new Publicacion();
$usuarioObj = new Usuario();

// Procesar acciones (cambiar estado, banear)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cambiar_estado'])) {
        $id_pub = (int)$_POST['id_publicacion'];
        $nuevo_estado = $_POST['nuevo_estado'] === 'oculta' ? 'oculta' : 'visible';
        $pubObj->cambiarVisibilidad($id_pub, $nuevo_estado);
        $_SESSION['flash_message'] = "Estado de la publicación actualizado.";
        $_SESSION['flash_type'] = "success";
    } elseif (isset($_POST['banear_usuario_mod'])) {
        $id_user = (int)$_POST['id_usuario'];
        $usuarioObj->cambiarEstadoBaneo($id_user, 1);
        $_SESSION['flash_message'] = "Usuario suspendido.";
        $_SESSION['flash_type'] = "success";
    }
    header("Location: " . BASE_URL . "admin/moderar.php");
    exit;
}

$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$publicaciones = $pubObj->obtenerTodasModeracion($busqueda);
$usuarios = $usuarioObj->obtenerTodos();
?>

<main class="p-4">
    <h2 class="mb-4">Panel de Moderación</h2>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="card" style="background: var(--success-color); color: white; text-align: center; margin-bottom: 1rem;">
            <?php
            echo htmlspecialchars($_SESSION['flash_message']);
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            ?>
        </div>
    <?php endif; ?>

    <section class="mb-4">
        <h3>Gestión de Publicaciones</h3>

        <!-- Buscador -->
        <form method="GET" action="<?php echo BASE_URL; ?>admin/moderar.php" class="flex gap-2 mb-4">
            <input type="text" name="buscar" class="input-field" placeholder="Buscar por contenido o autor..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <?php if (!empty($busqueda)): ?>
                <a href="<?php echo BASE_URL; ?>admin/moderar.php" class="btn" style="background: var(--border-color); color: var(--text-main); text-decoration: none; display: inline-flex; align-items: center;">Limpiar</a>
            <?php endif; ?>
        </form>

        <?php require __DIR__ . '/../componentes/tabla_mod_publicaciones.php'; ?>
    </section>

    <section>
        <h3>Gestión de Usuarios (Banear)</h3>
        <?php require __DIR__ . '/../componentes/tabla_mod_usuarios.php'; ?>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>