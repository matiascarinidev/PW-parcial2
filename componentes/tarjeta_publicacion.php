<?php
$pub = $pub ?? [];
$rol_visitante = $rol_visitante ?? 'usuario';
$nombre_autor = $nombre_autor ?? 'Usuario';
$redirigir = $redirigir ?? 'index.php';

if (empty($pub) || !isset($pub['contenido'])) return;

$es_oculta = isset($pub['estado_visibilidad']) && $pub['estado_visibilidad'] === 'oculta';
$es_moderador = in_array($rol_visitante, ['admin', 'moderador']);
$border_style = ($es_oculta && $es_moderador) ? 'border-color: var(--danger-color); border-width: 2px;' : '';

$total_likes = $pub['total_likes'] ?? 0;
$dio_like = $pub['dio_like'] ?? 0;

// URL del perfil del autor
$url_autor = BASE_URL . "perfil.php?u=" . urlencode($pub['usuario'] ?? $nombre_autor);
?>
<article class="card" style="<?php echo $border_style; ?>">
    <header class="flex justify-between items-center mb-4">
        <strong>
            <a href="<?php echo $url_autor; ?>" style="color: var(--text-main); text-decoration: none;">
                <?php echo htmlspecialchars($nombre_autor); ?>
            </a>
        </strong>
        <small class="text-muted"><?php echo htmlspecialchars($pub['fecha_creacion'] ?? ''); ?></small>
    </header>
    <div style="word-wrap: break-word; margin-bottom: 1rem;">
        <p><?php echo nl2br(htmlspecialchars($pub['contenido'])); ?></p>
    </div>

    <footer class="flex items-center gap-4" style="border-top: 1px solid var(--border-color); padding-top: 0.5rem;">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <form method="POST" action="<?php echo BASE_URL; ?>procesar_like.php" style="display:inline; margin: 0;">
                <input type="hidden" name="id_publicacion" value="<?php echo $pub['id_publicacion']; ?>">
                <input type="hidden" name="redirigir" value="<?php echo htmlspecialchars($redirigir); ?>">
                <button type="submit" class="btn" style="background: none; border: none; color: <?php echo $dio_like ? 'var(--primary-color)' : 'var(--text-muted)'; ?>; font-size: 0.9rem; cursor: pointer; padding: 0; font-weight: <?php echo $dio_like ? 'bold' : 'normal'; ?>;">
                    👍 <?php echo $total_likes; ?> Me gusta
                </button>
            </form>
        <?php else: ?>
            <span class="text-muted" style="font-size: 0.9rem;">👍 <?php echo $total_likes; ?> Me gusta</span>
        <?php endif; ?>

        <?php if ($es_moderador): ?>
            <form method="POST" action="<?php echo BASE_URL; ?>admin/moderar_accion.php" style="display:inline; margin: 0; margin-left: auto;">
                <input type="hidden" name="id_publicacion" value="<?php echo $pub['id_publicacion']; ?>">
                <input type="hidden" name="nuevo_estado" value="<?php echo $pub['estado_visibilidad'] === 'visible' ? 'oculta' : 'visible'; ?>">
                <input type="hidden" name="redirigir" value="<?php echo htmlspecialchars($redirigir); ?>">
                <button type="submit" class="btn" style="background: none; border: none; color: <?php echo $pub['estado_visibilidad'] === 'visible' ? 'var(--success-color)' : 'var(--danger-color)'; ?>; font-size: 0.8rem; cursor: pointer; padding: 0; font-weight: bold;">
                    <?php echo $pub['estado_visibilidad'] === 'visible' ? '👁️ Ocultar' : '🚫 Mostrar'; ?>
                </button>
            </form>
        <?php endif; ?>
    </footer>
</article>