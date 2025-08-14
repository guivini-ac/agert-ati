<?php
/**
 * Admin helpers for Reuniões CPT.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds metabox for Reunião videos.
 */
function agert_reuniao_videos_metabox() {
    add_meta_box(
        'agert_reuniao_videos',
        __('Vídeos', 'agert'),
        'agert_reuniao_videos_cb',
        'reuniao',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes_reuniao', 'agert_reuniao_videos_metabox');

function agert_reuniao_videos_cb($post) {
    wp_nonce_field('agert_reuniao_videos', 'agert_reuniao_videos_nonce');
    $videos = agert_meta($post->ID, 'videos', array());
    if (!is_array($videos)) {
        $videos = array();
    }
    ?>
    <table class="widefat fixed" id="agert-videos-table">
        <thead>
            <tr>
                <th><?php _e('Título', 'agert'); ?></th>
                <th><?php _e('URL do Vídeo', 'agert'); ?></th>
                <th><?php _e('Duração (seg)', 'agert'); ?></th>
                <th><?php _e('Descrição', 'agert'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($videos) : foreach ($videos as $i => $vid) : ?>
                <tr>
                    <td><input type="text" name="agert_videos[<?php echo $i; ?>][titulo]" value="<?php echo esc_attr($vid['titulo'] ?? ''); ?>" class="widefat" /></td>
                    <td><input type="url" name="agert_videos[<?php echo $i; ?>][video_url]" value="<?php echo esc_attr($vid['video_url'] ?? ''); ?>" class="widefat" /></td>
                    <td><input type="number" name="agert_videos[<?php echo $i; ?>][duracao_segundos]" value="<?php echo esc_attr($vid['duracao_segundos'] ?? ''); ?>" class="widefat" /></td>
                    <td><input type="text" name="agert_videos[<?php echo $i; ?>][descricao]" value="<?php echo esc_attr($vid['descricao'] ?? ''); ?>" class="widefat" /></td>
                    <td><button type="button" class="button agert-remove-video">&times;</button></td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
    <p><button type="button" class="button" id="agert-add-video"><?php _e('Adicionar vídeo', 'agert'); ?></button></p>
    <script>
    document.getElementById('agert-add-video').addEventListener('click', function(){
        var table = document.getElementById('agert-videos-table').getElementsByTagName('tbody')[0];
        var index = table.rows.length;
        var row = table.insertRow();
        row.innerHTML = '<td><input type="text" name="agert_videos['+index+'][titulo]" class="widefat"/></td>'+
                        '<td><input type="url" name="agert_videos['+index+'][video_url]" class="widefat"/></td>'+
                        '<td><input type="number" name="agert_videos['+index+'][duracao_segundos]" class="widefat"/></td>'+
                        '<td><input type="text" name="agert_videos['+index+'][descricao]" class="widefat"/></td>'+
                        '<td><button type="button" class="button agert-remove-video">&times;</button></td>';
    });
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('agert-remove-video')){
            e.target.closest('tr').remove();
        }
    });
    </script>
    <?php
}

function agert_save_reuniao_videos($post_id) {
    if (!isset($_POST['agert_reuniao_videos_nonce']) || !wp_verify_nonce($_POST['agert_reuniao_videos_nonce'], 'agert_reuniao_videos')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    $videos = $_POST['agert_videos'] ?? array();
    $clean  = array();
    if (is_array($videos)) {
        foreach ($videos as $v) {
            if (empty($v['video_url'])) {
                continue;
            }
            $clean[] = array(
                'titulo'            => sanitize_text_field($v['titulo'] ?? ''),
                'video_url'         => esc_url_raw($v['video_url']),
                'duracao_segundos'  => isset($v['duracao_segundos']) ? (int) $v['duracao_segundos'] : '',
                'descricao'        => sanitize_text_field($v['descricao'] ?? '')
            );
        }
    }
    update_post_meta($post_id, 'videos', $clean);
}
add_action('save_post_reuniao', 'agert_save_reuniao_videos');

/**
 * Adds admin columns for videos and attachments count.
 */
function agert_reuniao_columns($columns) {
    $columns['videos'] = __('Vídeos', 'agert');
    $columns['anexos'] = __('Anexos', 'agert');
    return $columns;
}
add_filter('manage_reuniao_posts_columns', 'agert_reuniao_columns');

function agert_reuniao_columns_content($column, $post_id) {
    if ($column === 'videos') {
        $videos = agert_meta($post_id, 'videos', array());
        echo is_array($videos) ? count($videos) : 0;
    } elseif ($column === 'anexos') {
        $docs = agert_meta($post_id, 'documentos', array());
        $count = 0;
        if (is_array($docs)) {
            foreach ($docs as $d) {
                if (!empty($d['arquivo_id']) || !empty($d['arquivo_url'])) {
                    $count++;
                }
            }
        }
        echo $count;
    }
}
add_action('manage_reuniao_posts_custom_column', 'agert_reuniao_columns_content', 10, 2);
