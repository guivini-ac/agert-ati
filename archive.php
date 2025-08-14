<?php
/**
 * Template padrão de arquivos.
 *
 * @package AGERT
 */

get_header(); ?>
<div class="container py-5">
    <h1 class="mb-4"><?php the_archive_title(); ?></h1>
    <?php if (have_posts()) : ?>
        <div class="row g-4">
            <?php while (have_posts()) : the_post(); ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?></a>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php the_title(); ?></h5>
                            <p class="card-text"><?php echo esc_html(get_the_excerpt()); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary"><?php _e('Ler mais', 'agert'); ?></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php the_posts_pagination(array(
            'prev_text' => __('Anterior', 'agert'),
            'next_text' => __('Próxima', 'agert'),
        )); ?>
    <?php else : ?>
        <p><?php _e('Nenhum conteúdo encontrado.', 'agert'); ?></p>
    <?php endif; ?>
</div>
<?php get_footer();
