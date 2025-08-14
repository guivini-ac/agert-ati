<?php
/**
 * Linha de documento agregada.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

// Espera-se que $doc (array) e $meeting (WP_Post) sejam fornecidos.
$meeting_id = $meeting->ID;
$data_hora  = agert_meta($meeting_id, 'data_hora');
$file_url   = '';
$file_name  = '';
$size       = $doc['tamanho_bytes'] ?? '';

if (!empty($doc['arquivo_id'])) {
    $file_url  = wp_get_attachment_url($doc['arquivo_id']);
    $file_name = get_the_title($doc['arquivo_id']);
    if (!$size) {
        $file_path = get_attached_file($doc['arquivo_id']);
        if ($file_path && file_exists($file_path)) {
            $size = filesize($file_path);
        }
    }
} elseif (!empty($doc['arquivo_url'])) {
    $file_url  = $doc['arquivo_url'];
    $file_name = basename($file_url);
}

if (!$file_url) {
    return;
}
if (!$file_name) {
    $file_name = basename($file_url);
}
$size_h      = $size ? agert_bytes_to_human((int) $size) : '';
$same_domain = strpos($file_url, home_url()) === 0;
?>
<div class="doc-row mb-2">
    <span class="badge-chip"><?php echo esc_html($doc['rotulo'] ?? 'Documento'); ?></span>
    <div class="flex-grow-1">
        <?php if ($data_hora) : ?>
            <small class="d-block text-muted"><?php echo esc_html(date_i18n('d/m/Y', strtotime($data_hora))); ?></small>
        <?php endif; ?>
        <a href="<?php echo esc_url(get_permalink($meeting_id)); ?>" class="text-decoration-none"><?php echo esc_html(get_the_title($meeting_id)); ?></a>
        <?php if (!empty($doc['resumo'])) : ?>
            <div class="muted small"><?php echo esc_html($doc['resumo']); ?></div>
        <?php endif; ?>
    </div>
    <?php if ($size_h) : ?><span class="doc-size"><?php echo esc_html($size_h); ?></span><?php endif; ?>
    <a href="<?php echo esc_url(get_permalink($meeting_id)); ?>" class="btn btn-outline-brand btn-sm ms-2" aria-label="Ver Reunião"><i class="bi bi-eye"></i></a>
    <a href="<?php echo esc_url($file_url); ?>" class="btn btn-brand btn-sm ms-1" <?php echo $same_domain ? 'download' : 'target="_blank" rel="noopener noreferrer"'; ?> aria-label="Download <?php echo esc_attr($file_name); ?>"><i class="bi bi-download"></i></a>
</div>
