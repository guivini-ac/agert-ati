<?php
/**
 * Template da página inicial
 * 
 * @package AGERT
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8">
                <h1 class="display-4 mb-4">AGERT</h1>
                <p class="lead mb-4">
                    <?php _e('Agência Reguladora de Serviços Públicos Delegados do Município de Timon', 'agert'); ?>
                </p>
                <p class="mb-4">
                    <?php _e('Garantindo a qualidade e eficiência dos serviços públicos delegados, com transparência e compromisso com o cidadão timonense.', 'agert'); ?>
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('sobre'))); ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-info-circle me-2"></i>
                        <?php _e('Conheça a AGERT', 'agert'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-calendar-event me-2"></i>
                        <?php _e('Ver Reuniões', 'agert'); ?>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-3 p-4">
                    <i class="bi bi-building display-1 text-white opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Acesso Rápido -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2><?php _e('Acesso Rápido', 'agert'); ?></h2>
                <p class="text-muted"><?php _e('Principais funcionalidades do sistema', 'agert'); ?></p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php
            $quick_access_items = array(
                array(
                    'icon' => 'bi-info-circle',
                    'title' => __('Sobre a AGERT', 'agert'),
                    'description' => __('Conheça nossa missão, visão e estrutura organizacional', 'agert'),
                    'link' => get_permalink(get_page_by_path('sobre'))
                ),
                array(
                    'icon' => 'bi-calendar-event',
                    'title' => __('Reuniões', 'agert'),
                    'description' => __('Acompanhe e gerencie as reuniões da agência', 'agert'),
                    'link' => get_permalink(get_page_by_path('reunioes'))
                ),
                array(
                    'icon' => 'bi-person-badge',
                    'title' => __('Presidente', 'agert'),
                    'description' => __('Conheça o presidente da AGERT e sua trajetória', 'agert'),
                    'link' => get_permalink(get_page_by_path('presidente'))
                ),
                array(
                    'icon' => 'bi-envelope',
                    'title' => __('Contato', 'agert'),
                    'description' => __('Entre em contato através dos canais oficiais', 'agert'),
                    'link' => get_permalink(get_page_by_path('contato'))
                )
            );
            
            foreach ($quick_access_items as $item) :
            ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 quick-access-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="<?php echo esc_attr($item['icon']); ?> display-4 text-primary"></i>
                            </div>
                            <h5 class="card-title"><?php echo esc_html($item['title']); ?></h5>
                            <p class="card-text text-muted"><?php echo esc_html($item['description']); ?></p>
                            <a href="<?php echo esc_url($item['link']); ?>" class="btn btn-outline-primary">
                                <?php _e('Acessar', 'agert'); ?>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Últimas Reuniões -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h2><?php _e('Últimas Reuniões', 'agert'); ?></h2>
                <p class="text-muted mb-0"><?php _e('Acompanhe as reuniões mais recentes', 'agert'); ?></p>
            </div>
            <div class="col-md-4 text-end">
                <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="btn btn-primary">
                    <?php _e('Ver Todas', 'agert'); ?>
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        
        <div class="row g-4">
            <?php
            $recent_meetings = get_posts(array(
                'post_type' => 'reuniao',
                'posts_per_page' => 3,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            if ($recent_meetings) :
                foreach ($recent_meetings as $meeting) :
                    $data_hora = get_post_meta($meeting->ID, '_data_hora', true);
                    $local = get_post_meta($meeting->ID, '_local', true);
                    $status_class = agert_get_meeting_status_class($meeting->ID);
                    $status_text = agert_get_meeting_status_text($meeting->ID);
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 meeting-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0"><?php echo esc_html($meeting->post_title); ?></h6>
                                <span class="badge bg-light text-dark <?php echo esc_attr($status_class); ?>">
                                    <?php echo esc_html($status_text); ?>
                                </span>
                            </div>
                            
                            <?php if (!empty($data_hora)) : ?>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?php echo esc_html(agert_format_datetime($data_hora, 'd/m/Y H:i')); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if (!empty($local)) : ?>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    <?php echo esc_html($local); ?>
                                </p>
                            <?php endif; ?>
                            
                            <p class="card-text">
                                <?php echo esc_html(agert_truncate_text(get_the_excerpt($meeting), 100)); ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <?php
                                    $anexos_count = count(agert_get_meeting_attachments($meeting->ID));
                                    $participantes_count = count(agert_get_meeting_participants($meeting->ID));
                                    
                                    echo sprintf(
                                        __('%d anexos, %d participantes', 'agert'),
                                        $anexos_count,
                                        $participantes_count
                                    );
                                    ?>
                                </small>
                                <a href="<?php echo get_permalink($meeting->ID); ?>" class="btn btn-sm btn-outline-primary">
                                    <?php _e('Ver detalhes', 'agert'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                endforeach;
            else :
            ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-calendar-x display-1 text-muted mb-3"></i>
                            <h5><?php _e('Nenhuma reunião encontrada', 'agert'); ?></h5>
                            <p class="text-muted"><?php _e('Ainda não há reuniões cadastradas no sistema.', 'agert'); ?></p>
                            <?php if (agert_user_can_create_posts()) : ?>
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('reunioes'))); ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    <?php _e('Criar primeira reunião', 'agert'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3><?php _e('Transparência e Eficiência', 'agert'); ?></h3>
                <p class="lead text-muted mb-4">
                    <?php _e('A AGERT trabalha com transparência total, disponibilizando todas as informações sobre reuniões, decisões e atividades para a população timonense.', 'agert'); ?>
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="btn btn-primary">
                        <i class="bi bi-eye me-2"></i>
                        <?php _e('Ver todas as reuniões', 'agert'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contato'))); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>
                        <?php _e('Entre em contato', 'agert'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>