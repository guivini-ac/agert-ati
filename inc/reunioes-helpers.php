<?php
/**
 * Helpers específicos das páginas de reuniões.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Recupera meta com fallback para ACF
 */
if (!function_exists('agert_meta')) {
    function agert_meta($id, $key, $default = '') {
        if (function_exists('get_field')) {
            $value = get_field($key, $id);
            if ($value !== null && $value !== false && $value !== '') {
                return $value;
            }
        }
        $value = get_post_meta($id, $key, true);
        return $value !== '' ? $value : $default;
    }
}

/**
 * Converte minutos em formato "3h 15min".
 */
function agert_minutes_to_human(int $min): string {
    $hours   = intdiv($min, 60);
    $minutes = $min % 60;
    $parts   = array();

    if ($hours > 0) {
        $parts[] = $hours . 'h';
    }
    if ($minutes > 0) {
        $parts[] = $minutes . 'min';
    }

    return implode(' ', $parts);
}

/**
 * Converte segundos para mm:ss.
 */
function agert_seconds_to_mmss(int $sec): string {
    $minutes = floor($sec / 60);
    $seconds = $sec % 60;
    return sprintf('%02d:%02d', $minutes, $seconds);
}

/**
 * Converte bytes em tamanho legível.
 */
function agert_bytes_to_human(int $bytes): string {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return sprintf('%s %s', round($bytes, $i ? 1 : 0), $units[$i]);
}

/**
 * Retorna o ano de uma string datetime.
 */
function agert_get_year_from_datetime(string $dt): int {
    $time = strtotime($dt);
    return (int) gmdate('Y', $time);
}

/**
 * Conta documentos relacionados à reunião.
 */
function agert_count_documentos(int $post_id): int {
    $documentos = agert_meta($post_id, 'documentos', array());
    if (!is_array($documentos)) {
        return 0;
    }
    $count = 0;
    foreach ($documentos as $doc) {
        if (!empty($doc['arquivo_id']) || !empty($doc['arquivo_url'])) {
            $count++;
        }
    }
    return $count;
}

/**
 * Verifica se reunião possui vídeo.
 */
function agert_reuniao_has_video(int $post_id): bool {
    $videos = agert_meta($post_id, 'videos', array());
    return is_array($videos) && !empty($videos);
}

/**
 * Verifica se reunião possui documentos.
 */
function agert_reuniao_has_docs(int $post_id): bool {
    return agert_count_documentos($post_id) > 0;
}

/**
 * Lista anos disponíveis de reuniões.
 *
 * @return int[]
 */
function agert_available_years(): array {
    $years = array();
    $q = new WP_Query(array(
        'post_type' => 'reuniao',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));
    
    foreach ($q->posts as $pid) {
        $dt = agert_meta($pid, 'data_hora', '');
        if ($dt) {
            $y = (int) date_i18n('Y', strtotime($dt));
        } else {
            $y = (int) get_the_date('Y', $pid);
        }
        $years[$y] = true;
    }
    
    $keys = array_keys($years);
    rsort($keys);
    
    if (empty($keys)) {
        $keys[] = (int) date('Y');
    }
    
    return $keys;
}

/**
 * Retorna o ano ativo considerando querystring.
 *
 * @return int
 */
function agert_active_year(): int {
    $y = isset($_GET['ano']) ? (int) $_GET['ano'] : 0;
    $list = agert_available_years();
    if ($y && in_array($y, $list, true)) {
        return $y;
    }
    return $list[0];
}

/**
 * Monta WP_Query para reuniões filtradas.
 *
 * @param array $p Parâmetros de filtro.
 *
 * @return WP_Query
 */
