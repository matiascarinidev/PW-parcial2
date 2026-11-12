<?php

require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Publicacion.php";
require_once "includes/header.php";
require_once "includes/nav.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $titulo = trim($_POST['titulo'] ?? "Estado");
    $contenido = trim($_POST['contenido'] ?? "");
    $redirigir = $_POST['redirigir'] ?? "index.php"; 

    if (empty($contenido)) {
        $_SESSION['flash_message'] = "El contenido es obligatorio.";
        $_SESSION['flash_type'] = "error";
    } else {
        $pubObj = new Publicacion();
        if ($pubObj->crear($_SESSION['usuario_id'], $titulo, $contenido)) {
            $_SESSION['flash_message'] = "Publicación creada. Pendiente de revisión.";
            $_SESSION['flash_type'] = "success";

            header("Location: " . BASE_URL  . $redirigir);
            exit();
        }
    }
}
?>
<h2>Crear Publicación</h2>
<form method="POST">
    <label>TÍTULO: <input type="text" name="titulo" placeholder="Opcional" value="Estado"></label><br>
    <label>CONTENIDO: <textarea name="contenido" required></textarea></label><br>
    <button type="submit">Publicar</button>
</form>
<?php require_once "includes/footer.php"; ?>