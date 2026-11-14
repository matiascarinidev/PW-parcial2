<?php
$perfil = $perfil ?? [];
$datos_formulario = $datos_formulario ?? $perfil; // Si no hay datos de formulario, usa el perfil
$es_edicion = $es_edicion ?? false;

$action = $es_edicion ? 'perfil.php?tab=configuracion' : 'crear_perfil.php';
$url_action = BASE_URL . $action;

$btn_text = $es_edicion ? 'Actualizar Perfil' : 'Guardar Perfil';
?>
<section class="card">
    <h2 class="mb-4"><?php echo $es_edicion ? 'Editar mi Perfil' : 'Completa tu Perfil'; ?></h2>
    <form method="POST" action="<?php echo $url_action; ?>">
        <label class="font-bold text-sm">NICKNAME</label>
        <input type="text" name="usuario" class="input-field mt-2" value="<?php echo htmlspecialchars($datos_formulario['usuario'] ?? ''); ?>" required>

        <label class="font-bold text-sm mt-4" style="display:block;">NOMBRE</label>
        <input type="text" name="nombre" class="input-field mt-2" value="<?php echo htmlspecialchars($datos_formulario['nombre'] ?? ''); ?>" required>

        <label class="font-bold text-sm mt-4" style="display:block;">APELLIDO</label>
        <input type="text" name="apellido" class="input-field mt-2" value="<?php echo htmlspecialchars($datos_formulario['apellido'] ?? ''); ?>" required>

        <label class="font-bold text-sm mt-4" style="display:block;">TELÉFONO</label>
        <input type="text" name="telefono" class="input-field mt-2" value="<?php echo htmlspecialchars($datos_formulario['telefono'] ?? ''); ?>">

        <label class="font-bold text-sm mt-4" style="display:block;">BIOGRAFÍA</label>
        <textarea name="biografia" class="input-field mt-2" style="min-height: 80px;"><?php echo htmlspecialchars($datos_formulario['biografia'] ?? ''); ?></textarea>

        <button type="submit" class="btn btn-primary mt-4 w-full"><?php echo $btn_text; ?></button>
    </form>
</section>