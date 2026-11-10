<?php

require_once "conexion.php";
require_once "header.php";
require_once "nav.php";

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['flash_message'] = "Debes iniciar sesión para crear un perfil.";
    $_SESSION['flash_type'] = "error";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $usuario = trim($_POST['usuario'] ?? "");
    $nombre = trim($_POST['nombre'] ?? "");
    $apellido = trim($_POST['apellido'] ?? "");
    $telefono = trim($_POST['telefono'] ?? "");
    $biografia = trim($_POST['biografia'] ?? "");

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $usuario = trim($_POST['usuario'] ?? "");
        $nombre = trim($_POST['nombre'] ?? "");
        $apellido = trim($_POST['apellido'] ?? "");
        $telefono = trim($_POST['telefono'] ?? "");
        $biografia = trim($_POST['biografia'] ?? "");

        if (empty($usuario) || empty($nombre) || empty($apellido)) {
            $_SESSION['flash_message'] = "usuario, nombre y apellido son obligatorios.";
            $_SESSION['flash_type'] = "error";
        } else {
            // Verificar que el usuario no esté tomado
            $stmt_check = $conexion->prepare("SELECT id_perfil FROM perfiles WHERE usuario = ?");
            $stmt_check->bind_param("s", $usuario);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $_SESSION['flash_message'] = "El usuario ya está en uso.";
                $_SESSION['flash_type'] = "error";
            } else {
                $stmt = $conexion->prepare("INSERT INTO perfiles (usuario_id, usuario, nombre, apellido, telefono, biografia) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $_SESSION['usuario_id'], $usuario, $nombre, $apellido, $telefono, $biografia);

                if ($stmt->execute()) {
                    $stmt_update = $conexion->prepare("UPDATE usuarios SET perfil_completo = TRUE WHERE id = ?");
                    $stmt_update->bind_param("i", $_SESSION['usuario_id']);
                    $stmt_update->execute();
                    $stmt_update->close();

                    $_SESSION['flash_message'] = "Perfil creado exitosamente.";
                    $_SESSION['flash_type'] = "success";
                    header("Location: perfil.php");
                    exit();
                }
                $stmt->close();
            }
            $stmt_check->close();
        }
    }
}
?>
<h2>Completa tu Perfil</h2>
<form method="POST">
    <label>NICKNAME: <input type="text" name="usuario" required></label><br>
    <label>NOMBRE: <input type="text" name="nombre" required></label><br>
    <label>APELLIDO: <input type="text" name="apellido" required></label><br>
    <label>TELÉFONO: <input type="text" name="telefono"></label><br>
    <label>BIOGRAFÍA: <textarea name="biografia"></textarea></label><br>
    <button type="submit">Guardar Perfil</button>
</form>

<?php require_once "footer.php"; ?>