<?php
// componentes/paginacion.php
// Inicialización de variables para evitar errores del editor y de ejecución
$total_paginas = $total_paginas ?? 0;
$pagina = $pagina ?? 1;
$url_base_paginacion = $url_base_paginacion ?? '';

if ($total_paginas > 1):
?>
    <nav class="paginacion" style="display: flex; justify-content: center; gap: 8px; margin-top: 25px; margin-bottom: 30px;">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="<?php echo htmlspecialchars($url_base_paginacion); ?>&pagina=<?php echo $i; ?>"
                style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; font-weight: bold;
                  <?php echo $i == $pagina ? 'background: #007bff; color: #fff; border-color: #007bff;' : 'background: #fff; color: #007bff;'; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </nav>
<?php endif; ?>