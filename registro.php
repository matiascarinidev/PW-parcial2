<?php
require_once "conexion.php";
require_once "header.php";
require_once "nav.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $password_confirm = trim($_POST['password_confirm'] ?? "");

    if (empty($email) || empty($password)) {
        $_SESSION['flash_message'] = "Todos los campos son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } elseif ($password !== $password_confirm) {
        $_SESSION['flash_message'] = "Las contraseñas no coinciden.";
        $_SESSION['flash_type'] = "error";
    } elseif (strlen($password) < 8 || strlen($password) > 25) {
        $_SESSION['flash_message'] = "La contraseña debe tener entre 8 y 25 caracteres.";
        $_SESSION['flash_type'] = "error";
    } else {
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['flash_message'] = "El correo electrónico ya está en uso.";
            $_SESSION['flash_type'] = "error";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $conexion->prepare("INSERT INTO usuarios (email, password, rol) VALUES (?, ?, 'usuario')");
            $stmt_insert->bind_param("ss", $email, $password_hash);
            if ($stmt_insert->execute()) {
                $_SESSION['flash_message'] = "Registro exitoso. Ahora puedes iniciar sesión.";
                $_SESSION['flash_type'] = "success";
                header("Location: login.php");
                exit();
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
?>
<h2>Registro de Usuario</h2>
<form method="POST">
    <label>EMAIL: <input type="email" name="email" required></label><br>
    <label>CONTRASEÑA: <input type="password" name="password" maxlength="25" required></label><br>
    <label>REPETIR CONTRASEÑA: <input type="password" name="password_confirm" maxlength="25" required></label><br>
    <input type="submit" value="Registrarse">
</form>
<?php require_once "footer.php"; ?>