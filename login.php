<?php

require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Usuario.php";
require_once "includes/config.php";
require_once "includes/header.php";
require_once "includes/nav.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");

    if (empty($email) || empty($password)) {
        $_SESSION['flash_message'] = "Todos los campos son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } else {
        $usuarioObj = new Usuario();
        $fila = $usuarioObj->buscarPorEmail($email);

        if ($fila) {
            if ($fila['baneado'] == 1) {
                $_SESSION['flash_message'] = "Tu cuenta ha sido suspendida.";
                $_SESSION['flash_type'] = "error";
            } elseif (password_verify($password, $fila['password'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $fila['id'];
                $_SESSION['usuario_email'] = $fila['email'];
                $_SESSION['usuario_rol'] = $fila['rol'];

                header("Location: " . BASE_URL . ($fila['perfil_completo'] == 0 ? "crear_perfil.php" : "perfil.php"));
                exit();
            } else {
                $_SESSION['flash_message'] = "Usuario o contraseña incorrectas.";
                $_SESSION['flash_type'] = "error";
            }
        } else {
            $_SESSION['flash_message'] = "Usuario no encontrado.";
            $_SESSION['flash_type'] = "error";
        }
    }
}
?>
<h2>Iniciar sesión</h2>
<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="flash-message flash-<?php echo $_SESSION['flash_type'] ?? 'success'; ?>">
        <?php
        echo htmlspecialchars($_SESSION['flash_message']);
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        ?>
    </div>
<?php endif; ?>
<form method="POST">
    <label>EMAIL: <input type="email" name="email" required></label><br>
    <label>CONTRASEÑA: <input type="password" name="password" required></label><br>
    <button type="submit">Iniciar sesión</button>
</form>
<?php require_once "includes/footer.php"; ?>