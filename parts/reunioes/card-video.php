<?php
/**
 * Card de vídeo para listagens.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

$post_id   = $meeting->ID;
$thumb     = get_the_post_thumbnail($post_id, 'large', array('class' => 'thumb-16x9 w-100', 'alt' => esc_attr(get_the_title($post_id))));
$duracao   = $video['duracao_segundos'] ?? '';
$data_hora = agert_meta($post_id, 'data_hora');
$titulo    = $video['titulo'] ?? get_the_title($post_id);
$descricao = $video['descricao'] ?? get_the_excerpt($post_id);
?>
<div class="card card-soft h-100">
    <div class="position-relative">
        <?php if ($thumb) : ?>
            <?php echo $thumb; ?>
        <?php endif; ?>
        <i class="bi bi-play-fill position-absolute top-50 start-50 translate-middle fs-1 text-white"></i>
        <?php if ($duracao) : ?>
            <span class="duration-badge"><?php echo esc_html(agert_seconds_to_mmss((int) $duracao)); ?></span>
        <?php endif; ?>
    </div>
    <div class="card-body d-flex flex-column">
        <?php if ($data_hora) : ?>
            <p class="small text-muted mb-1"><i class="bi bi-calendar3 me-1"></i><?php echo esc_html(date_i18n('d/m/Y', strtotime($data_hora))); ?></p>
        <?php endif; ?>
        <h5 class="card-title mb-2"><?php echo esc_html($titulo); ?></h5>
        <p class="muted mb-3 flex-grow-1"><?php echo esc_html($descricao); ?></p>
        <a href="<?php echo esc_url(get_permalink($post_id) . '#transmissao'); ?>" class="btn btn-brand w-100 mt-auto">Assistir + Ver Detalhes</a>
    </div>
</div>
