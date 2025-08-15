<?php
/**
 * Template Name: Vídeos
 * Description: Página que lista os vídeos das reuniões.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$paged = get_query_var('paged') ? (int) get_query_var('paged') : 1;
$params = array('paged' => $paged);
$videos_data = agert_coletar_videos($params);
$per_page = 9;
$videos_slice = array_slice($videos_data['results'], ($paged - 1) * $per_page, $per_page);
$total_pages = max(1, (int) ceil($videos_data['total'] / $per_page));
?>

<div class="container py-5">
    <h1 class="text-center mb-4"><?php _e('Vídeos', 'agert'); ?></h1>
    <?php if ($videos_slice) : ?>
        <div class="row g-4">
            <?php foreach ($videos_slice as $item) :
                $video = $item['video'];
                $meeting = $item['meeting'];
            ?>
                <div class="col-sm-6 col-lg-4">
                    <?php get_template_part('parts/reunioes/card-video', null, array('video' => $video, 'meeting' => $meeting)); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($total_pages > 1) : ?>
            <nav class="mt-4">
                <?php echo paginate_links(array(
                    'current' => $paged,
                    'total'   => $total_pages,
                )); ?>
            </nav>
        <?php endif; ?>
    <?php else : ?>
        <p class="text-center text-muted"><?php _e('Nenhum vídeo encontrado.', 'agert'); ?></p>
    <?php endif; ?>
</div>

<?php
get_footer();
