<?php
$perfil = $perfil ?? [];
$url_perfil = $url_perfil ?? '';
$es_mi_perfil = $es_mi_perfil ?? false;
$estado_amistad = $estado_amistad ?? null;
?>
<aside class="card" style="text-align: center;">
    <h2 class="mt-2"><?php echo htmlspecialchars($perfil['usuario'] ?? 'Sin perfil'); ?></h2>
    <p class="text-muted"><?php echo htmlspecialchars(($perfil['nombre'] ?? '') . " " . ($perfil['apellido'] ?? '')); ?></p>
    <p class="text-sm mt-2"><?php echo nl2br(htmlspecialchars($perfil['biografia'] ?? '')); ?></p>

    <?php if (!$es_mi_perfil && isset($_SESSION['usuario_id'])): ?>
        <div class="mt-4">
            <?php if ($estado_amistad === 'aceptada'): ?>
                <button class="btn" style="background: var(--success-color); color: white; width: 100%; cursor: default;">✅ Amigos</button>
            <?php elseif ($estado_amistad === 'pendiente'): ?>
                <button class="btn" style="background: var(--border-color); color: var(--text-main); width: 100%; cursor: default;">⏳ Solicitud pendiente</button>
            <?php else: ?>
                <button type="button" class="btn btn-primary w-full btn-agregar-perfil"
                    data-id="<?php echo $perfil['id']; ?>"
                    data-nombre="<?php echo htmlspecialchars($perfil['usuario'] ?? $perfil['email'], ENT_QUOTES); ?>">
                    ➕ Añadir a amigos
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($es_mi_perfil): ?>
        <hr class="mt-4 mb-4" style="border: 0; border-top: 1px solid var(--border-color);">
        <nav class="flex-col gap-2" style="text-align: left;">
            <a href="<?php echo BASE_URL; ?>perfil.php<?php echo $url_perfil; ?>&tab=publicaciones" class="font-bold text-sm" style="display:block; padding: 0.5rem; border-radius: 4px; background: var(--bg-body);">Publicaciones</a>
            <a href="<?php echo BASE_URL; ?>perfil.php<?php echo $url_perfil; ?>&tab=amigos" class="font-bold text-sm" style="display:block; padding: 0.5rem; border-radius: 4px; background: var(--bg-body);">Amigos</a>
            <a href="<?php echo BASE_URL; ?>perfil.php<?php echo $url_perfil; ?>&tab=configuracion" class="font-bold text-sm" style="display:block; padding: 0.5rem; border-radius: 4px; background: var(--bg-body);">Configuración</a>
        </nav>
    <?php endif; ?>
</aside>