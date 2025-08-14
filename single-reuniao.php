<?php
/**
 * Template para exibição de uma reunião individual.
 *
 * @package AGERT
 */

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        $id = get_the_ID();

        $resumo        = agert_meta($id, 'resumo');
        $videos        = agert_meta($id, 'videos', array());
        $data_hora     = agert_meta($id, 'data_hora');
        $duracao       = agert_meta($id, 'duracao_minutos');
        $local         = agert_meta($id, 'local');
        $pauta         = agert_meta($id, 'pauta', array());
        $decisoes      = agert_meta($id, 'decisoes', array());
        $participantes = agert_meta($id, 'participantes', array());
        $documentos    = agert_meta($id, 'documentos', array());
        $transmitido   = agert_meta($id, 'transmitido_em', $data_hora);
        if (!$resumo) {
            $resumo = get_the_excerpt();
        }

        $tipo_reuniao = '';
        $terms = get_the_terms($id, 'tipo_reuniao');
        if ($terms && !is_wp_error($terms)) {
            $tipo_reuniao = $terms[0]->name;
        } else {
            $tipo_reuniao = agert_meta($id, 'tipo_reuniao');
        }
        ?>

        <div class="container py-4">
            <div class="d-flex align-items-center mb-3">
                <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="btn btn-outline-brand btn-sm me-3" aria-label="Voltar para Reuniões" title="Voltar para Reuniões">
                    <i class="bi bi-arrow-left"></i> Voltar para Reuniões
                </a>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>">Reuniões</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
                    </ol>
                </nav>
            </div>

            <header class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="mb-2"><?php the_title(); ?></h1>
                    <ul class="list-inline text-muted small mb-0">
                        <?php if ($data_hora) : ?>
                            <li class="list-inline-item me-3"><i class="bi bi-calendar-event me-1"></i><?php echo esc_html(date_i18n('d/m/Y \à\s H:i', strtotime($data_hora))); ?></li>
                        <?php endif; ?>
                        <?php if ($duracao) : ?>
                            <li class="list-inline-item me-3"><i class="bi bi-clock me-1"></i><?php echo esc_html(agert_minutes_to_human((int) $duracao)); ?></li>
                        <?php endif; ?>
                        <?php if ($local) : ?>
                            <li class="list-inline-item"><i class="bi bi-geo-alt me-1"></i><?php echo esc_html($local); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php if ($tipo_reuniao) : ?>
                    <span class="badge-chip"><?php echo esc_html($tipo_reuniao); ?></span>
                <?php endif; ?>
            </header>

            <div class="row g-4">
                <div class="col-lg-8">
                    <?php if ($resumo) : ?>
                        <div class="card card-soft mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Sobre a Reunião</h6>
                                <p class="mb-0"><?php echo wp_kses_post($resumo); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($videos) && is_array($videos)) : ?>
                        <div class="card card-soft mb-4" id="transmissao">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Transmissão/Vídeos</h6>
                                <?php foreach ($videos as $vid) :
                                    $url = $vid['video_url'] ?? '';
                                    if (!$url) { continue; }
                                    $embed = wp_oembed_get($url);
                                    if (!$embed) { continue; }
                                    $embed = preg_replace('/<iframe /', '<iframe class="rounded" ', $embed);
                                    ?>
                                    <div class="ratio ratio-16x9 mb-3">
                                        <?php echo $embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </div>
                                    <?php if (!empty($vid['descricao']) || !empty($vid['duracao_segundos'])) : ?>
                                        <p class="text-muted small mb-3">
                                            <?php if (!empty($vid['duracao_segundos'])) : ?><?php echo esc_html(agert_seconds_to_mmss((int) $vid['duracao_segundos'])); ?><?php endif; ?>
                                            <?php if (!empty($vid['descricao'])) : ?><?php echo esc_html($vid['descricao']); ?><?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($pauta) && is_array($pauta)) : ?>
                        <div class="card card-soft mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Pauta da Reunião</h6>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($pauta as $item) : ?>
                                        <li class="list-dot mb-1"><?php echo esc_html($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($decisoes) && is_array($decisoes)) : ?>
                        <div class="card card-soft mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Principais Decisões</h6>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($decisoes as $item) : ?>
                                        <li class="d-flex align-items-start mb-2"><i class="bi bi-check-circle-fill text-success me-2 mt-1"></i><span><?php echo esc_html($item); ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <aside class="col-lg-4">
                    <?php if (!empty($participantes) && is_array($participantes)) : ?>
                        <div class="card card-soft mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Participantes</h6>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($participantes as $p) :
                                        $nome  = $p['nome'] ?? '';
                                        $cargo = $p['cargo'] ?? '';
                                        if (!$nome) {
                                            continue;
                                        }
                                        ?>
                                        <li class="py-2 border-bottom">
                                            <strong><?php echo esc_html($nome); ?></strong>
                                            <?php if ($cargo) : ?> – <span class="text-muted"><?php echo esc_html($cargo); ?></span><?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($documentos) && is_array($documentos)) : ?>
                        <div class="card card-soft mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Documentos</h6>
                                <div class="d-flex flex-column gap-2">
                                    <?php foreach ($documentos as $doc) :
                                        $rotulo   = $doc['rotulo'] ?? '';
                                        $file_url = '';
                                        $file_name = '';
                                        $size     = $doc['tamanho_bytes'] ?? '';
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
                                            continue;
                                        }
                                        if (!$file_name) {
                                            $file_name = basename($file_url);
                                        }
                                        $size_h = $size ? agert_bytes_to_human((int) $size) : '';
                                        $same_domain = strpos($file_url, home_url()) === 0;
                                        ?>
                                        <div class="doc-row">
                                            <?php if ($rotulo) : ?>
                                                <span class="badge-chip"><?php echo esc_html($rotulo); ?></span>
                                            <?php endif; ?>
                                            <span><?php echo esc_html($file_name); ?></span>
                                            <?php if ($size_h) : ?><span class="doc-size"><?php echo esc_html($size_h); ?></span><?php endif; ?>
                                            <a class="btn btn-brand btn-sm ms-2" href="<?php echo esc_url($file_url); ?>" <?php echo $same_domain ? 'download' : 'target="_blank" rel="noopener noreferrer"'; ?> aria-label="Download <?php echo esc_attr($file_name); ?>" title="Download <?php echo esc_attr($file_name); ?>">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="card card-soft">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Informações</h6>
                            <ul class="list-unstyled small mb-0">
                                <?php if ($data_hora) : ?>
                                    <li class="mb-2"><span class="text-muted d-block">Data e hora</span><span class="fw-semibold"><?php echo esc_html(date_i18n('d/m/Y \à\s H:i', strtotime($data_hora))); ?></span></li>
                                <?php endif; ?>
                                <?php if ($local) : ?>
                                    <li class="mb-2"><span class="text-muted d-block">Local</span><span class="fw-semibold"><?php echo esc_html($local); ?></span></li>
                                <?php endif; ?>
                                <?php if ($duracao) : ?>
                                    <li class="mb-2"><span class="text-muted d-block">Duração</span><span class="fw-semibold"><?php echo esc_html(agert_minutes_to_human((int) $duracao)); ?></span></li>
                                <?php endif; ?>
                                <?php if ($tipo_reuniao) : ?>
                                    <li class="mb-0"><span class="text-muted d-block">Tipo</span><span class="fw-semibold"><?php echo esc_html($tipo_reuniao); ?></span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
        <?php
    endwhile;
endif;

get_footer();
