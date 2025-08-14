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
}
add_action('init', 'agert_register_post_types');

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
}
add_action('add_meta_boxes', 'agert_add_meta_boxes');

/**
 * Callback para meta box da Reunião
 */
function agert_reuniao_meta_box_callback($post) {
    wp_nonce_field('agert_reuniao_meta_nonce', 'reuniao_meta_nonce');
    
    $data_hora = get_post_meta($post->ID, '_data_hora', true);
    $local = get_post_meta($post->ID, '_local', true);
    $pauta = get_post_meta($post->ID, '_pauta', true);
    $video_url = get_post_meta($post->ID, '_video_url', true);
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
            <th><label for="pauta"><?php _e('Pauta', 'agert'); ?></label></th>
            <td><textarea id="pauta" name="pauta" rows="5" class="large-text"><?php echo esc_textarea($pauta); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="video_url"><?php _e('URL do Vídeo', 'agert'); ?></label></th>
            <td>
                <input type="url" id="video_url" name="video_url" value="<?php echo esc_url($video_url); ?>" class="regular-text" />
                <p class="description"><?php _e('Link do vídeo da reunião (YouTube, etc.)', 'agert'); ?></p>
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
        if (isset($_POST['pauta'])) {
            update_post_meta($post_id, '_pauta', sanitize_textarea_field($_POST['pauta']));
        }
        if (isset($_POST['video_url'])) {
            update_post_meta($post_id, '_video_url', esc_url_raw($_POST['video_url']));
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