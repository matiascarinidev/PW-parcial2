<?php
require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Perfil.php";
require_once "includes/header.php";
require_once "includes/nav.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $usuario = trim($_POST['usuario'] ?? "");
    $nombre = trim($_POST['nombre'] ?? "");
    $apellido = trim($_POST['apellido'] ?? "");
    $telefono = trim($_POST['telefono'] ?? "");
    $biografia = trim($_POST['biografia'] ?? "");

    if (empty($usuario) || empty($nombre) || empty($apellido)) {
        $_SESSION['flash_message'] = "Usuario, nombre y apellido son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } else {
        $perfilObj = new Perfil();
        if ($perfilObj->existeUsuario($usuario)) {
            $_SESSION['flash_message'] = "El nombre de usuario ya está en uso.";
            $_SESSION['flash_type'] = "error";
        } else {
            if ($perfilObj->crear($_SESSION['usuario_id'], $usuario, $nombre, $apellido, $telefono, $biografia)) {
                $_SESSION['flash_message'] = "Perfil creado exitosamente.";
                $_SESSION['flash_type'] = "success";
                header("Location: " . BASE_URL . "perfil.php");
                exit();
            } else {
                $_SESSION['flash_message'] = "Error al crear el perfil.";
                $_SESSION['flash_type'] = "error";
            }
        }
    }
}
?>
<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="card" style="background: var(--danger-color); color: white; text-align: center; margin-bottom: 1rem;">
        <?php echo htmlspecialchars($_SESSION['flash_message']);
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']); ?>
    </div>
<?php endif; ?>
<div class="max-w-md mx-auto">
    <?php
    $perfil = []; // Array vacío porque es nuevo
    $es_edicion = false;
    require "componentes/form_perfil.php";
    ?>
</div>
<?php require_once "includes/footer.php"; ?>