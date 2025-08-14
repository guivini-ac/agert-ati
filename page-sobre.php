<?php
/**
 * Template Name: Sobre a AGERT
 * Description: Página institucional "Sobre a AGERT".
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();

        $post_id = get_the_ID();
        $titulo  = agert_sobre_meta($post_id, 'sobre_titulo', get_the_title());
        $texto   = agert_sobre_meta($post_id, 'sobre_texto', '');
        $valores = agert_sobre_meta($post_id, 'valores', array());
        $orgaos  = agert_sobre_meta($post_id, 'orgaos', array());

        if (!is_array($valores)) {
            $valores = array();
        }
        if (!is_array($orgaos)) {
            $orgaos = array();
        }
        ?>

        <div class="page-sobre py-5">
            <div class="container-lg">
                <h1 class="text-center mb-4"><?php echo esc_html($titulo); ?></h1>

                <?php
                if (current_user_can('manage_options') && (empty($texto) || empty($valores) || empty($orgaos))) {
                    if (isset($_GET['seed_sobre']) && check_admin_referer('agert_sobre_seed')) {
                        agert_sobre_seed_if_empty(get_the_ID());
                        wp_safe_redirect(remove_query_arg(array('seed_sobre', '_wpnonce')));
                        exit;
                    }
                    echo '<p class="text-center mb-3"><a class="btn btn-outline-brand btn-sm" href="' .
                        esc_url(add_query_arg('seed_sobre', '1')) . '&_wpnonce=' . wp_create_nonce('agert_sobre_seed') .
                        '">Preencher dados de exemplo</a></p>';
                }
                ?>

                <?php if (!empty($texto)) : ?>
                    <div class="card-soft p-4 mb-5">
                        <h2 class="h5 mb-3"><?php echo esc_html__('Sobre a AGERT', 'agert'); ?></h2>
                        <div class="page-intro"><?php echo wp_kses_post($texto); ?></div>
                    </div>
                <?php elseif (current_user_can('manage_options')) : ?>
                    <p class="text-center text-muted"><?php esc_html_e('Preencha os campos em Aparência › Campos/Meta', 'agert'); ?></p>
                <?php endif; ?>

                <?php if (!empty($valores)) : ?>
                    <h2 class="section-title"><?php _e('Nossos Valores', 'agert'); ?></h2>
                    <div class="row g-4 mb-5">
                        <?php foreach ($valores as $valor) :
                            $vt = isset($valor['titulo']) ? $valor['titulo'] : '';
                            $vd = isset($valor['descricao']) ? $valor['descricao'] : '';
                            $vi = isset($valor['icone']) ? $valor['icone'] : '';
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="valor-card h-100">
                                <span class="valor-icone"><?php agert_icon($vi); ?></span>
                                <h3 class="valor-titulo h5"><?php echo esc_html($vt); ?></h3>
                                <p class="valor-desc"><?php echo esc_html($vd); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (current_user_can('manage_options')) : ?>
                    <p class="text-center text-muted"><?php esc_html_e('Preencha os campos em Aparência › Campos/Meta', 'agert'); ?></p>
                <?php endif; ?>

                <?php if (!empty($orgaos)) : ?>
                    <h2 class="section-title"><?php _e('Estrutura Organizacional', 'agert'); ?></h2>
                    <div class="card-soft p-3">
                        <div class="row g-3">
                            <?php foreach ($orgaos as $org) :
                                $ot = isset($org['titulo']) ? $org['titulo'] : '';
                                $od = isset($org['descricao']) ? $org['descricao'] : '';
                                $oi = isset($org['icone']) ? $org['icone'] : '';
                            ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="org-item">
                                    <div class="org-icone"><?php agert_icon($oi); ?></div>
                                    <div>
                                        <p class="org-titulo"><?php echo esc_html($ot); ?></p>
                                        <p class="org-desc mb-0"><?php echo esc_html($od); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php elseif (current_user_can('manage_options')) : ?>
                    <p class="text-center text-muted"><?php esc_html_e('Preencha os campos em Aparência › Campos/Meta', 'agert'); ?></p>
                <?php endif; ?>

            </div>
        </div>

        <?php
    endwhile;
endif;

get_footer();
