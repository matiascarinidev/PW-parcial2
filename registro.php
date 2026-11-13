<?php
require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Usuario.php";
require_once "includes/header.php";
require_once "includes/nav.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $password_confirm = trim($_POST['password_confirm'] ?? "");

    if (empty($email) || empty($password) || empty($password_confirm)) {
        $_SESSION['flash_message'] = "Todos los campos son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } elseif ($password !== $password_confirm) {
        $_SESSION['flash_message'] = "Las contraseñas no coinciden.";
        $_SESSION['flash_type'] = "error";
    } elseif (strlen($password) < 8 || strlen($password) > 25) {
        $_SESSION['flash_message'] = "La contraseña debe tener entre 8 y 25 caracteres.";
        $_SESSION['flash_type'] = "error";
    } else {
        $usuarioObj = new Usuario();

        if ($usuarioObj->existeEmail($email)) {
            $_SESSION['flash_message'] = "El correo electrónico ya está en uso.";
            $_SESSION['flash_type'] = "error";
        } else {
            $nuevo_id = $usuarioObj->crear($email, $password);

            if ($nuevo_id) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $nuevo_id;
                $_SESSION['usuario_email'] = $email;
                $_SESSION['usuario_rol'] = 'usuario';

                $_SESSION['flash_message'] = "Registro exitoso. Completa tu perfil.";
                $_SESSION['flash_type'] = "success";
                header("Location: " . BASE_URL . "crear_perfil.php");
                exit();
            } else {
                $_SESSION['flash_message'] = "Error al registrar en la base de datos.";
                $_SESSION['flash_type'] = "error";
            }
        }
    }
}
?>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="card flash-<?php echo $_SESSION['flash_type'] ?? 'error'; ?>" style="background: <?php echo ($_SESSION['flash_type'] ?? '') === 'error' ? 'var(--danger-color)' : 'var(--success-color)'; ?>; color: white; text-align: center; margin-bottom: 1rem;">
        <?php
        echo htmlspecialchars($_SESSION['flash_message']);
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        ?>
    </div>
<?php endif; ?>

<?php require "componentes/form_registro.php"; ?>

<?php require_once "includes/footer.php"; ?>