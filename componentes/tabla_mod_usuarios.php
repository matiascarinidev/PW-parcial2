<?php
// componentes/tabla_mod_usuarios.php
// Requiere: $usuarios (array de usuarios)

if (!isset($usuarios) || empty($usuarios)): ?>
    <p class="text-muted" style="text-align: center;">No hay usuarios para moderar.</p>
<?php else: ?>
    <div class="table-container card">
        <table class="table">
            <thead>
                <tr style="background-color: var(--bg-body);">
                    <th>ID</th>
                    <th>Email</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $user): ?>
                    <?php if ($user['rol'] !== 'admin' && !$user['baneado']): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['usuario'] ?? 'Sin perfil'); ?></td>
                            <td><?php echo htmlspecialchars($user['rol']); ?></td>
                            <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Banear a este usuario?');">
                                    <input type="hidden" name="id_usuario" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="banear_usuario_mod" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Banear</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>