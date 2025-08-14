<?php
/**
 * Template para resultados de busca
 * 
 * @package AGERT
 */

get_header(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <header class="page-header mb-5">
                <h1 class="page-title">
                    <i class="bi bi-search me-2 text-primary"></i>
                    <?php
                    printf(
                        __('Resultados da busca por: %s', 'agert'),
                        '<span class="text-primary">"' . get_search_query() . '"</span>'
                    );
                    ?>
                </h1>
                
                <?php if (have_posts()) : ?>
                    <p class="text-muted">
                        <?php
                        global $wp_query;
                        printf(
                            _n(
                                'Encontrado %d resultado',
                                'Encontrados %d resultados',
                                $wp_query->found_posts,
                                'agert'
                            ),
                            $wp_query->found_posts
                        );
                        ?>
                    </p>
                <?php endif; ?>
            </header>

            <?php if (have_posts()) : ?>
                
                <div class="search-results">
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('card mb-4'); ?>>
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <i class="bi bi-<?php echo esc_attr(agert_get_post_type_icon(get_post_type())); ?> text-primary fs-4"></i>
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <header class="entry-header">
                                            <h3 class="card-title h5 mb-2">
                                                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <div class="entry-meta text-muted small mb-3">
                                                <span class="post-type badge bg-secondary me-2">
                                                    <?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>
                                                </span>
                                                
                                                <span class="posted-on">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    <time datetime="<?php echo get_the_date('c'); ?>">
                                                        <?php echo get_the_date('d/m/Y'); ?>
                                                    </time>
                                                </span>
                                                
                                                <?php if (get_post_type() !== 'page') : ?>
                                                    <span class="byline ms-3">
                                                        <i class="bi bi-person me-1"></i>
                                                        <?php the_author(); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </header>

                                        <div class="entry-content">
                                            <?php
                                            // Mostrar excerpt com destaque dos termos de busca
                                            $excerpt = get_the_excerpt();
                                            $search_query = get_search_query();
                                            
                                            if ($search_query && $excerpt) {
                                                $highlighted_excerpt = preg_replace(
                                                    '/(' . preg_quote($search_query, '/') . ')/i',
                                                    '<mark class="bg-warning">$1</mark>',
                                                    $excerpt
                                                );
                                                echo $highlighted_excerpt;
                                            } else {
                                                echo $excerpt;
                                            }
                                            ?>
                                        </div>

                                        <?php
                                        // Informações específicas por tipo de post
                                        if (get_post_type() === 'reuniao') :
                                            $data_hora = get_post_meta(get_the_ID(), '_data_hora', true);
                                            $local = get_post_meta(get_the_ID(), '_local', true);
                                            
                                            if ($data_hora || $local) :
                                        ?>
                                            <div class="meta-info mt-2 p-2 bg-light rounded-2">
                                                <?php if ($data_hora) : ?>
                                                    <small class="d-block text-muted">
                                                        <i class="bi bi-calendar-event me-1"></i>
                                                        <?php echo agert_format_datetime($data_hora, 'd/m/Y H:i'); ?>
                                                    </small>
                                                <?php endif; ?>
                                                
                                                <?php if ($local) : ?>
                                                    <small class="d-block text-muted">
                                                        <i class="bi bi-geo-alt me-1"></i>
                                                        <?php echo esc_html($local); ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        <?php
                                            endif;
                                        elseif (get_post_type() === 'anexo') :
                                            $reuniao_id = get_post_meta(get_the_ID(), '_reuniao_id', true);
                                            if ($reuniao_id) :
                                                $reuniao = get_post($reuniao_id);
                                                if ($reuniao) :
                                        ?>
                                            <div class="meta-info mt-2 p-2 bg-light rounded-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-link-45deg me-1"></i>
                                                    <?php _e('Relacionado à reunião:', 'agert'); ?>
                                                    <a href="<?php echo get_permalink($reuniao_id); ?>" class="text-decoration-none">
                                                        <?php echo esc_html($reuniao->post_title); ?>
                                                    </a>
                                                </small>
                                            </div>
                                        <?php
                                                endif;
                                            endif;
                                        elseif (get_post_type() === 'participante') :
                                            $nome_participante = get_post_meta(get_the_ID(), '_nome_participante', true);
                                            $cargo = get_post_meta(get_the_ID(), '_cargo', true);
                                            
                                            if ($nome_participante || $cargo) :
                                        ?>
                                            <div class="meta-info mt-2 p-2 bg-light rounded-2">
                                                <?php if ($nome_participante) : ?>
                                                    <small class="d-block text-muted">
                                                        <i class="bi bi-person me-1"></i>
                                                        <?php echo esc_html($nome_participante); ?>
                                                    </small>
                                                <?php endif; ?>
                                                
                                                <?php if ($cargo) : ?>
                                                    <small class="d-block text-muted">
                                                        <i class="bi bi-briefcase me-1"></i>
                                                        <?php echo esc_html($cargo); ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; endif; ?>

                                        <footer class="entry-footer mt-3">
                                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                                <?php _e('Ver detalhes', 'agert'); ?>
                                                <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        </footer>
                                    </div>
                                </div>
                            </div>
                        </article>

                    <?php endwhile; ?>
                </div>

                <?php
                // Paginação
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '<i class="bi bi-chevron-left"></i> ' . __('Anterior', 'agert'),
                    'next_text' => __('Próxima', 'agert') . ' <i class="bi bi-chevron-right"></i>',
                    'class' => 'pagination justify-content-center mt-5'
                ));
                ?>

            <?php else : ?>
                
                <div class="no-results text-center py-5">
                    <i class="bi bi-search display-1 text-muted mb-4"></i>
                    <h2 class="mb-3"><?php _e('Nenhum resultado encontrado', 'agert'); ?></h2>
                    <p class="text-muted mb-4">
                        <?php _e('Não foi possível encontrar resultados para sua busca. Tente novamente com palavras diferentes.', 'agert'); ?>
                    </p>
                    
                    <!-- Formulário de busca -->
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="d-flex">
                                <input type="search" 
                                       class="form-control me-2" 
                                       placeholder="<?php _e('Digite sua pesquisa...', 'agert'); ?>" 
                                       value="<?php echo get_search_query(); ?>" 
                                       name="s" 
                                       title="<?php _e('Buscar por:', 'agert'); ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                    <span class="d-none d-sm-inline ms-1"><?php _e('Buscar', 'agert'); ?></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Sugestões de busca -->
                    <div class="mt-4">
                        <h6 class="text-muted"><?php _e('Sugestões:', 'agert'); ?></h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-2">
                            <a href="<?php echo esc_url(home_url('?s=reunião')); ?>" class="btn btn-outline-secondary btn-sm">reunião</a>
                            <a href="<?php echo esc_url(home_url('?s=presidente')); ?>" class="btn btn-outline-secondary btn-sm">presidente</a>
                            <a href="<?php echo esc_url(home_url('?s=regulação')); ?>" class="btn btn-outline-secondary btn-sm">regulação</a>
                            <a href="<?php echo esc_url(home_url('?s=serviços')); ?>" class="btn btn-outline-secondary btn-sm">serviços</a>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <aside class="sidebar">
                <!-- Widget de nova busca -->
                <div class="widget card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-search me-2"></i>
                            <?php _e('Refinar Busca', 'agert'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="mb-3">
                                <input type="search" 
                                       class="form-control" 
                                       placeholder="<?php _e('Digite sua pesquisa...', 'agert'); ?>" 
                                       value="<?php echo get_search_query(); ?>" 
                                       name="s">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>
                                <?php _e('Buscar Novamente', 'agert'); ?>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Widget de filtros por tipo -->
                <div class="widget card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-funnel me-2"></i>
                            <?php _e('Filtrar por Tipo', 'agert'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php
                            $search_url = add_query_arg('s', get_search_query(), home_url('/'));
                            
                            $post_types = array(
                                'post' => array('name' => __('Posts', 'agert'), 'icon' => 'bi-newspaper'),
                                'page' => array('name' => __('Páginas', 'agert'), 'icon' => 'bi-file-text'),
                                'reuniao' => array('name' => __('Reuniões', 'agert'), 'icon' => 'bi-calendar-event'),
                                'anexo' => array('name' => __('Anexos', 'agert'), 'icon' => 'bi-paperclip'),
                                'participante' => array('name' => __('Participantes', 'agert'), 'icon' => 'bi-person')
                            );
                            
                            foreach ($post_types as $post_type => $data) :
                                $filter_url = add_query_arg('post_type', $post_type, $search_url);
                            ?>
                                <a href="<?php echo esc_url($filter_url); ?>" 
                                   class="btn btn-outline-primary btn-sm text-start">
                                    <i class="<?php echo esc_attr($data['icon']); ?> me-2"></i>
                                    <?php echo esc_html($data['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Widget de links úteis -->
                <div class="widget card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-links me-2"></i>
                            <?php _e('Links Úteis', 'agert'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-calendar-event me-2"></i>
                                <?php _e('Todas as Reuniões', 'agert'); ?>
                            </a>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('sobre'))); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-info-circle me-2"></i>
                                <?php _e('Sobre a AGERT', 'agert'); ?>
                            </a>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contato'))); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-envelope me-2"></i>
                                <?php _e('Contato', 'agert'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>