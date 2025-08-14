<?php
/**
 * Template para páginas
 * 
 * @package AGERT
 */

get_header(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header text-center mb-5">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-image mb-4">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        
                        <?php if (get_the_excerpt()) : ?>
                            <div class="entry-excerpt text-muted lead mt-3">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
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

                    <?php if (get_edit_post_link()) : ?>
                        <footer class="entry-footer mt-5 pt-4 border-top">
                            <div class="edit-link">
                                <?php
                                edit_post_link(
                                    sprintf(
                                        '<i class="bi bi-pencil me-2"></i>%s',
                                        __('Editar página', 'agert')
                                    ),
                                    '<span class="edit-link">',
                                    '</span>',
                                    null,
                                    'btn btn-outline-secondary btn-sm'
                                );
                                ?>
                            </div>
                        </footer>
                    <?php endif; ?>
                </article>

                <?php
                // Se os comentários estão abertos ou temos pelo menos um comentário, carregue o template de comentários
                if (comments_open() || get_comments_number()) {
                    echo '<div class="mt-5">';
                    comments_template();
                    echo '</div>';
                }
                ?>

            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>