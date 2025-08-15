<?php
/**
 * Card com informação de contato reutilizável.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'icon'  => '',
    'label' => '',
    'text'  => '',
);
$args = wp_parse_args($args, $defaults);
?>
<div class="col">
    <div class="card-soft p-4 h-100">
        <div class="d-flex align-items-start">
            <?php if ($args['icon']) : ?>
                <i class="bi <?php echo esc_attr($args['icon']); ?> fs-3 me-3" aria-hidden="true"></i>
            <?php endif; ?>
            <div>
                <?php if ($args['label']) : ?>
                    <p class="mb-1 fw-semibold"><?php echo esc_html($args['label']); ?></p>
                <?php endif; ?>
                <?php if ($args['text']) : ?>
                    <p class="mb-0"><?php echo esc_html($args['text']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
