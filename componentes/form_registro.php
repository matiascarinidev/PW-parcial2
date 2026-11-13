<?php
// componentes/form_registro.php
// No requiere variables externas
?>
<section class="card max-w-md mx-auto mt-4">
    <h2 class="mb-4" style="text-align: center;">Registro de Usuario</h2>
    <form method="POST">
        <label class="font-bold text-sm">EMAIL</label>
        <input type="email" name="email" class="input-field mt-2" required>

        <label class="font-bold text-sm mt-4" style="display: block;">CONTRASEÑA</label>
        <input type="password" name="password" class="input-field mt-2" maxlength="25" required>

        <label class="font-bold text-sm mt-4" style="display: block;">REPETIR CONTRASEÑA</label>
        <input type="password" name="password_confirm" class="input-field mt-2" maxlength="25" required>

        <button type="submit" class="btn btn-primary mt-4 w-full">Registrarse</button>
    </form>
</section>