<?php
/**
 * Template padrão do tema
 * 
 * @package AGERT
 */

get_header(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <?php if (have_posts()) : ?>
                
                <header class="page-header mb-4">
                    <?php if (is_home() && !is_front_page()) : ?>
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    <?php elseif (is_search()) : ?>
                        <h1 class="page-title">
                            <i class="bi bi-search me-2"></i>
                            <?php printf(__('Resultados da busca por: %s', 'agert'), '<span>' . get_search_query() . '</span>'); ?>
                        </h1>
                    <?php elseif (is_archive()) : ?>
                        <h1 class="page-title">
                            <i class="bi bi-archive me-2"></i>
                            <?php the_archive_title(); ?>
                        </h1>
                        <?php the_archive_description('<div class="archive-description text-muted">', '</div>'); ?>
                    <?php endif; ?>
                </header>

                <div class="posts-container">
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('card mb-4'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="card-img-top">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <header class="entry-header">
                                    <h2 class="card-title h4">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <div class="entry-meta text-muted small mb-3">
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
                                        
                                        <?php if (get_post_type() !== 'page' && has_category()) : ?>
                                            <span class="cat-links ms-3">
                                                <i class="bi bi-folder me-1"></i>
                                                <?php the_category(', '); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </header>

                                <div class="entry-content">
                                    <?php
                                    if (is_singular()) {
                                        the_content();
                                    } else {
                                        the_excerpt();
                                    }
                                    ?>
                                </div>

                                <footer class="entry-footer">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                        <?php _e('Leia mais', 'agert'); ?>
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </footer>
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
                    'class' => 'pagination justify-content-center'
                ));
                ?>

            <?php else : ?>
                
                <div class="no-results text-center py-5">
                    <i class="bi bi-exclamation-circle display-1 text-muted mb-3"></i>
                    <h2><?php _e('Nada encontrado', 'agert'); ?></h2>
                    
                    <?php if (is_search()) : ?>
                        <p class="text-muted mb-4">
                            <?php _e('Não foi possível encontrar resultados para sua busca. Tente novamente com palavras diferentes.', 'agert'); ?>
                        </p>
                        <?php get_search_form(); ?>
                    <?php else : ?>
                        <p class="text-muted">
                            <?php _e('Parece que não conseguimos encontrar o que você está procurando.', 'agert'); ?>
                        </p>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <aside class="sidebar">
                <?php if (is_active_sidebar('sidebar-1')) : ?>
                    <?php dynamic_sidebar('sidebar-1'); ?>
                <?php else : ?>
                    
                    <!-- Widget de busca padrão -->
                    <div class="widget card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-search me-2"></i>
                                <?php _e('Buscar', 'agert'); ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php get_search_form(); ?>
                        </div>
                    </div>

                    <!-- Widget de links úteis -->
                    <div class="widget card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-links me-2"></i>
                                <?php _e('Links Úteis', 'agert'); ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo esc_url(agert_get_page_link('sobre')); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <?php _e('Sobre a AGERT', 'agert'); ?>
                                </a>
                                <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    <?php _e('Reuniões', 'agert'); ?>
                                </a>
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('contato'))); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-envelope me-2"></i>
                                    <?php _e('Contato', 'agert'); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>