<?php

require_once "includes/config.php";
require_once "clases/Database.php";
require_once "clases/Perfil.php";
require_once "clases/Publicacion.php";
require_once "clases/Amistad.php";
require_once "includes/header.php";
require_once "includes/nav.php";

// 1. Obtener el usuario a mostrar
$param = $_GET['u'] ?? $_SESSION['usuario_email'] ?? null;
if (!$param) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Intentar buscar por email o por nombre de usuario
$db = Database::getInstance()->getConexion();
$stmt = $db->prepare("
    SELECT u.id, u.email, p.usuario, p.nombre, p.apellido, p.biografia 
    FROM usuarios u 
    LEFT JOIN perfiles p ON u.id = p.usuario_id 
    WHERE p.usuario = ? OR u.email = ?
");
$stmt->bind_param("ss", $param, $param);
$stmt->execute();
$perfil = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$perfil) {
    die("Usuario no encontrado.");
}

$es_mi_perfil = (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $perfil['id']);

// 2. Paginación (solo para publicaciones)
$tab_actual = $_GET['tab'] ?? 'publicaciones';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 5;
$offset = ($pagina - 1) * $limite;

$pubObj = new Publicacion();
$publicaciones = [];
$total_paginas = 0;

if ($tab_actual === 'publicaciones') {
    $publicaciones = $pubObj->obtenerPublicacionesPerfil($perfil['id'], $offset, $limite);
    $total = $pubObj->contarPublicacionesPerfil($perfil['id']);
    $total_paginas = ceil($total / $limite);
}
// Procesar aceptación/rechazo de solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_solicitud'])) {
    $id_amistad = (int)$_POST['id_amistad'];
    $amistadObj = new Amistad();

    if ($_POST['accion_solicitud'] === 'aceptar') {
        $amistadObj->aceptarSolicitud($id_amistad, $_SESSION['usuario_id']);
        $_SESSION['flash_message'] = "Solicitud aceptada.";
    } elseif ($_POST['accion_solicitud'] === 'rechazar') {
        $amistadObj->rechazarSolicitud($id_amistad, $_SESSION['usuario_id']);
        $_SESSION['flash_message'] = "Solicitud rechazada.";
    }

    $_SESSION['flash_type'] = "success";
    header("Location: " . BASE_URL . "perfil.php?u=" . urlencode($perfil['usuario'] ?? $perfil['email']) . "&tab=amigos");
    exit();
}
?>

