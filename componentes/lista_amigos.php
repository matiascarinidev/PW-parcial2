<?php
// componentes/lista_amigos.php
$solicitudes = $solicitudes ?? [];
$amigos = $amigos ?? [];
$resultados_busqueda = $resultados_busqueda ?? [];
$perfil = $perfil ?? ['usuario' => '', 'email' => ''];
$url_perfil = $url_perfil ?? '';
?>
<div class="card">
    <?php if (count($solicitudes) > 0): ?>
        <h3 class="mb-4">Solicitudes Pendientes</h3>
        <ul style="list-style: none;">
            <?php foreach ($solicitudes as $sol): ?>
                <li class="flex justify-between items-center mb-2 p-2" style="background: var(--bg-body); border-radius: 4px;">
                    <strong><?php echo htmlspecialchars($sol['usuario']); ?></strong>
                    <form method='POST' action="<?php echo BASE_URL; ?>perfil.php<?php echo $url_perfil; ?>&tab=amigos" class="flex gap-2">
                        <input type='hidden' name='id_amistad' value='<?php echo $sol['id_amistad']; ?>'>
                        <button type='submit' name='accion_solicitud' value='aceptar' class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Aceptar</button>
                        <button type='submit' name='accion_solicitud' value='rechazar' class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Rechazar</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <hr class="mt-4 mb-4" style="border: 0; border-top: 1px solid var(--border-color);">
    <?php endif; ?>

    <h3 class="mb-4">Buscar Usuarios</h3>
    <form method="GET" action="<?php echo BASE_URL; ?>perfil.php" class="flex gap-2 mb-4">
        <input type="hidden" name="u" value="<?php echo urlencode($perfil['usuario'] ?? $perfil['email']); ?>">
        <input type="hidden" name="tab" value="amigos">
        <input type="text" name="buscar" class="input-field" placeholder="Nombre o email" required>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <?php if (isset($_GET['buscar']) && count($resultados_busqueda) > 0): ?>
        <h4 class="mb-2">Resultados:</h4>
        <ul style="list-style: none;">
            <?php foreach ($resultados_busqueda as $row): ?>
                <li class="flex justify-between items-center mb-2">
                    <span><?php echo htmlspecialchars($row['usuario'] ?? $row['email']); ?></span>
                    <button type='button' class='btn btn-primary btn-agregar'
                        data-id='<?php echo $row['id']; ?>'
                        data-nombre='<?php echo htmlspecialchars($row['usuario'] ?? $row['email'], ENT_QUOTES); ?>'
                        style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Agregar</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (isset($_GET['buscar'])): ?>
        <p class="text-muted mb-4">No se encontraron usuarios.</p>
    <?php endif; ?>

    <hr class="mt-4 mb-4" style="border: 0; border-top: 1px solid var(--border-color);">

    <h3 class="mb-4">Mis Amigos</h3>
    <?php if (count($amigos) > 0): ?>
        <ul style="list-style: none;">
            <?php foreach ($amigos as $amigo): ?>
                <li class="text-sm" style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);"><?php echo htmlspecialchars($amigo['usuario']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted">No tienes amigos aún.</p>
    <?php endif; ?>
</div>