function agert_query_reunioes_filtradas(array $p): WP_Query {
    $ano = (int) ($p['ano'] ?? agert_active_year());
    $de = $p['de'] ?? sprintf('%d-01-01 00:00:00', $ano);
    $ate = $p['ate'] ?? sprintf('%d-12-31 23:59:59', $ano);
    
    $meta_query = array('relation' => 'AND');
    
    // data_hora OU post_date (fallback)
    $meta_query[] = array(
        'relation' => 'OR',
        array(
            'key' => 'data_hora',
            'value' => array($de, $ate),
            'type' => 'DATETIME',
            'compare' => 'BETWEEN'
        ),
        array(
            'key' => 'data_hora',
            'compare' => 'NOT EXISTS'
        )
    );
    
    if (!empty($p['status'])) {
        if ($p['status'] === 'video') {
            $meta_query[] = array('key' => 'videos', 'compare' => 'EXISTS');
        }
        if ($p['status'] === 'docs') {
            $meta_query[] = array('key' => 'documentos', 'compare' => 'EXISTS');
        }
    }
    
    if (!empty($p['local'])) {
        $meta_query[] = array(
            'key' => 'local',
            'value' => $p['local'],
            'compare' => 'LIKE'
        );
    }
    
    $args = array(
        'post_type'      => 'reuniao',
        'post_status'    => 'publish',
        'paged'          => max(1, (int) ($p['paged'] ?? 1)),
        'posts_per_page' => (int) ($p['posts_per_page'] ?? 9),
        's'              => $p['q'] ?? '',
        'meta_query'     => $meta_query,
    );
    
    // tipo: tax ou meta
    if (!empty($p['tipo'])) {
        if (taxonomy_exists('tipo_reuniao')) {
            $args['tax_query'] = array(array(
                'taxonomy' => 'tipo_reuniao',
                'field'    => 'slug',
                'terms'    => $p['tipo']
            ));
        } else {
            $meta_query[] = array(
                'key'   => 'tipo_reuniao',
                'value' => $p['tipo'],
                'compare' => 'LIKE'
            );
            $args['meta_query'] = $meta_query;
        }
    }
    
    // ordenação
    $ordem = $p['ordem'] ?? 'data_desc';
    if ($ordem === 'data_asc' || $ordem === 'data_desc') {
        $args['meta_key'] = 'data_hora';
        $args['orderby'] = 'meta_value';
        $args['meta_type'] = 'DATETIME';
        $args['order'] = ($ordem === 'data_asc') ? 'ASC' : 'DESC';
    } elseif ($ordem === 'titulo_za') {
        $args['orderby'] = 'title';
        $args['order'] = 'DESC';
    } else {
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
    }
    
    if (!empty($p['fields'])) {
        $args['fields'] = $p['fields'];
    }
    
    return new WP_Query($args);
}

/**
 * Coleta documentos agregados das reuniões filtradas.
 *
 * @param array $p Filtros para agert_query_reunioes_filtradas.
 * @return array Lista de documentos
 */
function agert_coletar_documentos(array $p): array {
    $q = agert_query_reunioes_filtradas(array_merge($p, array('posts_per_page' => -1)));
    $items = array();
    
    while ($q->have_posts()) {
        $q->the_post();
        $pid = get_the_ID();
        
        $docs = agert_meta($pid, 'documentos', array());
        if (!is_array($docs) || empty($docs)) {
            // fallback: pegar attachments do post
            $atts = get_children(array(
                'post_parent' => $pid,
                'post_type' => 'attachment',
                'posts_per_page' => -1
            ));
            foreach ($atts as $att) {
                $file_path = get_attached_file($att->ID);
                $items[] = array(
                    'doc' => array(
                        'rotulo' => get_post_mime_type($att->ID),
                        'arquivo_id' => $att->ID,
                        'arquivo_url' => wp_get_attachment_url($att->ID),
                        'tamanho_bytes' => $file_path ? filesize($file_path) : 0,
                        'resumo' => ''
                    ),
                    'meeting' => get_post($pid)
                );
            }
        } else {
            foreach ($docs as $d) {
                $items[] = array(
                    'doc' => $d,
                    'meeting' => get_post($pid)
                );
            }
        }
    }
    wp_reset_postdata();
    
    return array(
        'results' => $items,
        'total' => count($items),
        'pages' => 1
    );
}

/**
 * Coleta vídeos agregados das reuniões filtradas.
 *
 * @param array $p Filtros.
 * @return array {results,total,pages}
 */