<div style="display: flex; gap: 20px;">
    <!-- Columna Izquierda -->
    <div style="width: 250px;">
        <h2><?php echo htmlspecialchars($perfil['usuario'] ?? 'Sin perfil'); ?></h2>
        <p><?php echo htmlspecialchars($perfil['nombre'] . " " . $perfil['apellido']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($perfil['biografia'] ?? '')); ?></p>

        <?php if ($es_mi_perfil): ?>
            <hr>
            <ul style="list-style: none; padding: 0;">
                <li><a href="<?php echo BASE_URL; ?>perfil.php?u=<?php echo urlencode($perfil['usuario'] ?? $perfil['email']); ?>&tab=publicaciones">Publicaciones</a></li>
                <li><a href="<?php echo BASE_URL; ?>perfil.php?u=<?php echo urlencode($perfil['usuario'] ?? $perfil['email']); ?>&tab=amigos">Amigos</a></li>
                <li><a href="<?php echo BASE_URL; ?>perfil.php?u=<?php echo urlencode($perfil['usuario'] ?? $perfil['email']); ?>&tab=configuracion">Configuración</a></li>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Columna Derecha (Panel Dinámico) -->
    <div style="flex: 1;">
        <?php if ($tab_actual === 'publicaciones'): ?>
            <?php if ($es_mi_perfil): ?>
                <form method="POST" action="<?php echo BASE_URL; ?>crear_publicacion.php">
                    <textarea name="contenido" placeholder="¿Qué estás pensando?" required></textarea><br>
                    <input type="hidden" name="redirigir" value="perfil.php?u=<?php echo urlencode($perfil['usuario'] ?? $perfil['email']); ?>">
                    <button type="submit">Publicar</button>
                </form>
            <?php endif; ?>

            <?php foreach ($publicaciones as $pub): ?>
                <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <h4><?php echo htmlspecialchars($pub['titulo'] ?? 'Sin título'); ?></h4>
                    <p><?php echo nl2br(htmlspecialchars($pub['contenido'])); ?></p>
                    <small><?php echo $pub['fecha_creacion']; ?></small>
                </div>
            <?php endforeach; ?>

            <!-- Paginación -->
            <div>
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <a href="<?php echo BASE_URL; ?>perfil.php?u=<?php echo urlencode($perfil['usuario'] ?? $perfil['email']); ?>&tab=publicaciones&pagina=<?php echo $i; ?>"
                        style="<?php echo $i == $pagina ? 'font-weight: bold;' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>

        <?php elseif ($tab_actual === 'amigos' && $es_mi_perfil): ?>
            <!-- Buscador de amigos -->
            <hr>
            <h3>Buscar Usuarios</h3>
            <form method="GET" action="perfil.php">
                <input type="hidden" name="u" value="<?php echo urlencode($perfil['usuario'] ?? $perfil['email']); ?>">
                <input type="hidden" name="tab" value="amigos">
                <input type="text" name="buscar" placeholder="Nombre de usuario o email" required>
                <button type="submit">Buscar</button>
            </form>

            <?php
            if (isset($_GET['buscar'])) {
                $busqueda = trim($_GET['buscar']);
                $db = Database::getInstance()->getConexion();
                $stmt = $db->prepare("
                    SELECT u.id, u.email, p.usuario 
                    FROM usuarios u 
                    LEFT JOIN perfiles p ON u.id = p.usuario_id 
                    WHERE (p.usuario LIKE ? OR u.email LIKE ?) AND u.id != ?
                ");
                $like = "%" . $busqueda . "%";
                $stmt->bind_param("ssi", $like, $like, $perfil['id']);
                $stmt->execute();
                $resultados = $stmt->get_result();

                if ($resultados->num_rows > 0) {
                    echo "<h4>Resultados de búsqueda:</h4>";
                    echo "<ul>";
                    while ($row = $resultados->fetch_assoc()) {
                        $nombre_mostrar = htmlspecialchars($row['usuario'] ?? $row['email']);
                        echo "<li>" . $nombre_mostrar . " 
                              <button type='button' class='btn-agregar' 
                                      data-id='" . $row['id'] . "' 
                                      data-nombre='" . htmlspecialchars($nombre_mostrar, ENT_QUOTES) . "'>
                                  Agregar
                              </button>
                              </li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No se encontraron usuarios.</p>";
                }
                $stmt->close();
            }
            ?>
            <!-- Solicitudes de amistad -->
            <?php
            $amistadObj = new Amistad();
            $solicitudes = $amistadObj->obtenerSolicitudesPendientes($perfil['id']);

            if (count($solicitudes) > 0) {
                echo "<h3>Solicitudes de Amistad Pendientes</h3>";
                echo "<ul>";
                foreach ($solicitudes as $sol) {
                    echo "<li>" . htmlspecialchars($sol['usuario']) . " 
                          <form method='POST' style='display:inline;'>
                              <input type='hidden' name='id_amistad' value='" . $sol['id_amistad'] . "'>
                              <button type='submit' name='accion_solicitud' value='aceptar'>Aceptar</button>
                              <button type='submit' name='accion_solicitud' value='rechazar'>Rechazar</button>
                          </form>
                          </li>";
                }
                echo "</ul><hr>";
            }
            ?>
            <!-- Lista de amigos y buscador integrado -->
            <h3>Mis Amigos</h3>

            <?php
            $perfilObj = new Perfil();
            $amigos = $perfilObj->obtenerAmigos($perfil['id']);
            if (count($amigos) > 0) {
                echo "<ul>";
                foreach ($amigos as $amigo) {
                    echo "<li>" . htmlspecialchars($amigo['usuario']) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No tienes amigos agregados aún.</p>";
            }
            ?>



        <?php endif; ?>
    </div>
</div>

<!-- Modal de Confirmación de Amistad -->
<div id="modal-confirmar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; margin: 10% auto; padding: 20px; width: 300px; border-radius: 5px; text-align: center; color: #000;">
        <h3>Confirmar Solicitud</h3>
        <p>¿Enviar solicitud de amistad a <strong id="nombre-amigo"></strong>?</p>
        <form method="POST" action="<?php echo BASE_URL; ?>enviar_solicitud.php">
            <input type="hidden" name="id_amigo" id="id-amigo-modal">
            <input type="hidden" name="u" value="<?php echo urlencode($perfil['usuario'] ?? $perfil['email']); ?>">
            <button type="submit" style="background: #007bff; color: #fff; padding: 8px 15px; border: none; border-radius: 4px;">Confirmar</button>
            <button type="button" onclick="cerrarModal()" style="background: #ccc; color: #000; padding: 8px 15px; border: none; border-radius: 4px;">Cancelar</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const botones = document.querySelectorAll('.btn-agregar');

        botones.forEach(function(boton) {
            boton.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');

                document.getElementById('id-amigo-modal').value = id;
                document.getElementById('nombre-amigo').textContent = nombre;
                document.getElementById('modal-confirmar').style.display = 'block';
            });
        });
    });

    function cerrarModal() {
        document.getElementById('modal-confirmar').style.display = 'none';
    }
</script>

<?php require_once "includes/footer.php"; ?>