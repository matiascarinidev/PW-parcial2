<?php
// componentes/tabla_mod_publicaciones.php
// Requiere: $publicaciones (array de publicaciones)

if (!isset($publicaciones) || empty($publicaciones)): ?>
    <p class="text-muted" style="text-align: center;">No hay publicaciones para moderar.</p>
<?php else: ?>
    <div class="table-container card">
        <table class="table">
            <thead>
                <tr style="background-color: var(--bg-body);">
                    <th>ID</th>
                    <th>Autor</th>
                    <th>Contenido</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($publicaciones as $pub): ?>
                    <tr>
                        <td><?php echo $pub['id_publicacion']; ?></td>
                        <td><?php echo htmlspecialchars($pub['usuario'] ?? 'Sin perfil'); ?></td>
                        <td style="max-width: 300px; word-wrap: break-word;">
                            <?php echo nl2br(htmlspecialchars(substr($pub['contenido'] ?? '', 0, 100))); ?>
                            <?php if (strlen($pub['contenido'] ?? '') > 100) echo "..."; ?>
                        </td>
                        <td>
                            <?php if ($pub['estado_visibilidad'] === 'visible'): ?>
                                <span style="color: var(--success-color); font-weight: bold;">👁️ Visible</span>
                            <?php else: ?>
                                <span style="color: var(--danger-color); font-weight: bold;">🚫 Oculta</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id_publicacion" value="<?php echo $pub['id_publicacion']; ?>">
                                <input type="hidden" name="nuevo_estado" value="<?php echo $pub['estado_visibilidad'] === 'visible' ? 'oculta' : 'visible'; ?>">
                                <button type="submit" name="cambiar_estado" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                    <?php echo $pub['estado_visibilidad'] === 'visible' ? 'Ocultar' : 'Mostrar'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>