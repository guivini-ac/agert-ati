<?php
/**
 * Card de reunião para listagens.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

$post_id = get_the_ID();
$thumb   = get_the_post_thumbnail($post_id, 'large', array('class' => 'thumb-16x9 w-100', 'alt' => esc_attr(get_the_title())));
$duracao = agert_meta($post_id, 'duracao_minutos');
$tipo    = '';
$terms   = get_the_terms($post_id, 'tipo_reuniao');
if ($terms && !is_wp_error($terms)) {
    $tipo = $terms[0]->name;
} else {
    $tipo = agert_meta($post_id, 'tipo_reuniao');
}
$video   = agert_reuniao_has_video($post_id);
$doc_qtd = agert_count_documentos($post_id);
$data_hora = agert_meta($post_id, 'data_hora');
?>
<div class="card card-soft h-100">
    <div class="position-relative">
        <?php if ($thumb) : ?>
            <?php echo $thumb; ?>
        <?php endif; ?>
        <?php if ($duracao) : ?>
            <span class="duration-badge"><?php echo esc_html(agert_seconds_to_mmss((int) $duracao * 60)); ?></span>
        <?php endif; ?>
        <?php if ($tipo) : ?>
            <span class="badge-chip position-absolute m-2"><?php echo esc_html($tipo); ?></span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if ($data_hora) : ?>
            <p class="small text-muted mb-1"><i class="bi bi-calendar3 me-1"></i><?php echo esc_html(date_i18n('d/m/Y', strtotime($data_hora))); ?></p>
        <?php endif; ?>
        <h5 class="card-title mb-2"><?php the_title(); ?></h5>
        <p class="muted mb-3"><?php echo esc_html(get_the_excerpt()); ?></p>
    </div>
    <div class="card-footer bg-white border-0 pt-0">
        <div class="d-flex justify-content-between small text-muted mb-2">
            <span><?php echo esc_html($doc_qtd); ?> documentos</span>
            <?php if ($video) : ?>
                <span>Vídeo disponível</span>
            <?php endif; ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="btn btn-brand w-100">Ver Detalhes Completos</a>
    </div>
</div>