function agert_coletar_videos(array $p): array {
    $q = agert_query_reunioes_filtradas(array_merge($p, array('posts_per_page' => -1)));
    $items = array();
    
    while ($q->have_posts()) {
        $q->the_post();
        $pid = get_the_ID();
        
        // Meta videos (array)
        $list = agert_meta($pid, 'videos', array());
        if (is_array($list) && !empty($list)) {
            foreach ($list as $v) {
                $v['parent_id'] = $pid;
                $items[] = array(
                    'video' => $v,
                    'meeting' => get_post($pid)
                );
            }
        }
        
        // Meta video_url simples
        $single = agert_meta($pid, 'video_url', '');
        if ($single) {
            $items[] = array(
                'video' => array(
                    'titulo' => get_the_title($pid),
                    'video_url' => $single,
                    'duracao_segundos' => 0,
                    'descricao' => '',
                    'parent_id' => $pid
                ),
                'meeting' => get_post($pid)
            );
        }
    }
    wp_reset_postdata();
    
    // Juntar com CPT 'reuniao_video'
    $rel = new WP_Query(array(
        'post_type' => 'reuniao_video',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'reuniao_relacionada',
                'compare' => 'EXISTS'
            )
        )
    ));
    
    foreach ($rel->posts as $v) {
        $pid = (int) get_post_meta($v->ID, 'reuniao_relacionada', true);
        if ($pid) {
            $items[] = array(
                'video' => array(
                    'titulo' => get_the_title($v->ID),
                    'video_url' => get_post_meta($v->ID, 'video_url', true),
                    'duracao_segundos' => (int) get_post_meta($v->ID, 'duracao_segundos', true),
                    'descricao' => wp_strip_all_tags(get_post_field('post_content', $v->ID)),
                    'parent_id' => $pid
                ),
                'meeting' => get_post($pid)
            );
        }
    }
    
    return array(
        'results' => $items,
        'total' => count($items),
        'pages' => 1
    );
}

/**
 * Cria dados de exemplo se não existirem reuniões.
 */
function agert_seed_demo_if_empty() {
    $existing = get_posts(array(
        'post_type' => 'reuniao',
        'posts_per_page' => 1,
        'post_status' => 'any',
    ));
    if ($existing) {
        return;
    }

    $post_id = wp_insert_post(array(
        'post_type' => 'reuniao',
        'post_status' => 'publish',
        'post_title' => 'Reunião de Demonstração',
        'post_content' => 'Conteúdo de exemplo da reunião.',
    ));
    if (!$post_id) {
        return;
    }

    $now = current_time('mysql');
    update_post_meta($post_id, 'data_hora', $now);
    update_post_meta($post_id, 'duracao_minutos', 90);
    update_post_meta($post_id, 'local', 'Porto Alegre');
    update_post_meta($post_id, 'resumo', 'Reunião inicial de demonstração.');
    update_post_meta($post_id, 'pauta', array('Apresentação do projeto', 'Planejamento das ações'));
    update_post_meta($post_id, 'decisoes', array('Projeto aprovado', 'Próxima reunião agendada'));
    update_post_meta($post_id, 'participantes', array(
        array('nome' => 'João Carlos', 'cargo' => 'Presidente'),
        array('nome' => 'Maria Souza', 'cargo' => 'Diretora'),
    ));

    // Create sample attachments
    $docs = array();
    for ($i = 1; $i <= 2; $i++) {
        $bits = wp_upload_bits("documento-demo-$i.txt", null, "Documento de exemplo $i");
        if (empty($bits['error'])) {
            $filetype = wp_check_filetype($bits['file']);
            $attach_id = wp_insert_attachment(array(
                'post_mime_type' => $filetype['type'],
                'post_title'     => "Documento $i",
                'post_status'    => 'inherit',
            ), $bits['file']);
            if (!is_wp_error($attach_id)) {
                $docs[] = array(
                    'rotulo' => "Documento $i",
                    'arquivo_id' => $attach_id,
                    'tamanho_bytes' => filesize($bits['file']),
                );
            }
        }
    }
    update_post_meta($post_id, 'documentos', $docs);

    // Sample video
    update_post_meta($post_id, 'videos', array(array(
        'titulo' => 'Vídeo de Demonstração',
        'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'duracao_segundos' => 120,
        'descricao' => 'Transmissão de exemplo.',
    )));
}
