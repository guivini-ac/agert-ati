<?php
/**
 * Template Name: Presidente
 * Template da página "Presidente".
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

// Adiciona classe específica ao body
add_filter('body_class', function ($classes) {
    $classes[] = 'page-presidente';
    return $classes;
});

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $post_id          = get_the_ID();
        $foto_id          = agert_meta($post_id, 'foto_presidente_id');
        $nome             = agert_meta($post_id, 'nome_presidente');
        $cargo_titulo     = agert_meta($post_id, 'cargo_titulo');
        $mandato          = agert_meta($post_id, 'mandato_periodo');
        $formacao         = agert_meta($post_id, 'formacao');
        $especializacao   = agert_meta($post_id, 'especializacao');
        $bio_breve        = agert_meta($post_id, 'bio_breve');
        $experiencias     = agert_meta($post_id, 'experiencias');
        $formacoes        = agert_meta($post_id, 'formacoes');
        $mensagem         = agert_meta($post_id, 'mensagem');
        $assinatura_nome  = agert_meta($post_id, 'assinatura_nome');
        $assinatura_cargo = agert_meta($post_id, 'assinatura_cargo');

        $has_profile = $foto_id || $nome || $cargo_titulo || $mandato || $formacao || $especializacao;
        $has_details = $bio_breve || !empty($experiencias) || !empty($formacoes) || $mensagem || $assinatura_nome || $assinatura_cargo;
?>
<div class="container-lg py-5">
    <h1 class="text-center mb-5"><?php _e('Presidente', 'agert'); ?></h1>
    <div class="row g-4">
        <?php if ($has_profile) : ?>
            <div class="col-lg-4">
                <div class="card-soft p-4 h-100 text-center">
                    <div class="mb-3">
                        <?php if ($foto_id) : ?>
                            <?php echo agert_img($foto_id, 'large', array('class' => 'photo', 'alt' => $nome ? sprintf(__('Foto de %s', 'agert'), $nome) : __('Foto do presidente', 'agert'))); ?>
                        <?php else : ?>
                            <div class="photo d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" fill="#6b7280" class="bi bi-person" viewBox="0 0 16 16" role="img" aria-label="<?php esc_attr_e('Sem foto', 'agert'); ?>">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    <path fill-rule="evenodd" d="M14 14s-1-4-6-4-6 4-6 4 1 0 6 0 6 0 6 0z" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($nome) : ?>
                        <p class="mb-1 fw-bold"><?php echo esc_html($nome); ?></p>
                    <?php endif; ?>
                    <?php if ($cargo_titulo) : ?>
                        <p class="mb-3"><?php echo esc_html($cargo_titulo); ?></p>
                    <?php endif; ?>
                    <?php if ($mandato) : ?>
                        <p><span class="label-muted"><?php _e('Mandato:', 'agert'); ?></span> <?php echo esc_html($mandato); ?></p>
                    <?php endif; ?>
                    <?php if ($formacao) : ?>
                        <p><span class="label-muted"><?php _e('Formação:', 'agert'); ?></span> <?php echo esc_html($formacao); ?></p>
                    <?php endif; ?>
                    <?php if ($especializacao) : ?>
                        <p><span class="label-muted"><?php _e('Especialização:', 'agert'); ?></span> <?php echo esc_html($especializacao); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($has_details) : ?>
            <div class="col-lg-8">
                <?php if ($bio_breve) : ?>
                <div class="card-soft p-4 mb-4">
                    <h2 class="card-title-sm mb-3"><i class="bi bi-person-circle me-2" aria-hidden="true"></i><?php _e('Biografia', 'agert'); ?></h2>
                    <p><?php echo esc_html($bio_breve); ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($experiencias) && is_array($experiencias)) : ?>
                <div class="card-soft p-4 mb-4">
                    <h2 class="card-title-sm mb-3"><i class="bi bi-briefcase me-2" aria-hidden="true"></i><?php _e('Experiência Profissional', 'agert'); ?></h2>
                    <?php foreach ($experiencias as $exp) :
                        $cargo  = $exp['cargo'] ?? '';
                        $orgao  = $exp['orgao'] ?? '';
                        $periodo = $exp['periodo'] ?? '';
                        if (!$cargo && !$orgao && !$periodo) {
                            continue;
                        }
                    ?>
                        <div class="item-row">
                            <div class="item-head">
                                <?php if ($cargo) : ?><span class="item-title"><?php echo esc_html($cargo); ?></span><?php endif; ?>
                                <?php if ($periodo) : ?><span class="label-muted"><?php echo esc_html($periodo); ?></span><?php endif; ?>
                            </div>
                            <?php if ($orgao) : ?><div class="item-sub"><?php echo esc_html($orgao); ?></div><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($formacoes) && is_array($formacoes)) : ?>
                <div class="card-soft p-4 mb-4">
                    <h2 class="card-title-sm mb-3"><i class="bi bi-mortarboard me-2" aria-hidden="true"></i><?php _e('Formação Acadêmica', 'agert'); ?></h2>
                    <?php foreach ($formacoes as $form) :
                        $curso = $form['curso'] ?? '';
                        $instituicao = $form['instituicao'] ?? '';
                        $ano = $form['ano'] ?? '';
                        if (!$curso && !$instituicao && !$ano) {
                            continue;
                        }
                    ?>
                        <div class="item-row">
                            <div class="item-head">
                                <?php if ($curso) : ?><span class="item-title"><?php echo esc_html($curso); ?></span><?php endif; ?>
                                <?php if ($ano) : ?><span class="label-muted"><?php echo esc_html($ano); ?></span><?php endif; ?>
                            </div>
                            <?php if ($instituicao) : ?><div class="item-sub"><?php echo esc_html($instituicao); ?></div><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($mensagem) : ?>
                <div class="card-soft p-4">
                    <h2 class="card-title-sm mb-3"><i class="bi bi-chat-quote me-2" aria-hidden="true"></i><?php _e('Mensagem do Presidente', 'agert'); ?></h2>
                    <blockquote class="presidente-quote mb-0"><?php echo wp_kses_post($mensagem); ?></blockquote>
                    <?php if ($assinatura_nome || $assinatura_cargo) : ?>
                        <div class="signature">
                            <?php if ($assinatura_nome) : ?><div class="name"><?php echo esc_html($assinatura_nome); ?></div><?php endif; ?>
                            <?php if ($assinatura_cargo) : ?><div class="role"><?php echo esc_html($assinatura_cargo); ?></div><?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
        endwhile;
endif;

get_footer();
