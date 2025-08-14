<?php
/**
 * Funções executadas na ativação do tema.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cria páginas padrão se não existirem.
 */
function agert_create_pages() {
    $pages = array(
        'reunioes' => array(
            'title'   => 'Reuniões',
            'content' => '<p>Gerencie as reuniões da AGERT.</p>'
        ),
        'anexos' => array(
            'title'   => 'Anexos',
            'content' => '<p>Gerencie anexos das reuniões.</p>'
        ),
        'participantes' => array(
            'title'   => 'Participantes',
            'content' => '<p>Registre participantes das reuniões.</p>'
        ),
    );

    foreach ($pages as $slug => $page_data) {
        if (!get_page_by_path($slug)) {
            wp_insert_post(array(
                'post_title'   => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_name'    => $slug,
            ));
        }
    }
}

/**
 * Cria menu principal e associa páginas.
 */
function agert_create_menu() {
    $menu_name = 'Menu Principal';
    $menu = wp_get_nav_menu_object($menu_name);

    if (!$menu) {
        $menu_id = wp_create_nav_menu($menu_name);
        $pages = array('reunioes', 'anexos', 'participantes');
        $order = 1;
        foreach ($pages as $slug) {
            $page = get_page_by_path($slug);
            if ($page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'  => $page->post_title,
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'   => 'post_type',
                    'menu-item-status' => 'publish',
                    'menu-item-position' => $order++,
                ));
            }
        }
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}

/**
 * Hook de ativação do tema.
 */
function agert_seed_demo_data() {
    if (get_posts(array('post_type' => 'reuniao', 'posts_per_page' => 1))) {
        return;
    }

    $meeting_id = wp_insert_post(array(
        'post_title'   => 'Reunião de Exemplo',
        'post_content' => 'Conteúdo de exemplo para testes.',
        'post_type'    => 'reuniao',
        'post_status'  => 'publish',
    ));

    if ($meeting_id) {
        $docs = array(
            array(
                'rotulo'        => 'Ata',
                'arquivo_url'   => 'https://example.com/ata.pdf',
                'tamanho_bytes' => 12345,
                'resumo'        => 'Ata da reunião.',
            ),
            array(
                'rotulo'        => 'Relatório',
                'arquivo_url'   => 'https://example.com/relatorio.pdf',
                'tamanho_bytes' => 67890,
                'resumo'        => 'Relatório final.',
            ),
        );

        update_post_meta($meeting_id, 'data_hora', gmdate('Y-m-d H:i:s'));
        update_post_meta($meeting_id, 'duracao_minutos', 60);
        update_post_meta($meeting_id, 'local', 'Sala de Reuniões');
        update_post_meta($meeting_id, 'resumo', 'Reunião criada automaticamente para testes.');
        update_post_meta($meeting_id, 'video_url', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        update_post_meta($meeting_id, 'documentos', $docs);
    }
}

function agert_theme_activation() {
    agert_create_pages();
    agert_create_menu();
    agert_seed_demo_data();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'agert_theme_activation');
