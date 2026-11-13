<?php
$perfil = $perfil ?? [];
$es_edicion = $es_edicion ?? false;
$action = $es_edicion ? 'editar_perfil.php' : 'crear_perfil.php';
$btn_text = $es_edicion ? 'Actualizar Perfil' : 'Guardar Perfil';
?>
<section class="card">
    <h2 class="mb-4"><?php echo $es_edicion ? 'Editar mi Perfil' : 'Completa tu Perfil'; ?></h2>
    <form method="POST" action="<?php echo BASE_URL . $action; ?>">
        <label class="font-bold text-sm">NICKNAME</label>
        <input type="text" name="usuario" class="input-field mt-2" value="<?php echo htmlspecialchars($perfil['usuario'] ?? ''); ?>" required>

        <label class="font-bold text-sm mt-4" style="display:block;">NOMBRE</label>
        <input type="text" name="nombre" class="input-field mt-2" value="<?php echo htmlspecialchars($perfil['nombre'] ?? ''); ?>" required>

        <label class="font-bold text-sm mt-4" style="display:block;">APELLIDO</label>
        <input type="text" name="apellido" class="input-field mt-2" value="<?php echo htmlspecialchars($perfil['apellido'] ?? ''); ?>" required>

        <label class="font-bold text-sm mt-4" style="display:block;">TELÉFONO</label>
        <input type="text" name="telefono" class="input-field mt-2" value="<?php echo htmlspecialchars($perfil['telefono'] ?? ''); ?>">

        <label class="font-bold text-sm mt-4" style="display:block;">BIOGRAFÍA</label>
        <textarea name="biografia" class="input-field mt-2" style="min-height: 80px;"><?php echo htmlspecialchars($perfil['biografia'] ?? ''); ?></textarea>

        <button type="submit" class="btn btn-primary mt-4 w-full"><?php echo $btn_text; ?></button>
    </form>
</section>