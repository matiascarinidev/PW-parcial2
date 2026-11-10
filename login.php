<?php
require_once "conexion.php";
require_once "header.php";
require_once "nav.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");

    if (empty($email) || empty($password)) {
        $_SESSION['flash_message'] = "Todos los campos son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } else {
        $stmt = $conexion->prepare("SELECT id, email, password, rol, perfil_completo FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            if ($fila['baneado'] == 1) {
                $_SESSION['flash_message'] = "Tu cuenta ha sido suspendida.";
                $_SESSION['flash_type'] = "error";
            } elseif (password_verify($password, $fila['password'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $fila['id'];
                $_SESSION['usuario_email'] = $fila['email'];
                $_SESSION['usuario_rol'] = $fila['rol'];

                if ($fila['perfil_completo'] == 0) {
                    header("Location: crear_perfil.php");
                } else {
                    header("Location: perfil.php");
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
    $stmt->close();
}

?>
<h2>Iniciar sesión</h2>
<form method="POST">
    <label>EMAIL: <input type="email" name="email" required></label><br>
    <label>CONTRASEÑA: <input type="password" name="password" required></label><br>
    <button type="submit">Iniciar sesión</button>
</form>
<?php require_once "footer.php"; ?>