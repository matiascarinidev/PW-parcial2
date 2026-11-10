<?php

require_once "conexion.php";
require_once "header.php";
require_once "nav.php";

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['flash_message'] = "Debes iniciar sesión para publicar.";
    $_SESSION['flash_type'] = "error";
    header("Location: login.php");
    exit();
}

// Verificar si el perfil está completo consultando la base de datos
$stmt_check_perfil = $conexion->prepare("SELECT perfil_completo FROM usuarios WHERE id = ?");
$stmt_check_perfil->bind_param("i", $_SESSION['usuario_id']);
$stmt_check_perfil->execute();
$res_perfil = $stmt_check_perfil->get_result()->fetch_assoc();
$stmt_check_perfil->close();

if (!$res_perfil || $res_perfil['perfil_completo'] == 0) {
    $_SESSION['flash_message'] = "Debes completar tu perfil antes de publicar.";
    $_SESSION['flash_type'] = "error";
    header("Location: crear_perfil.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $titulo = trim($_POST['titulo'] ?? "");
    $contenido = trim($_POST['contenido'] ?? "");

    if (empty($titulo) || empty($contenido)) {
        $_SESSION['flash_message'] = "Título y contenido son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } else {
        $stmt = $conexion->prepare("INSERT INTO publicaciones (autor_id, titulo, contenido, estado) VALUES (?, ?, ?, 'pendiente')");
        $stmt->bind_param("iss", $_SESSION['usuario_id'], $titulo, $contenido);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Publicación enviada para revisión.";
            $_SESSION['flash_type'] = "success";
            header("Location: index.php");
            exit();
        }
        $stmt->close();
    }
}
?>
<h2>Crear Publicación</h2>
<form method="POST">
    <label>TÍTULO: <input type="text" name="titulo" required></label><br>
    <label>CONTENIDO: <textarea name="contenido" required></textarea></label><br>
    <button type="submit">Publicar</button>
</form>
<?php require_once "footer.php"; ?>