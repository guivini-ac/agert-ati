<?php
/**
 * Custom Post Types para AGERT
 * 
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registrar Custom Post Types
 */
function agert_register_post_types() {
    
    // CPT: Reunião
    register_post_type('reuniao', array(
        'labels' => array(
            'name' => __('Reuniões', 'agert'),
            'singular_name' => __('Reunião', 'agert'),
            'menu_name' => __('Reuniões', 'agert'),
            'add_new' => __('Nova Reunião', 'agert'),
            'add_new_item' => __('Adicionar Nova Reunião', 'agert'),
            'edit_item' => __('Editar Reunião', 'agert'),
            'new_item' => __('Nova Reunião', 'agert'),
            'view_item' => __('Ver Reunião', 'agert'),
            'view_items' => __('Ver Reuniões', 'agert'),
            'search_items' => __('Buscar Reuniões', 'agert'),
            'not_found' => __('Nenhuma reunião encontrada', 'agert'),
            'not_found_in_trash' => __('Nenhuma reunião encontrada na lixeira', 'agert'),
            'all_items' => __('Todas as Reuniões', 'agert'),
            'archives' => __('Arquivo de Reuniões', 'agert'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-groups',
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'rewrite' => array('slug' => 'reunioes'),
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
    ));
    
    // CPT: Anexo
    register_post_type('anexo', array(
        'labels' => array(
            'name' => __('Anexos', 'agert'),
            'singular_name' => __('Anexo', 'agert'),
            'menu_name' => __('Anexos', 'agert'),
            'add_new' => __('Novo Anexo', 'agert'),
            'add_new_item' => __('Adicionar Novo Anexo', 'agert'),
            'edit_item' => __('Editar Anexo', 'agert'),
            'new_item' => __('Novo Anexo', 'agert'),
            'view_item' => __('Ver Anexo', 'agert'),
            'view_items' => __('Ver Anexos', 'agert'),
            'search_items' => __('Buscar Anexos', 'agert'),
            'not_found' => __('Nenhum anexo encontrado', 'agert'),
            'not_found_in_trash' => __('Nenhum anexo encontrado na lixeira', 'agert'),
            'all_items' => __('Todos os Anexos', 'agert'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-paperclip',
        'menu_position' => 6,
        'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'),
        'rewrite' => array('slug' => 'anexos'),
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
    ));
    
    // CPT: Participante
    register_post_type('participante', array(
        'labels' => array(
            'name' => __('Participantes', 'agert'),
            'singular_name' => __('Participante', 'agert'),
            'menu_name' => __('Participantes', 'agert'),
            'add_new' => __('Novo Participante', 'agert'),
            'add_new_item' => __('Adicionar Novo Participante', 'agert'),
            'edit_item' => __('Editar Participante', 'agert'),
            'new_item' => __('Novo Participante', 'agert'),
            'view_item' => __('Ver Participante', 'agert'),
            'view_items' => __('Ver Participantes', 'agert'),
            'search_items' => __('Buscar Participantes', 'agert'),
            'not_found' => __('Nenhum participante encontrado', 'agert'),
            'not_found_in_trash' => __('Nenhum participante encontrado na lixeira', 'agert'),
            'all_items' => __('Todos os Participantes', 'agert'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-users',
        'menu_position' => 7,
        'supports' => array('title', 'custom-fields', 'thumbnail'),
        'rewrite' => array('slug' => 'participantes'),
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
    ));

    // CPT: Agenda Fiscal
    register_post_type('agenda_fiscal', array(
        'labels' => array(
            'name' => __('Agenda Fiscal', 'agert'),
            'singular_name' => __('Agenda Fiscal', 'agert'),
            'menu_name' => __('Agenda Fiscal', 'agert'),
            'add_new' => __('Nova Programação', 'agert'),
            'add_new_item' => __('Adicionar Programação', 'agert'),
            'edit_item' => __('Editar Programação', 'agert'),
            'new_item' => __('Nova Programação', 'agert'),
            'view_item' => __('Ver Programação', 'agert'),
            'search_items' => __('Buscar Programações', 'agert'),
            'not_found' => __('Nenhuma programação encontrada', 'agert'),
            'not_found_in_trash' => __('Nenhuma programação encontrada na lixeira', 'agert'),
            'all_items' => __('Todas as Programações', 'agert'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'menu_position' => 8,
        'supports' => array('title'),
        'rewrite' => array('slug' => 'agenda-fiscal'),
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
    ));
}
add_action('init', 'agert_register_post_types');

/**
 * Registrar CPT para Vídeos de Reuniões
 */
function agert_register_reuniao_video_cpt() {
    register_post_type('reuniao_video', array(
        'labels' => array(
            'name' => __('Vídeos de Reuniões', 'agert'),
            'singular_name' => __('Vídeo de Reunião', 'agert'),
            'menu_name' => __('Vídeos de Reuniões', 'agert'),
            'add_new' => __('Novo Vídeo', 'agert'),
            'add_new_item' => __('Adicionar Novo Vídeo', 'agert'),
            'edit_item' => __('Editar Vídeo', 'agert'),
            'new_item' => __('Novo Vídeo', 'agert'),
            'view_item' => __('Ver Vídeo', 'agert'),
            'search_items' => __('Buscar Vídeos', 'agert'),
            'not_found' => __('Nenhum vídeo encontrado', 'agert'),
            'not_found_in_trash' => __('Nenhum vídeo encontrado na lixeira', 'agert'),
            'all_items' => __('Todos os Vídeos', 'agert'),
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'edit.php?post_type=reuniao',
        'menu_position' => null,
        'supports' => array('title', 'editor'),
        'capability_type' => 'post',
        'hierarchical' => false,
    ));
}
add_action('init', 'agert_register_reuniao_video_cpt');

/**
 * Registrar taxonomias
 */
function agert_register_taxonomies() {
    
    // Taxonomy: Tipo de Reunião
    register_taxonomy('tipo_reuniao', array('reuniao'), array(
        'labels' => array(
            'name' => __('Tipos de Reunião', 'agert'),
            'singular_name' => __('Tipo de Reunião', 'agert'),
            'search_items' => __('Buscar Tipos', 'agert'),
            'all_items' => __('Todos os Tipos', 'agert'),
            'edit_item' => __('Editar Tipo', 'agert'),
            'update_item' => __('Atualizar Tipo', 'agert'),
            'add_new_item' => __('Adicionar Novo Tipo', 'agert'),
            'new_item_name' => __('Novo Tipo', 'agert'),
            'menu_name' => __('Tipos de Reunião', 'agert'),
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'tipo-reuniao'),
        'show_in_rest' => true,
    ));
    
    // Taxonomy: Status da Reunião
    register_taxonomy('status_reuniao', array('reuniao'), array(
        'labels' => array(
            'name' => __('Status das Reuniões', 'agert'),
            'singular_name' => __('Status', 'agert'),
            'menu_name' => __('Status', 'agert'),
        ),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'status-reuniao'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'agert_register_taxonomies');

/**
 * Adicionar meta boxes personalizadas
 */
function agert_add_meta_boxes() {
    
    // Meta box para Reunião
    add_meta_box(
        'reuniao_details',
        __('Detalhes da Reunião', 'agert'),
        'agert_reuniao_meta_box_callback',
        'reuniao',
        'normal',
        'high'
    );
    
    // Meta box para Anexo
    add_meta_box(
        'anexo_details',
        __('Detalhes do Anexo', 'agert'),
        'agert_anexo_meta_box_callback',
        'anexo',
        'normal',
        'high'
    );
    
    // Meta box para Participante
    add_meta_box(
        'participante_details',
        __('Detalhes do Participante', 'agert'),
        'agert_participante_meta_box_callback',
        'participante',
        'normal',
        'high'
    );

    // Meta box para Agenda Fiscal
    add_meta_box(
        'agenda_fiscal_details',
        __('Detalhes da Programação', 'agert'),
        'agert_agenda_fiscal_meta_box_callback',
        'agenda_fiscal',
        'normal',
        'high'
    );

    // Meta box para Vídeo de Reunião
    add_meta_box(
        'reuniao_video_details',
        __('Detalhes do Vídeo', 'agert'),
        'agert_reuniao_video_meta_box_callback',
        'reuniao_video',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'agert_add_meta_boxes');

/**
 * Callback para meta box da Reunião
 */
function agert_reuniao_meta_box_callback($post) {
    wp_nonce_field('agert_reuniao_meta_nonce', 'reuniao_meta_nonce');

    $data_hora   = get_post_meta($post->ID, '_data_hora', true);
    $local       = get_post_meta($post->ID, '_local', true);
    $video_url   = get_post_meta($post->ID, '_video_url', true);
    $pauta       = get_post_meta($post->ID, 'pauta', true);
    $decisoes    = get_post_meta($post->ID, 'decisoes', true);
    $pauta_text  = is_array($pauta) ? implode("\n", $pauta) : '';
    $decisoes_text = is_array($decisoes) ? implode("\n", $decisoes) : '';
    ?>
    <table class="form-table">
        <tr>
            <th><label for="data_hora"><?php _e('Data e Hora', 'agert'); ?></label></th>
            <td><input type="datetime-local" id="data_hora" name="data_hora" value="<?php echo esc_attr($data_hora); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="local"><?php _e('Local', 'agert'); ?></label></th>
            <td><input type="text" id="local" name="local" value="<?php echo esc_attr($local); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="video_url"><?php _e('URL do Vídeo', 'agert'); ?></label></th>
            <td>
                <input type="url" id="video_url" name="video_url" value="<?php echo esc_url($video_url); ?>" class="regular-text" />
                <p class="description"><?php _e('Link do vídeo da reunião (YouTube, etc.)', 'agert'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="pauta"><?php _e('Pauta da Reunião', 'agert'); ?></label></th>
            <td>
                <textarea id="pauta" name="pauta" rows="5" class="large-text"><?php echo esc_textarea($pauta_text); ?></textarea>
                <p class="description"><?php _e('Uma linha por item da pauta', 'agert'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="decisoes"><?php _e('Principais Decisões', 'agert'); ?></label></th>
            <td>
                <textarea id="decisoes" name="decisoes" rows="5" class="large-text"><?php echo esc_textarea($decisoes_text); ?></textarea>
                <p class="description"><?php _e('Uma linha por decisão', 'agert'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Callback para meta box do Anexo
 */
function agert_anexo_meta_box_callback($post) {
    wp_nonce_field('agert_anexo_meta_nonce', 'anexo_meta_nonce');
    
    $arquivo = get_post_meta($post->ID, '_arquivo', true);
    $reuniao_id = get_post_meta($post->ID, '_reuniao_id', true);
    
    // Buscar reuniões para select
    $reunioes = get_posts(array(
        'post_type' => 'reuniao',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    ?>
    <table class="form-table">
        <tr>
            <th><label for="arquivo"><?php _e('Arquivo', 'agert'); ?></label></th>
            <td>
                <input type="url" id="arquivo" name="arquivo" value="<?php echo esc_url($arquivo); ?>" class="regular-text" />
                <p class="description"><?php _e('URL do arquivo anexo (PDF, imagem, etc.)', 'agert'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="reuniao_id"><?php _e('Reunião Relacionada', 'agert'); ?></label></th>
            <td>
                <select id="reuniao_id" name="reuniao_id" class="regular-text">
                    <option value=""><?php _e('Selecione uma reunião', 'agert'); ?></option>
                    <?php foreach ($reunioes as $reuniao) : ?>
                        <option value="<?php echo $reuniao->ID; ?>" <?php selected($reuniao_id, $reuniao->ID); ?>>
                            <?php echo esc_html($reuniao->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Callback para meta box do Participante
 */
function agert_participante_meta_box_callback($post) {
    wp_nonce_field('agert_participante_meta_nonce', 'participante_meta_nonce');
    
    $nome_participante = get_post_meta($post->ID, '_nome_participante', true);
    $cargo = get_post_meta($post->ID, '_cargo', true);
    $email = get_post_meta($post->ID, '_email', true);
    $reuniao_id = get_post_meta($post->ID, '_reuniao_id', true);
    
    // Buscar reuniões para select
    $reunioes = get_posts(array(
        'post_type' => 'reuniao',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    ?>
    <table class="form-table">
        <tr>
            <th><label for="nome_participante"><?php _e('Nome do Participante', 'agert'); ?></label></th>
            <td><input type="text" id="nome_participante" name="nome_participante" value="<?php echo esc_attr($nome_participante); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="cargo"><?php _e('Cargo', 'agert'); ?></label></th>
            <td><input type="text" id="cargo" name="cargo" value="<?php echo esc_attr($cargo); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="email"><?php _e('E-mail', 'agert'); ?></label></th>
            <td><input type="email" id="email" name="email" value="<?php echo esc_attr($email); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="reuniao_id"><?php _e('Reunião', 'agert'); ?></label></th>
            <td>
                <select id="reuniao_id" name="reuniao_id" class="regular-text">
                    <option value=""><?php _e('Selecione uma reunião', 'agert'); ?></option>
                    <?php foreach ($reunioes as $reuniao) : ?>
                        <option value="<?php echo $reuniao->ID; ?>" <?php selected($reuniao_id, $reuniao->ID); ?>>
                            <?php echo esc_html($reuniao->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Callback para meta box da Agenda Fiscal
 */
function agert_agenda_fiscal_meta_box_callback($post) {
    wp_nonce_field('agert_agenda_fiscal_meta_nonce', 'agenda_fiscal_meta_nonce');

    $inicio = get_post_meta($post->ID, '_inicio', true);
    $fim = get_post_meta($post->ID, '_fim', true);
    $prestador = get_post_meta($post->ID, '_prestador', true);
    $atividade = get_post_meta($post->ID, '_atividade', true);
    $modalidade = get_post_meta($post->ID, '_modalidade', true);
    $responsavel = get_post_meta($post->ID, '_responsavel', true);
    $objetivo = get_post_meta($post->ID, '_objetivo', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="inicio"><?php _e('Início', 'agert'); ?></label></th>
            <td><input type="date" id="inicio" name="inicio" value="<?php echo esc_attr($inicio); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="fim"><?php _e('Fim', 'agert'); ?></label></th>
            <td><input type="date" id="fim" name="fim" value="<?php echo esc_attr($fim); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="prestador"><?php _e('Prestador de Serviço', 'agert'); ?></label></th>
            <td><input type="text" id="prestador" name="prestador" value="<?php echo esc_attr($prestador); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="atividade"><?php _e('Atividade/Local', 'agert'); ?></label></th>
            <td><input type="text" id="atividade" name="atividade" value="<?php echo esc_attr($atividade); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="modalidade"><?php _e('Modalidade', 'agert'); ?></label></th>
            <td><input type="text" id="modalidade" name="modalidade" value="<?php echo esc_attr($modalidade); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="responsavel"><?php _e('Responsável', 'agert'); ?></label></th>
            <td><input type="text" id="responsavel" name="responsavel" value="<?php echo esc_attr($responsavel); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="objetivo"><?php _e('Objetivo', 'agert'); ?></label></th>
            <td><textarea id="objetivo" name="objetivo" rows="4" class="large-text"><?php echo esc_textarea($objetivo); ?></textarea></td>
        </tr>
    </table>
    <?php
}

/**
 * Callback para meta box do Vídeo de Reunião
 */
function agert_reuniao_video_meta_box_callback($post) {
    wp_nonce_field('agert_reuniao_video_meta_nonce', 'reuniao_video_meta_nonce');
    
    $reuniao_relacionada = get_post_meta($post->ID, 'reuniao_relacionada', true);
    $video_url = get_post_meta($post->ID, 'video_url', true);
    $duracao_segundos = get_post_meta($post->ID, 'duracao_segundos', true);
    
    // Buscar reuniões para select
    $reunioes = get_posts(array(
        'post_type' => 'reuniao',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    ?>
    <table class="form-table">
        <tr>
            <th><label for="reuniao_relacionada"><?php _e('Reunião Relacionada *', 'agert'); ?></label></th>
            <td>
                <select id="reuniao_relacionada" name="reuniao_relacionada" class="regular-text" required>
                    <option value=""><?php _e('Selecione uma reunião', 'agert'); ?></option>
                    <?php foreach ($reunioes as $reuniao) : ?>
                        <option value="<?php echo $reuniao->ID; ?>" <?php selected($reuniao_relacionada, $reuniao->ID); ?>>
                            <?php echo esc_html($reuniao->post_title); ?>
                            <?php 
                            $data = get_post_meta($reuniao->ID, 'data_hora', true);
                            if ($data) {
                                echo ' - ' . date_i18n('d/m/Y', strtotime($data));
                            }
                            ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="video_url"><?php _e('URL do Vídeo *', 'agert'); ?></label></th>
            <td>
                <input type="url" id="video_url" name="video_url" value="<?php echo esc_url($video_url); ?>" class="regular-text" required />
                <p class="description"><?php _e('URL do YouTube, Vimeo ou outro serviço de vídeo', 'agert'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="duracao_segundos"><?php _e('Duração (segundos)', 'agert'); ?></label></th>
            <td>
                <input type="number" id="duracao_segundos" name="duracao_segundos" value="<?php echo esc_attr($duracao_segundos); ?>" class="regular-text" min="0" />
                <p class="description"><?php _e('Duração do vídeo em segundos (opcional)', 'agert'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Salvar meta fields
 */
function agert_save_meta_boxes($post_id) {
    // Verificar nonce e permissões
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Salvar meta da reunião
    if (isset($_POST['reuniao_meta_nonce']) && wp_verify_nonce($_POST['reuniao_meta_nonce'], 'agert_reuniao_meta_nonce')) {
        if (isset($_POST['data_hora'])) {
            update_post_meta($post_id, '_data_hora', sanitize_text_field($_POST['data_hora']));
        }
        if (isset($_POST['local'])) {
            update_post_meta($post_id, '_local', sanitize_text_field($_POST['local']));
        }
        if (isset($_POST['video_url'])) {
            update_post_meta($post_id, '_video_url', esc_url_raw($_POST['video_url']));
        }
        if (isset($_POST['pauta'])) {
            $items = preg_split("/\r\n|\r|\n/", $_POST['pauta']);
            $items = array_filter(array_map('sanitize_text_field', array_map('trim', $items)));
            update_post_meta($post_id, 'pauta', $items);
        }
        if (isset($_POST['decisoes'])) {
            $items = preg_split("/\r\n|\r|\n/", $_POST['decisoes']);
            $items = array_filter(array_map('sanitize_text_field', array_map('trim', $items)));
            update_post_meta($post_id, 'decisoes', $items);
        }
    }
    
    // Salvar meta do anexo
    if (isset($_POST['anexo_meta_nonce']) && wp_verify_nonce($_POST['anexo_meta_nonce'], 'agert_anexo_meta_nonce')) {
        if (isset($_POST['arquivo'])) {
            update_post_meta($post_id, '_arquivo', esc_url_raw($_POST['arquivo']));
        }
        if (isset($_POST['reuniao_id'])) {
            update_post_meta($post_id, '_reuniao_id', intval($_POST['reuniao_id']));
        }
    }
    
    // Salvar meta do participante
    if (isset($_POST['participante_meta_nonce']) && wp_verify_nonce($_POST['participante_meta_nonce'], 'agert_participante_meta_nonce')) {
        if (isset($_POST['nome_participante'])) {
            update_post_meta($post_id, '_nome_participante', sanitize_text_field($_POST['nome_participante']));
        }
        if (isset($_POST['cargo'])) {
            update_post_meta($post_id, '_cargo', sanitize_text_field($_POST['cargo']));
        }
        if (isset($_POST['email'])) {
            update_post_meta($post_id, '_email', sanitize_email($_POST['email']));
        }
        if (isset($_POST['reuniao_id'])) {
            update_post_meta($post_id, '_reuniao_id', intval($_POST['reuniao_id']));
        }
    }

    // Salvar meta da agenda fiscal
    if (isset($_POST['agenda_fiscal_meta_nonce']) && wp_verify_nonce($_POST['agenda_fiscal_meta_nonce'], 'agert_agenda_fiscal_meta_nonce')) {
        $map = array(
            'inicio'      => '_inicio',
            'fim'         => '_fim',
            'prestador'   => '_prestador',
            'atividade'   => '_atividade',
            'modalidade'  => '_modalidade',
            'responsavel' => '_responsavel',
            'objetivo'    => '_objetivo',
        );
        foreach ($map as $field => $meta_key) {
            if (isset($_POST[$field])) {
                $value = ('objetivo' === $field) ? sanitize_textarea_field($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }
    }

    // Salvar meta do vídeo de reunião
    if (isset($_POST['reuniao_video_meta_nonce']) && wp_verify_nonce($_POST['reuniao_video_meta_nonce'], 'agert_reuniao_video_meta_nonce')) {
        if (isset($_POST['reuniao_relacionada'])) {
            update_post_meta($post_id, 'reuniao_relacionada', intval($_POST['reuniao_relacionada']));
        }
        if (isset($_POST['video_url'])) {
            update_post_meta($post_id, 'video_url', esc_url_raw($_POST['video_url']));
        }
        if (isset($_POST['duracao_segundos'])) {
            update_post_meta($post_id, 'duracao_segundos', intval($_POST['duracao_segundos']));
        }
    }
}
add_action('save_post', 'agert_save_meta_boxes');

/**
 * Criar termos padrão para taxonomias
 */
function agert_create_default_terms() {
    // Tipos de reunião
    if (!term_exists('Ordinária', 'tipo_reuniao')) {
        wp_insert_term('Ordinária', 'tipo_reuniao');
    }
    if (!term_exists('Extraordinária', 'tipo_reuniao')) {
        wp_insert_term('Extraordinária', 'tipo_reuniao');
    }
    
    // Status das reuniões
    if (!term_exists('Agendada', 'status_reuniao')) {
        wp_insert_term('Agendada', 'status_reuniao');
    }
    if (!term_exists('Realizada', 'status_reuniao')) {
        wp_insert_term('Realizada', 'status_reuniao');
    }
    if (!term_exists('Cancelada', 'status_reuniao')) {
        wp_insert_term('Cancelada', 'status_reuniao');
    }
}
add_action('init', 'agert_create_default_terms', 20);

/**
 * Adicionar colunas customizadas para reuniao_video
 */
function agert_reuniao_video_columns($columns) {
    $columns['reuniao'] = __('Reunião', 'agert');
    $columns['video_url'] = __('URL do Vídeo', 'agert');
    $columns['duracao'] = __('Duração', 'agert');
    return $columns;
}
add_filter('manage_reuniao_video_posts_columns', 'agert_reuniao_video_columns');

function agert_reuniao_video_columns_content($column, $post_id) {
    switch ($column) {
        case 'reuniao':
            $reuniao_id = get_post_meta($post_id, 'reuniao_relacionada', true);
            if ($reuniao_id) {
                $reuniao = get_post($reuniao_id);
                if ($reuniao) {
                    echo '<a href="' . get_edit_post_link($reuniao_id) . '">' . esc_html($reuniao->post_title) . '</a>';
                }
            } else {
                echo '-';
            }
            break;
        case 'video_url':
            $url = get_post_meta($post_id, 'video_url', true);
            if ($url) {
                echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html(parse_url($url, PHP_URL_HOST)) . '</a>';
            } else {
                echo '-';
            }
            break;
        case 'duracao':
            $segundos = get_post_meta($post_id, 'duracao_segundos', true);
            if ($segundos) {
                $minutos = floor($segundos / 60);
                $seg = $segundos % 60;
                echo sprintf('%02d:%02d', $minutos, $seg);
            } else {
                echo '-';
            }
            break;
    }
}
add_action('manage_reuniao_video_posts_custom_column', 'agert_reuniao_video_columns_content', 10, 2);