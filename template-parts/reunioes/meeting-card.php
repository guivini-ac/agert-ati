<?php
/**
 * Card de reunião reutilizável.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'status_class' => '',
    'status_text'  => '',
    'tipos'        => array(),
    'data_hora'    => '',
    'local'        => '',
);
$args = wp_parse_args($args, $defaults);
?>
<div class="col-md-6">
    <div class="card meeting-card h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0"><?php the_title(); ?></h5>
                <span class="badge bg-light text-dark <?php echo esc_attr($args['status_class']); ?>">
                    <?php echo esc_html($args['status_text']); ?>
                </span>
            </div>

            <?php if (!empty($args['tipos']) && !is_wp_error($args['tipos'])) : ?>
                <div class="mb-2">
                    <?php foreach ($args['tipos'] as $tipo) : ?>
                        <span class="badge bg-secondary me-1"><?php echo esc_html($tipo->name); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($args['data_hora'])) : ?>
                <p class="card-text small text-muted mb-1">
                    <i class="bi bi-clock me-1"></i>
                    <?php echo esc_html(agert_format_datetime($args['data_hora'])); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($args['local'])) : ?>
                <p class="card-text small text-muted mb-2">
                    <i class="bi bi-geo-alt me-1"></i>
                    <?php echo esc_html($args['local']); ?>
                </p>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i><?php _e('Ver Detalhes', 'agert'); ?>
                </a>
                <?php if (has_excerpt()) : ?>
                    <small class="text-muted"><?php echo esc_html(get_the_excerpt()); ?></small>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
