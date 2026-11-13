<?php
// componentes/tabla_admin_usuarios.php
// Requiere: $usuarios (array de usuarios)

if (!isset($usuarios) || empty($usuarios)): ?>
    <p class="text-muted" style="text-align: center;">No hay usuarios registrados.</p>
<?php else: ?>
    <div class="table-container card">
        <table class="table">
            <thead>
                <tr style="background-color: var(--bg-body);">
                    <th>ID</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $fila): ?>
                    <tr>
                        <td><?php echo $fila['id']; ?></td>
                        <td><?php echo htmlspecialchars($fila['email']); ?></td>
                        <td>
                            <?php if (!empty($fila['usuario'])): ?>
                                <strong><?php echo htmlspecialchars($fila['usuario']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars(($fila['nombre'] ?? '') . " " . ($fila['apellido'] ?? '')); ?></small>
                            <?php else: ?>
                                <em class="text-muted">Sin perfil</em>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" class="flex items-center gap-2">
                                <input type="hidden" name="id_usuario" value="<?php echo $fila['id']; ?>">
                                <select name="nuevo_rol" class="input-field" style="padding: 0.3rem; width: auto;">
                                    <option value="usuario" <?php if ($fila['rol'] == 'usuario') echo 'selected'; ?>>Usuario</option>
                                    <option value="moderador" <?php if ($fila['rol'] == 'moderador') echo 'selected'; ?>>Moderador</option>
                                    <option value="admin" <?php if ($fila['rol'] == 'admin') echo 'selected'; ?>>Admin</option>
                                </select>
                                <button type="submit" name="cambiar_rol" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Cambiar</button>
                            </form>
                        </td>
                        <td>
                            <?php if ($fila['baneado']): ?>
                                <span style="color: var(--danger-color); font-weight: bold;">BANEADO</span>
                            <?php else: ?>
                                <span style="color: var(--success-color); font-weight: bold;">Activo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_usuario" value="<?php echo $fila['id']; ?>">
                                    <input type="hidden" name="estado_ban" value="<?php echo $fila['baneado'] ? 0 : 1; ?>">
                                    <button type="submit" name="banear" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                                        <?php echo $fila['baneado'] ? 'Desbanear' : 'Banear'; ?>
                                    </button>
                                </form>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar cuenta permanentemente?');">
                                    <input type="hidden" name="id_usuario" value="<?php echo $fila['id']; ?>">
                                    <button type="submit" name="eliminar_cuenta" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Borrar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>