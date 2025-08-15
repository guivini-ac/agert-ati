<?php
/**
 * AGERT WordPress Theme Functions
 * Tema WordPress puro com Bootstrap 5
 * 
 * @package AGERT
 * @version 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function agert_setup() {
    // Suporte a recursos do tema
    load_theme_textdomain('agert', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('custom-logo', array(
        'height'      => 40,
        'width'       => 40,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    add_theme_support('menus');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('automatic-feed-links');
    add_theme_support('responsive-embeds');
    
    // Registrar menus
    register_nav_menus(array(
        'primary' => __('Menu Principal', 'agert'),
    ));
    
    // Tamanhos de imagem
    add_image_size('meeting-thumb', 400, 250, true);
    add_image_size('participant-thumb', 150, 150, true);
}
add_action('after_setup_theme', 'agert_setup');

/**
 * Registra sidebar padrão.
 */
function agert_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'agert'),
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title mb-3">',
        'after_title'   => '</h5>',
    ));
}
add_action('widgets_init', 'agert_widgets_init');

/**
 * Enqueue scripts and styles
 */
function agert_scripts() {
    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        array(),
        '5.3.0'
    );
    
    // Bootstrap Icons
    wp_enqueue_style(
        'bootstrap-icons',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css',
        array(),
        '1.11.0'
    );

    // Google Fonts
    wp_enqueue_style(
        'agert-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap',
        array(),
        null
    );

    // Design tokens
    wp_enqueue_style(
        'agert-ui-tokens',
        get_template_directory_uri() . '/assets/css/ui-tokens.css',
        array(),
        wp_get_theme()->get('Version')
    );

    // Theme stylesheet
    wp_enqueue_style(
        'agert-style',
        get_stylesheet_uri(),
        array('bootstrap', 'agert-fonts', 'agert-ui-tokens'),
        wp_get_theme()->get('Version')
    );

    // Header stylesheet
    wp_enqueue_style(
        'theme-header',
        get_template_directory_uri() . '/assets/css/header.css',
        array(),
        '1.0'
    );

    // Reuniões stylesheet
    $reunioes_css = get_template_directory() . '/assets/css/reunioes.css';
    if (file_exists($reunioes_css)) {
        wp_enqueue_style(
            'agert-reunioes',
            get_template_directory_uri() . '/assets/css/reunioes.css',
            array('agert-style'),
            wp_get_theme()->get('Version')
        );
    }

    // Presidente stylesheet (apenas na página do Presidente)
    if (is_page_template('page-presidente.php') || is_page('presidente')) {
        wp_enqueue_style(
            'presidente-css',
            get_template_directory_uri() . '/assets/css/presidente.css',
            array(),
            '1.0'
        );
    }

    // Sobre stylesheet (apenas na página "Sobre")
    if (is_page_template('page-sobre.php') || is_page(array('sobre-a-agert', 'sobre'))) {
        wp_enqueue_style(
            'agert-sobre',
            get_template_directory_uri() . '/assets/css/sobre.css',
            array('agert-style'),
            '1.0'
        );
    }
    
    // Bootstrap JS (sem jQuery)
    wp_enqueue_script(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
        array(),
        '5.3.0',
        true
    );
    
    // Theme JS (apenas se necessário)
    $theme_js_path = get_template_directory() . '/assets/js/theme.js';
    if (file_exists($theme_js_path)) {
        wp_enqueue_script(
            'agert-theme',
            get_template_directory_uri() . '/assets/js/theme.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );

        // Localizar para AJAX
        wp_localize_script('agert-theme', 'agert_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('agert_nonce'),
        ));
    }

    // JS específico das páginas de reuniões
    if (is_post_type_archive('reuniao')) {
        $reunioes_js_path = get_template_directory() . '/assets/js/reunioes.js';
        if (file_exists($reunioes_js_path)) {
            wp_enqueue_script(
                'agert-reunioes-js',
                get_template_directory_uri() . '/assets/js/reunioes.js',
                array(),
                wp_get_theme()->get('Version'),
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'agert_scripts');

/**
 * Incluir arquivos de funcionalidades
 */
require_once get_template_directory() . '/inc/meta-helpers.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/reunioes-helpers.php';
require_once get_template_directory() . '/inc/presidente-helpers.php';
require_once get_template_directory() . '/inc/sobre-helpers.php';
require_once get_template_directory() . '/inc/admin-reunioes.php';
require_once get_template_directory() . '/components/html.php';
require_once get_template_directory() . '/inc/security.php';
require_once get_template_directory() . '/inc/activation.php';

/**
 * Registra o template da página Presidente.
 */
add_filter('theme_page_templates', function ($templates) {
    $templates['page-presidente.php'] = __('Presidente', 'agert');
    return $templates;
});

// Incluir AJAX handlers se existir
$ajax_handlers_file = get_template_directory() . '/inc/ajax-handlers.php';
if (file_exists($ajax_handlers_file)) {
    require_once $ajax_handlers_file;
}

/**
 * Força o uso do template "page-sobre.php" para slugs específicos.
 */
add_filter('page_template', function ($template) {
    if (is_page(array('sobre-a-agert', 'sobre'))) {
        $new = locate_template('page-sobre.php');
        if ($new) {
            return $new;
        }
    }
    return $template;
});

/**
 * Corrigir estado ativo do menu para CPT reuniao
 */
add_filter('nav_menu_css_class', function($classes, $item) {
    if (is_post_type_archive('reuniao') || is_singular('reuniao')) {
        $archive = get_post_type_archive_link('reuniao');
        if (!empty($item->url) && $archive && trailingslashit($item->url) === trailingslashit($archive)) {
            $classes[] = 'current-menu-item';
        }
    }
    return $classes;
}, 10, 2);

if (!function_exists('agert_menu_fallback')) {
    /**
     * Fallback para o menu principal exibindo páginas básicas.
     */
    function agert_menu_fallback() {
        echo '<ul class="menu">';
        wp_list_pages([
            'title_li' => '',
        ]);
        echo '</ul>';
    }
}

/**
 * Permite preenchimento de exemplo via querystring (?seed_sobre=1).
 */
add_action('template_redirect', function () {
    if (!is_page_template('page-sobre.php') && !is_page(array('sobre-a-agert', 'sobre'))) {
        return;
    }
    if (!current_user_can('manage_options')) {
        return;
    }
    if (isset($_GET['seed_sobre']) && check_admin_referer('agert_sobre_seed')) {
        agert_sobre_seed_if_empty(get_queried_object_id());
        wp_safe_redirect(remove_query_arg(array('seed_sobre', '_wpnonce')));
        exit;
    }
});

/**
 * Customizar excerpts
 */
function agert_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'agert_excerpt_length');

function agert_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'agert_excerpt_more');

/**
 * Limitar uploads para arquivos seguros
 */
function agert_upload_mimes($mimes) {
    $mimes['pdf'] = 'application/pdf';
    return $mimes;
}
add_filter('upload_mimes', 'agert_upload_mimes');
