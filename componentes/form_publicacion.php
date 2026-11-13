<?php
$redirigir = $redirigir ?? "index.php";
?>
<section class="card">
    <form method="POST" action="<?php echo BASE_URL; ?>crear_publicacion.php">
        <textarea name="contenido" class="input-field" placeholder="¿Qué estás pensando?" required style="resize: none; min-height: 80px;"></textarea>
        <div class="flex justify-end mt-2">
            <button type="submit" class="btn btn-primary">Publicar</button>
        </div>
        <input type="hidden" name="redirigir" value="<?php echo htmlspecialchars($redirigir); ?>">
    </form>
</section>