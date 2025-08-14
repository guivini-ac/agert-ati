<?php
/**
 * Template para posts individuais
 * 
 * @package AGERT
 */

get_header(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
                    <header class="entry-header mb-4">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-image mb-4">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        
                        <div class="entry-meta text-muted small mt-3">
                            <span class="posted-on">
                                <i class="bi bi-calendar3 me-1"></i>
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date('d/m/Y \à\s H:i'); ?>
                                </time>
                            </span>
                            
                            <span class="byline ms-3">
                                <i class="bi bi-person me-1"></i>
                                <?php the_author(); ?>
                            </span>
                            
                            <?php if (has_category()) : ?>
                                <span class="cat-links ms-3">
                                    <i class="bi bi-folder me-1"></i>
                                    <?php the_category(', '); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (has_tag()) : ?>
                                <span class="tags-links ms-3">
                                    <i class="bi bi-tags me-1"></i>
                                    <?php the_tags('', ', ', ''); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links mt-4"><span class="page-links-title">' . __('Páginas:', 'agert') . '</span>',
                            'after'  => '</div>',
                            'link_before' => '<span class="page-link">',
                            'link_after'  => '</span>',
                        ));
                        ?>
                    </div>

                    <footer class="entry-footer mt-4 pt-4 border-top">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <?php if (get_edit_post_link()) : ?>
                                    <div class="edit-link">
                                        <?php
                                        edit_post_link(
                                            sprintf(
                                                '<i class="bi bi-pencil me-2"></i>%s',
                                                __('Editar post', 'agert')
                                            ),
                                            '<span class="edit-link">',
                                            '</span>',
                                            null,
                                            'btn btn-outline-secondary btn-sm'
                                        );
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 text-end">
                                <!-- Botões de compartilhamento social -->
                                <div class="social-share">
                                    <span class="me-2 text-muted small"><?php _e('Compartilhar:', 'agert'); ?></span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                       target="_blank" 
                                       class="btn btn-outline-primary btn-sm me-1"
                                       title="<?php _e('Compartilhar no Facebook', 'agert'); ?>">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                       target="_blank" 
                                       class="btn btn-outline-info btn-sm me-1"
                                       title="<?php _e('Compartilhar no Twitter', 'agert'); ?>">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                    <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                                       target="_blank" 
                                       class="btn btn-outline-success btn-sm"
                                       title="<?php _e('Compartilhar no WhatsApp', 'agert'); ?>">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </footer>
                </article>

                <!-- Navegação entre posts -->
                <nav class="post-navigation mb-5" aria-label="<?php _e('Navegação entre posts', 'agert'); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $prev_post = get_previous_post();
                            if ($prev_post) : ?>
                                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="btn btn-outline-primary w-100 text-start">
                                    <i class="bi bi-chevron-left me-2"></i>
                                    <small class="d-block text-muted"><?php _e('Post anterior', 'agert'); ?></small>
                                    <span><?php echo esc_html($prev_post->post_title); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            $next_post = get_next_post();
                            if ($next_post) : ?>
                                <a href="<?php echo get_permalink($next_post->ID); ?>" class="btn btn-outline-primary w-100 text-end">
                                    <small class="d-block text-muted"><?php _e('Próximo post', 'agert'); ?></small>
                                    <span><?php echo esc_html($next_post->post_title); ?></span>
                                    <i class="bi bi-chevron-right ms-2"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>

                <?php
                // Comentários
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
                ?>

            <?php endwhile; ?>
        </div>

        <div class="col-lg-4">
            <aside class="sidebar">
                <!-- Widget de posts relacionados -->
                <?php
                $related_posts = get_posts(array(
                    'category__in' => wp_get_post_categories(get_the_ID()),
                    'numberposts' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'orderby' => 'rand'
                ));

                if ($related_posts) : ?>
                    <div class="widget card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-bookmark me-2"></i>
                                <?php _e('Posts Relacionados', 'agert'); ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($related_posts as $post) : setup_postdata($post); ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <h6 class="mb-1">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo get_the_date('d/m/Y'); ?>
                                    </small>
                                </div>
                            <?php endforeach; wp_reset_postdata(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Widget de categorias -->
                <div class="widget card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-folder me-2"></i>
                            <?php _e('Categorias', 'agert'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <?php
                            wp_list_categories(array(
                                'show_count' => true,
                                'title_li' => '',
                                'style' => 'none',
                                'separator' => '',
                                'walker' => new class extends Walker_Category {
                                    function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
                                        $output .= '<li class="mb-2">';
                                        $output .= '<a href="' . get_category_link($category->term_id) . '" class="text-decoration-none d-flex justify-content-between align-items-center">';
                                        $output .= '<span><i class="bi bi-arrow-right me-2"></i>' . $category->name . '</span>';
                                        if ($args['show_count']) {
                                            $output .= '<span class="badge bg-secondary">' . $category->count . '</span>';
                                        }
                                        $output .= '</a>';
                                    }
                                    function end_el(&$output, $page, $depth = 0, $args = array()) {
                                        $output .= '</li>';
                                    }
                                }
                            ));
                            ?>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>