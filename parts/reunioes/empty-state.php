<?php
/**
 * Empty state message for listings.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="text-center py-5">
    <p class="mb-3"><?php echo esc_html($message ?? __('Nenhum resultado encontrado.', 'agert')); ?></p>
    <?php if (!empty($reset_url)) : ?>
        <a href="<?php echo esc_url($reset_url); ?>" class="btn btn-brand"><?php esc_html_e('Limpar filtros', 'agert'); ?></a>
    <?php endif; ?>
</div>
