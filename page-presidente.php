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
        $nome             = agert_meta($post_id, 'nome_presidente', 'Dr. João Carlos Silva Santos');
        $cargo_titulo     = agert_meta($post_id, 'cargo_titulo', 'Presidente da AGERT');
        $mandato          = agert_meta($post_id, 'mandato_periodo', '2020–2025');
        $formacao         = agert_meta($post_id, 'formacao', 'Direito');
        $especializacao   = agert_meta($post_id, 'especializacao', 'Administração Pública');
        $bio_breve        = agert_meta($post_id, 'bio_breve', __('Líder com vasta experiência no setor público e privado.', 'agert'));
        $experiencias     = agert_meta($post_id, 'experiencias', array(
            array('cargo' => 'Diretor Executivo', 'orgao' => 'Empresa XYZ', 'periodo' => '2015-2020'),
            array('cargo' => 'Conselheiro', 'orgao' => 'Órgão ABC', 'periodo' => '2012-2014'),
            array('cargo' => 'Professor', 'orgao' => 'Universidade Federal', 'periodo' => '2008-2012'),
            array('cargo' => 'Consultor', 'orgao' => 'Projetos Diversos', 'periodo' => '2000-2008'),
        ));
        $formacoes        = agert_meta($post_id, 'formacoes', array(
            array('curso' => 'Graduação em Direito', 'instituicao' => 'UFPR', 'ano' => '1995'),
            array('curso' => 'MBA em Gestão', 'instituicao' => 'FGV', 'ano' => '2000'),
            array('curso' => 'Especialização em Administração Pública', 'instituicao' => 'UFRGS', 'ano' => '2005'),
        ));
        $mensagem         = agert_meta($post_id, 'mensagem', __('Trabalhamos diariamente para fortalecer a radiodifusão e servir à sociedade gaúcha.', 'agert'));
        $assinatura_nome  = agert_meta($post_id, 'assinatura_nome', 'Dr. João Carlos Silva Santos');
        $assinatura_cargo = agert_meta($post_id, 'assinatura_cargo', 'Presidente da AGERT');

        $has_content = $nome || $cargo_titulo || $mandato || $formacao || $especializacao || $bio_breve || !empty($experiencias) || !empty($formacoes) || $mensagem || $assinatura_nome || $assinatura_cargo;
?>
<div class="container-lg py-5">
    <h1 class="text-center mb-5"><?php _e('Presidente', 'agert'); ?></h1>
    <div class="row g-4">
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
    </div>
    <?php if (!$has_content && current_user_can('manage_options')) : ?>
        <p class="text-center text-muted small mt-4"><?php _e('Preencha os campos da página do Presidente em Aparência › Campos (ACF) ou Meta.', 'agert'); ?></p>
    <?php endif; ?>
</div>
<?php
        endwhile;
endif;

get_footer();
