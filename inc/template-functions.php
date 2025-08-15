<?php
/**
 * Funções de template personalizadas
 * 
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Bootstrap NavWalker
 */
class agert_bootstrap_navwalker extends Walker_Nav_Menu {
    
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }
    
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        
        if ($depth === 0) {
            $class_names .= ' nav-item';
            if (in_array('menu-item-has-children', $classes)) {
                $class_names .= ' dropdown';
            }
        } else {
            $class_names .= ' dropdown-item';
        }
        
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        if ($depth === 0) {
            $attributes .= ' class="nav-link';
            if (in_array('menu-item-has-children', $classes)) {
                $attributes .= ' dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"';
            } else {
                $attributes .= '"';
            }
        } else {
            $attributes .= ' class="dropdown-item"';
        }
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
    
    static function fallback($args) {
        $defaults = array(
            'menu_class' => 'navbar-nav ms-auto',
            'container' => false,
        );
        $args = wp_parse_args($args, $defaults);
        
        $pages = get_pages();
        if (empty($pages)) {
            return;
        }
        
        echo '<ul class="' . esc_attr($args['menu_class']) . '">';
        echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/')) . '">' . __('Início', 'agert') . '</a></li>';
        
        foreach ($pages as $page) {
            $class = 'nav-item';
            if (is_page($page->ID)) {
                $class .= ' active';
            }
            echo '<li class="' . $class . '">';
            echo '<a class="nav-link" href="' . get_permalink($page->ID) . '">' . esc_html($page->post_title) . '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
    }
}

/**
 * Função para exibir ícones baseados no tipo de post
 */
function agert_get_post_type_icon($post_type) {
    $icons = array(
        'reuniao' => 'bi-calendar-event',
        'anexo' => 'bi-paperclip',
        'participante' => 'bi-person',
        'post' => 'bi-newspaper',
        'page' => 'bi-file-text'
    );
    
    return isset($icons[$post_type]) ? $icons[$post_type] : 'bi-file';
}

/**
 * Função para formatar data
 */
function agert_format_date($date, $format = 'd/m/Y') {
    if (empty($date)) {
        return '';
    }
    
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    return date_i18n($format, $timestamp);
}

/**
 * Função para formatar data e hora
 */
function agert_format_datetime($datetime, $format = 'd/m/Y H:i') {
    if (empty($datetime)) {
        return '';
    }
    
    $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
    return date_i18n($format, $timestamp);
}

/**
 * Buscar anexos relacionados a uma reunião
 */
function agert_get_meeting_attachments($meeting_id) {
    return get_posts(array(
        'post_type' => 'anexo',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_reuniao_id',
                'value' => $meeting_id,
                'compare' => '='
            )
        ),
        'post_status' => 'publish'
    ));
}

/**
 * Buscar participantes de uma reunião
 */
function agert_get_meeting_participants($meeting_id) {
    return get_posts(array(
        'post_type' => 'participante',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_reuniao_id',
                'value' => $meeting_id,
                'compare' => '='
            )
        ),
        'post_status' => 'publish'
    ));
}

/**
 * Exibir status message
 */
function agert_show_status_message() {
    if (isset($_GET['status'])) {
        $status = sanitize_text_field($_GET['status']);
        $message = '';
        $type = 'success';
        
        switch ($status) {
            case 'reuniao_created':
                $message = __('Reunião criada com sucesso!', 'agert');
                break;
            case 'anexo_created':
                $message = __('Anexo adicionado com sucesso!', 'agert');
                break;
            case 'participante_created':
                $message = __('Participante registrado com sucesso!', 'agert');
                break;
            case 'error':
                $message = __('Erro ao processar solicitação. Tente novamente.', 'agert');
                $type = 'error';
                break;
        }
        
        if (!empty($message)) {
            echo '<div class="alert alert-' . ($type === 'success' ? 'success' : 'danger') . ' alert-dismissible fade show" role="alert">';
            echo '<i class="bi bi-' . ($type === 'success' ? 'check-circle' : 'exclamation-triangle') . ' me-2"></i>';
            echo esc_html($message);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
        }
    }
}

/**
 * Validar se usuário pode criar posts
 */
function agert_user_can_create_posts() {
    return is_user_logged_in() && current_user_can('edit_posts');
}

/**
 * Exibir lista de reuniões para select
 */
function agert_get_meetings_options($selected = '', $limit = 50) {
    $query = new WP_Query(array(
        'post_type'      => 'reuniao',
        'posts_per_page' => intval($limit),
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'fields'         => 'ids'
    ));

    $options = '<option value="">' . __('Selecione uma reunião', 'agert') . '</option>';

    foreach ($query->posts as $meeting_id) {
        $data_hora = get_post_meta($meeting_id, '_data_hora', true);
        $data_formatada = !empty($data_hora) ? ' - ' . agert_format_datetime($data_hora) : '';

        $options .= sprintf(
            '<option value="%d" %s>%s%s</option>',
            $meeting_id,
            selected($selected, $meeting_id, false),
            esc_html(get_the_title($meeting_id)),
            esc_html($data_formatada)
        );
    }

    wp_reset_postdata();

    return $options;
}

function agert_ajax_get_meetings() {
    check_ajax_referer('agert_nonce', 'nonce');

    $query = new WP_Query(array(
        'post_type'      => 'reuniao',
        'posts_per_page' => 100,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'fields'         => 'ids'
    ));

    $meetings = array();

    foreach ($query->posts as $meeting_id) {
        $data_hora = get_post_meta($meeting_id, '_data_hora', true);
        $data_formatada = !empty($data_hora) ? ' - ' . agert_format_datetime($data_hora) : '';
        $meetings[] = array(
            'id'    => $meeting_id,
            'title' => get_the_title($meeting_id) . $data_formatada
        );
    }

    wp_send_json_success($meetings);
}
add_action('wp_ajax_agert_get_meetings', 'agert_ajax_get_meetings');
add_action('wp_ajax_nopriv_agert_get_meetings', 'agert_ajax_get_meetings');

/**
 * Truncar texto mantendo palavras completas
 */
function agert_truncate_text($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    $truncated = substr($text, 0, $length);
    $last_space = strrpos($truncated, ' ');
    
    if ($last_space !== false) {
        $truncated = substr($truncated, 0, $last_space);
    }
    
    return $truncated . '...';
}

/**
 * Gerar classe de status para reunião
 */
function agert_get_meeting_status_class($meeting_id) {
    $data_hora = get_post_meta($meeting_id, '_data_hora', true);
    
    if (empty($data_hora)) {
        return 'text-muted';
    }
    
    $timestamp = strtotime($data_hora);
    $now = current_time('timestamp');
    
    if ($timestamp > $now) {
        return 'text-primary'; // Agendada
    } elseif ($timestamp <= $now && $timestamp > ($now - 86400)) {
        return 'text-warning'; // Hoje ou ontem
    } else {
        return 'text-success'; // Realizada
    }
}

/**
 * Obter texto de status da reunião
 */
function agert_get_meeting_status_text($meeting_id) {
    $data_hora = get_post_meta($meeting_id, '_data_hora', true);
    
    if (empty($data_hora)) {
        return __('Sem data definida', 'agert');
    }
    
    $timestamp = strtotime($data_hora);
    $now = current_time('timestamp');
    
    if ($timestamp > $now) {
        return __('Agendada', 'agert');
    } elseif ($timestamp <= $now && $timestamp > ($now - 86400)) {
        return __('Em andamento/Hoje', 'agert');
    } else {
        return __('Realizada', 'agert');
    }
}