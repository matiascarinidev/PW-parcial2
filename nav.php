<?php

require_once 'conexion.php';
if (!isset($tema)) {
    $tema = $_COOKIE['tema'] ?? 'claro';
}

?>

<nav>
    <a href="index.php">Inicio</a>
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <span>Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?></span>
        <a href="logout.php">Cerrar sesión</a>
    <?php else: ?>
        <a href="login.php">Iniciar sesión</a>
        <a href="registro.php">Registrarse</a>
    <?php endif; ?>
    <form method="POST" action="cambiar_tema.php" style="display:inline;">
        <button type="submit" name="tema" value="<?php echo $tema === 'claro' ? 'oscuro' : 'claro'; ?>">
            Cambiar a <?php echo $tema === 'claro' ? 'Oscuro' : 'Claro'; ?>
        </button>
    </form>
    <div>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message flash-<?php echo $_SESSION["flash_type"] ?? "success"; ?> ">
                <?php echo htmlspecialchars($_SESSION["flash_message"]);
                unset($_SESSION["flash_message"]);
                unset($_SESSION["flash_type"]);
                ?>

            </div>
        <?php endif; ?>
    </div>
</nav>