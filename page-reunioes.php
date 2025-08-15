<?php
/**
 * Template da página de Reuniões
 *
 * @package AGERT
 */

get_header();
?>

<div class="container py-5 page-reunioes">
    <?php agert_show_status_message(); ?>

    <h1 class="mb-4"><i class="bi bi-calendar-event text-primary me-2"></i><?php _e('Reuniões', 'agert'); ?></h1>

    <nav class="tabbar mb-4" aria-label="<?php esc_attr_e('Seções de reuniões', 'agert'); ?>">
        <div class="nav nav-tabs" id="reunioesTabs" role="tablist">
            <button class="nav-link active" id="reunioes-tab" data-bs-toggle="tab" data-bs-target="#reunioes-pane" type="button" role="tab" aria-controls="reunioes-pane" aria-selected="true"><?php _e('Reuniões', 'agert'); ?></button>
            <button class="nav-link" id="anexos-tab" data-bs-toggle="tab" data-bs-target="#anexos-pane" type="button" role="tab" aria-controls="anexos-pane" aria-selected="false"><?php _e('Anexos', 'agert'); ?></button>
            <button class="nav-link" id="videos-tab" data-bs-toggle="tab" data-bs-target="#videos-pane" type="button" role="tab" aria-controls="videos-pane" aria-selected="false"><?php _e('Vídeos', 'agert'); ?></button>
        </div>
    </nav>

    <div class="tab-content" id="reunioesTabsContent">

        <div class="tab-pane fade show active" id="reunioes-pane" role="tabpanel" aria-labelledby="reunioes-tab">
            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $meetings = new WP_Query(array(
                'post_type'      => 'reuniao',
                'posts_per_page' => 9,
                'paged'          => $paged,
                'post_status'    => 'publish',
                'orderby'        => 'meta_value',
                'meta_key'       => '_data_hora',
                'order'          => 'DESC',
            ));

            if ($meetings->have_posts()) : ?>
                <div class="row g-4">
                    <?php while ($meetings->have_posts()) : $meetings->the_post(); ?>
                        <div class="col-md-4">
                            <?php get_template_part('parts/reunioes/card-reuniao'); ?>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php if ($meetings->max_num_pages > 1) : ?>
                    <div class="mt-4">
                        <?php
                        echo paginate_links(array(
                            'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format'    => '?paged=%#%',
                            'current'   => max(1, $paged),
                            'total'     => $meetings->max_num_pages,
                            'type'      => 'list',
                            'prev_text' => '<i class="bi bi-chevron-left"></i> ' . __('Anterior', 'agert'),
                            'next_text' => __('Próxima', 'agert') . ' <i class="bi bi-chevron-right"></i>',
                        ));
                        ?>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <?php get_template_part('parts/reunioes/empty-state'); ?>
            <?php endif; wp_reset_postdata(); ?>
        </div>

        <div class="tab-pane fade" id="anexos-pane" role="tabpanel" aria-labelledby="anexos-tab">
            <?php
            $attachments = new WP_Query(array(
                'post_type'      => 'anexo',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ));

            if ($attachments->have_posts()) :
                while ($attachments->have_posts()) : $attachments->the_post();
                    $reuniao_id = get_post_meta(get_the_ID(), '_reuniao_id', true);
                    $meeting    = $reuniao_id ? get_post($reuniao_id) : null;
                    $doc        = array(
                        'rotulo'      => get_the_title(),
                        'resumo'      => get_the_excerpt(),
                        'arquivo_url' => get_post_meta(get_the_ID(), '_arquivo', true),
                    );
                    get_template_part('parts/reunioes/row-documento', null, array('doc' => $doc, 'meeting' => $meeting));
                endwhile;
            else :
                get_template_part('parts/reunioes/empty-state');
            endif;
            wp_reset_postdata();
            ?>
        </div>

        <div class="tab-pane fade" id="videos-pane" role="tabpanel" aria-labelledby="videos-tab">
            <?php
            $videos_query = new WP_Query(array(
                'post_type'      => 'reuniao',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'meta_query'     => array(
                    array(
                        'key'     => 'videos',
                        'compare' => 'EXISTS',
                    ),
                ),
            ));

            if ($videos_query->have_posts()) :
                echo '<div class="row g-4">';
                while ($videos_query->have_posts()) : $videos_query->the_post();
                    $videos = get_post_meta(get_the_ID(), 'videos', true);
                    if (is_array($videos)) {
                        foreach ($videos as $video) {
                            echo '<div class="col-md-4">';
                            get_template_part('parts/reunioes/card-video', null, array(
                                'meeting' => get_post(),
                                'video'   => $video,
                            ));
                            echo '</div>';
                        }
                    }
                endwhile;
                echo '</div>';
            else :
                get_template_part('parts/reunioes/empty-state');
            endif;
            wp_reset_postdata();
            ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>

