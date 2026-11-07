<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "conexion.php";
require_once "header.php";
require_once "nav.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $usuario = trim($_POST['usuario'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $recordarme = isset($_POST['recordarme']);

    if (empty($usuario) || empty($password)) {
        $_SESSION['flash_message'] = "Todos los campos son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } else {
        $stmt = $conexion->prepare("SELECT id, usuario, password FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            if (password_verify($password, $fila['password'])) {

                $_SESSION['usuario_id'] = $fila['id'];
                $_SESSION['usuario_nombre'] = $fila['usuario'];

                if ($recordarme) {
                    setcookie("usuario_recordado", $fila['usuario'], time() + (86400 * 30), "/");
                } else {
                    setcookie("usuario_recordado", "", time() - 3600, "/");
                }

                $_SESSION['flash_message'] = "Bienvenido, " . htmlspecialchars($fila['usuario']);
                $_SESSION['flash_type'] = "success";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['flash_message'] = "Usuario o contraseña incorrectas.";
                $_SESSION['flash_type'] = "error";
            }
        } else {
            $_SESSION['flash_message'] = "Usuario no encontrado.";
            $_SESSION['flash_type'] = "error";
        }
        $stmt->close();
    }
}
$usuario_recordado = $_COOKIE['usuario_recordado'] ?? '';
?>
<h2>Iniciar sesión,</h2>
<form method="POST">
    <label for="">USUARIO: <input type="text" name="usuario" value="<?php echo htmlspecialchars($usuario_recordado); ?>" required></label><br>
    <label for="">CONTRASEÑA: <input type="password" name="password" required></label><br>
    <label for="">Recordarme: <input type="checkbox" name="recordarme"></label><br>
    <button type="submit">Iniciar sesión</button>
</form>
<?php require_once "footer.php"; ?>