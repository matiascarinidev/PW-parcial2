<?php


require_once "conexion.php";
require_once "header.php";
require_once "nav.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $usuario = trim($_POST['usuario'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $email = trim($_POST['email'] ?? "");

    if (empty($usuario) || empty($password) || empty($email)) {
        $_SESSION['flash_message'] = "Todos los campos son obligatorios.";
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
            $stmt_insert = $conexion->prepare("INSERT INTO usuarios (usuario, password, email) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $usuario, $password_hash, $email);

            if ($stmt_insert->execute()) {
                $_SESSION['flash_message'] = "Registro exitoso. Ahora puedes iniciar sesión.";
                $_SESSION['flash_type'] = "success";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['flash_message'] = "Error al registrar el usuario. Por favor, inténtalo de nuevo.";
                $_SESSION['flash_type'] = "error";
            }
            $stmt->close();
        }
    }
}
?>
<h2>Registro de Usuario</h2>
<form method="POST">
    <label for="">USUARIO: <input type="text" name="usuario" required></label><br>
    <label for="">CONTRASEÑA: <input type="password" name="password" required></label><br>
    <label for="">EMAIL: <input type="email" name="email" required></label><br>
    <input type="submit" value="Registrarse">
</form>

<?php require_once "footer.php";

?>