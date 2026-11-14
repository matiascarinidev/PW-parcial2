<?php
require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Perfil.php";
require_once "clases/Publicacion.php";
require_once "clases/Amistad.php";
require_once "includes/header.php";
require_once "includes/nav.php";

$tab_actual = $_GET['tab'] ?? 'publicaciones';

// Si es configuración y hay un POST, o si no viene el parámetro 'u', forzamos el perfil de la sesión
if (($tab_actual === 'configuracion' && $_SERVER['REQUEST_METHOD'] === 'POST') || !isset($_GET['u'])) {
    if (isset($_SESSION['usuario_id'])) {
        $param = $_SESSION['usuario_email']; // Buscamos por email para evitar problemas si el username cambió
    } else {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
} else {
    $param = $_GET['u'];
}

$db = Database::getInstance()->getConexion();
$stmt = $db->prepare("SELECT u.id, u.email, p.usuario, p.nombre, p.apellido, p.telefono, p.biografia FROM usuarios u LEFT JOIN perfiles p ON u.id = p.usuario_id WHERE p.usuario = ? OR u.email = ?");
$stmt->bind_param("ss", $param, $param);
$stmt->execute();
$perfil = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$perfil) die("Usuario no encontrado.");

$es_mi_perfil = (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $perfil['id']);
$rol_visitante = $_SESSION['usuario_rol'] ?? 'usuario';
$usuario_actual_id = $_SESSION['usuario_id'] ?? 0;

// --- PROCESAMIENTO DE EDICIÓN DE PERFIL ---
// Inicializamos $datos_formulario con los datos reales de la BD por defecto
$datos_formulario = $perfil;

if ($tab_actual === 'configuracion' && $es_mi_perfil && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? "");
    $nombre = trim($_POST['nombre'] ?? "");
    $apellido = trim($_POST['apellido'] ?? "");
    $telefono = trim($_POST['telefono'] ?? "");
    $biografia = trim($_POST['biografia'] ?? "");

    // Guardamos lo que el usuario escribió para mantenerlo en el formulario si hay error
    $datos_formulario['usuario'] = $usuario;
    $datos_formulario['nombre'] = $nombre;
    $datos_formulario['apellido'] = $apellido;
    $datos_formulario['telefono'] = $telefono;
    $datos_formulario['biografia'] = $biografia;

    if (empty($usuario) || empty($nombre) || empty($apellido)) {
        $_SESSION['flash_message'] = "Usuario, nombre y apellido son obligatorios.";
        $_SESSION['flash_type'] = "error";
    } else {
        $perfilObj = new Perfil();

        if ($perfilObj->existeUsuarioEnOtroPerfil($usuario, $perfil['id'])) {
            $_SESSION['flash_message'] = "El nombre de usuario ya está en uso por otra persona.";
            $_SESSION['flash_type'] = "error";
            // NO sobrescribimos $perfil, solo $datos_formulario que ya tiene lo que se escribió
        } else {
            $exito = $perfilObj->actualizarPropio($perfil['id'], $usuario, $nombre, $apellido, $telefono, $biografia);

            if ($exito) {
                $_SESSION['flash_message'] = "Perfil actualizado correctamente.";
                $_SESSION['flash_type'] = "success";
                // Redirigimos usando el NUEVO usuario
                header("Location: " . BASE_URL . "perfil.php?u=" . urlencode($usuario) . "&tab=configuracion");
                exit();
            } else {
                $_SESSION['flash_message'] = "Error inesperado al actualizar el perfil.";
                $_SESSION['flash_type'] = "error";
            }
        }
    }
}
// -------------------------------------------

$estado_amistad = null;
if (isset($_SESSION['usuario_id']) && !$es_mi_perfil) {
    $amistadObj = new Amistad();
    $estado_amistad = $amistadObj->obtenerEstadoRelacion($_SESSION['usuario_id'], $perfil['id']);
}

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 5;
$offset = ($pagina - 1) * $limite;

$pubObj = new Publicacion();
$publicaciones = [];
$total_paginas = 0;

if ($tab_actual === 'publicaciones') {
    $publicaciones = $pubObj->obtenerPublicacionesPerfil($perfil['id'], $offset, $limite, $es_mi_perfil, $rol_visitante, $usuario_actual_id);
    $total = $pubObj->contarPublicacionesPerfil($perfil['id'], $es_mi_perfil, $rol_visitante);
    $total_paginas = ceil($total / $limite);
}

// Procesar solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_solicitud'])) {
    $id_amistad = (int)$_POST['id_amistad'];
    $amistadObj = new Amistad();
    if ($_POST['accion_solicitud'] === 'aceptar') {
        $amistadObj->aceptarSolicitud($id_amistad, $_SESSION['usuario_id']);
        $_SESSION['flash_message'] = "Solicitud aceptada.";
    } else {
        $amistadObj->rechazarSolicitud($id_amistad, $_SESSION['usuario_id']);
        $_SESSION['flash_message'] = "Solicitud rechazada.";
    }
    $_SESSION['flash_type'] = "success";
    header("Location: " . BASE_URL . "perfil.php?u=" . urlencode($perfil['usuario'] ?? $perfil['email']) . "&tab=amigos");
    exit();
}

