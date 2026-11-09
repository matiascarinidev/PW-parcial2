<?php
// nav.php
require_once 'conexion.php';
if (!isset($tema)) {
    $tema = $_COOKIE['tema'] ?? 'claro';
}
?>
<nav>
    <a href="index.php">Inicio</a>
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> (<?php echo htmlspecialchars($_SESSION['usuario_rol']); ?>)</span>
        <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
            <a href="admin.php">Admin</a>
        <?php endif; ?>
        <?php if (in_array($_SESSION['usuario_rol'], ['admin', 'moderador'])): ?>
            <a href="moderar.php">Moderar</a>
        <?php endif; ?>
        <a href="logout.php">Cerrar Sesión</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="registro.php">Registro</a>
    <?php endif; ?>

    <form method="POST" action="cambiar_tema.php" style="display:inline;">
        <button type="submit" name="tema" value="<?php echo $tema === 'claro' ? 'oscuro' : 'claro'; ?>">
            Cambiar a <?php echo $tema === 'claro' ? 'Oscuro' : 'Claro'; ?>
        </button>
    </form>
</nav>