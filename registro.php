<?php

require_once "conexion.php";
require_once "header.php";
require_once "nav.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $usuario = trim($_POST['usuario'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $password_confirm = trim($_POST['password_confirm'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $nombre = trim($_POST['nombre'] ?? "");
    $apellido = trim($_POST['apellido'] ?? "");
    $telefono = trim($_POST['telefono'] ?? "");

    if (empty($usuario) || empty($password) || empty($email) || empty($nombre) || empty($apellido)) {
        $_SESSION['flash_message'] = "Usuario, contraseña, email, nombre y apellido son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } elseif ($password !== $password_confirm) {
        $_SESSION['flash_message'] = "Las contraseñas no coinciden.";
        $_SESSION['flash_type'] = "error";
    } elseif (strlen($password) < 8 || strlen($password) > 72) {
        $_SESSION['flash_message'] = "La contraseña debe tener entre 8 y 72 caracteres.";
        $_SESSION['flash_type'] = "error";
    } else {
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ? OR email = ?");
        $stmt->bind_param("ss", $usuario, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['flash_message'] = "El nombre de usuario o el correo electrónico ya están en uso.";
            $_SESSION['flash_type'] = "error";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $conexion->prepare("INSERT INTO usuarios (usuario, password, email, nombre, apellido, telefono, rol) VALUES (?, ?, ?, ?, ?, ?, 'usuario')");
            $stmt_insert->bind_param("ssssss", $usuario, $password_hash, $email, $nombre, $apellido, $telefono);

            if ($stmt_insert->execute()) {
                $_SESSION['flash_message'] = "Registro exitoso. Ahora puedes iniciar sesión.";
                $_SESSION['flash_type'] = "success";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['flash_message'] = "Error al registrar el usuario. Por favor, inténtalo de nuevo.";
                $_SESSION['flash_type'] = "error";
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
?>
<h2>Registro de Usuario</h2>
<form method="POST">
    <label for="">USUARIO: <input type="text" name="usuario" required></label><br>
    <label for="">CONTRASEÑA: <input type="password" name="password" required></label><br>
    <label for="">REPETIR CONTRASEÑA: <input type="password" name="password_confirm" required></label><br>
    <label for="">EMAIL: <input type="email" name="email" required></label><br>
    <label for="">NOMBRE: <input type="text" name="nombre" required></label><br>
    <label for="">APELLIDO: <input type="text" name="apellido" required></label><br>
    <label for="">TELÉFONO: <input type="text" name="telefono"></label><br>
    <input type="submit" value="Registrarse">
</form>

<?php require_once "footer.php"; ?>