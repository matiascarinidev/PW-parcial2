<?php
require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Usuario.php";
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

                // Redirección corregida
                if ($fila['perfil_completo'] == 0) {
                    header("Location: " . BASE_URL . "crear_perfil.php");
                } else {
                    header("Location: " . BASE_URL . "index.php");
                }
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

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="card flash-<?php echo $_SESSION['flash_type'] ?? 'error'; ?>" style="background: var(--danger-color); color: white; text-align: center; margin-bottom: 1rem;">
        <?php
        echo htmlspecialchars($_SESSION['flash_message']);
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        ?>
    </div>
<?php endif; ?>

<?php require "componentes/form_login.php"; ?>

<?php require_once "includes/footer.php"; ?>