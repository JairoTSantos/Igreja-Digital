<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $base_url; ?>/public/js/scripts.js"></script>

<?php
$html = ob_get_clean(); // Obtém o conteúdo do buffer
$htmlMinificado = str_replace(array("\n", "\r", "\t"), '', $html); // Remove quebras de linha e espaços em branco
echo $htmlMinificado;
?>