$nombre_autor = $perfil['usuario'] ?? 'Usuario';
$url_perfil = "?u=" . urlencode($perfil['usuario'] ?? $perfil['email']);

// Datos para la pestaña de amigos
if ($tab_actual === 'amigos' && $es_mi_perfil) {
    $amistadObj = new Amistad();
    $solicitudes = $amistadObj->obtenerSolicitudesPendientes($perfil['id']);
    $perfilObj = new Perfil();
    $amigos = $perfilObj->obtenerAmigos($perfil['id']);

    $resultados_busqueda = [];
    if (isset($_GET['buscar'])) {
        $busqueda = trim($_GET['buscar']);
        $stmt = $db->prepare("SELECT u.id, u.email, p.usuario FROM usuarios u LEFT JOIN perfiles p ON u.id = p.usuario_id WHERE (p.usuario LIKE ? OR u.email LIKE ?) AND u.id != ?");
        $like = "%" . $busqueda . "%";
        $stmt->bind_param("ssi", $like, $like, $perfil['id']);
        $stmt->execute();
        $resultados_busqueda = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}
?>
<div class="flex gap-4" style="align-items: flex-start; flex-wrap: wrap;">
    <div style="flex: 0 0 250px; width: 100%;">
        <?php require "componentes/perfil_sidebar.php"; ?>
    </div>
    <div style="flex: 1; min-width: 300px;">
        <?php if ($tab_actual === 'publicaciones'): ?>
            <?php if ($es_mi_perfil):
                $redirigir = "perfil.php" . $url_perfil . "&tab=publicaciones";
                require "componentes/form_publicacion.php";
            endif; ?>

            <?php if (count($publicaciones) > 0): ?>
                <?php foreach ($publicaciones as $pub):
                    $redirigir = "perfil.php" . $url_perfil . "&tab=publicaciones&pagina=" . $pagina;
                    require "componentes/tarjeta_publicacion.php";
                endforeach; ?>
            <?php else: ?>
                <p class="text-muted" style="text-align: center;">No hay publicaciones.</p>
            <?php endif; ?>

            <?php
            $url_base_paginacion = BASE_URL . "perfil.php" . $url_perfil . "&tab=publicaciones";
            require "componentes/paginacion.php";
            ?>

        <?php elseif ($tab_actual === 'amigos' && $es_mi_perfil): ?>
            <?php require "componentes/lista_amigos.php"; ?>

        <?php elseif ($tab_actual === 'configuracion' && $es_mi_perfil): ?>
            <?php
            if (isset($_SESSION['flash_message'])): ?>
                <div class="card" style="background: <?php echo ($_SESSION['flash_type'] ?? '') === 'error' ? 'var(--danger-color)' : 'var(--success-color)'; ?>; color: white; text-align: center; margin-bottom: 1rem;">
                    <?php
                    echo htmlspecialchars($_SESSION['flash_message']);
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                    ?>
                </div>
            <?php endif; ?>

            <?php
            $es_edicion = true;
            require "componentes/form_perfil.php";
            ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Amistad -->
<div id="modal-confirmar" class="hidden" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div class="card" style="margin: 10% auto; width: 300px; text-align: center;">
        <h3>Confirmar Solicitud</h3>
        <p class="mt-2 mb-4">¿Enviar solicitud a <strong id="nombre-amigo"></strong>?</p>
        <form method="POST" action="<?php echo BASE_URL; ?>enviar_solicitud.php" class="flex justify-center gap-2">
            <input type="hidden" name="id_amigo" id="id-amigo-modal">
            <input type="hidden" name="u" value="<?php echo urlencode($perfil['usuario'] ?? $perfil['email']); ?>">
            <input type="hidden" name="tab_actual" value="<?php echo htmlspecialchars($tab_actual); ?>">
            <button type="submit" class="btn btn-primary">Confirmar</button>
            <button type="button" onclick="cerrarModal()" class="btn" style="background: #ccc; color: #000;">Cancelar</button>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Botones de agregar en la lista de búsqueda
        document.querySelectorAll('.btn-agregar').forEach(function(boton) {
            boton.addEventListener('click', function() {
                document.getElementById('id-amigo-modal').value = this.getAttribute('data-id');
                document.getElementById('nombre-amigo').textContent = this.getAttribute('data-nombre');
                document.getElementById('modal-confirmar').classList.remove('hidden');
            });
        });

        // Botón de agregar en el sidebar del perfil
        document.querySelectorAll('.btn-agregar-perfil').forEach(function(boton) {
            boton.addEventListener('click', function() {
                document.getElementById('id-amigo-modal').value = this.getAttribute('data-id');
                document.getElementById('nombre-amigo').textContent = this.getAttribute('data-nombre');
                document.getElementById('modal-confirmar').classList.remove('hidden');
            });
        });
    });

    function cerrarModal() {
        document.getElementById('modal-confirmar').classList.add('hidden');
    }
</script>
<?php require_once "includes/footer.php"; ?>