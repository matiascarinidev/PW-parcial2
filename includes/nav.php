<?php
require_once __DIR__ . '/config.php';
if (!isset($tema)) {
    $tema = $_COOKIE['tema'] ?? 'claro';
}
?>
<nav class="navbar">
    <div class="navbar-links">
        <a href="<?php echo BASE_URL; ?>index.php" class="font-bold text-lg" style="color: var(--primary-color);">Foro</a>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="<?php echo BASE_URL; ?>perfil.php">Mi Panel</a>
            <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                <a href="<?php echo BASE_URL; ?>admin/admin.php">Admin</a>
            <?php endif; ?>
            <?php if (in_array($_SESSION['usuario_rol'], ['admin', 'moderador'])): ?>
                <a href="<?php echo BASE_URL; ?>admin/moderar.php">Moderar</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>login.php">Login</a>
            <a href="<?php echo BASE_URL; ?>registro.php">Registro</a>
        <?php endif; ?>
    </div>
    <div class="navbar-links">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <span class="text-muted"><?php echo htmlspecialchars($_SESSION['usuario_email']); ?></span>
            <a href="<?php echo BASE_URL; ?>logout.php" class="text-sm" style="color: var(--danger-color);">Salir</a>
        <?php endif; ?>
        <form method="POST" action="<?php echo BASE_URL; ?>cambiar_tema.php" style="display:inline;">
            <button type="submit" name="tema" value="<?php echo $tema === 'claro' ? 'oscuro' : 'claro'; ?>" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                <?php echo $tema === 'claro' ? '🌙 Oscuro' : '☀️ Claro'; ?>
            </button>
        </form>
    </div>
</nav>
<main class="max-w-4xl mx-auto p-4">