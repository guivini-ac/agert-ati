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
function agert_get_reuniao_years(): array {
    global $wpdb;

    $rows = $wpdb->get_col(
        "SELECT DISTINCT YEAR(pm.meta_value)
         FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = 'data_hora'
           AND p.post_type = 'reuniao'
           AND p.post_status = 'publish'
         ORDER BY pm.meta_value DESC"
    );

    $years = array_map('intval', $rows);

    if (empty($years)) {
        $years[] = (int) gmdate('Y');
    }

    return $years;
}

/**
 * Retorna o primeiro ano com conteúdo usando callback.
 *
 * @param int[]   $years   Anos disponíveis.
 * @param int     $year    Ano solicitado.
 * @param callable $checker Função que recebe o ano e retorna true se houver conteúdo.
 *
 * @return int Ano válido encontrado.
 */
function agert_find_year_with_content(array $years, int $year, callable $checker): int {
    $to_try = $year && in_array($year, $years, true)
        ? array_merge(array($year), array_diff($years, array($year)))
        : $years;

    foreach ($to_try as $y) {
        if (call_user_func($checker, $y)) {
            return $y;
        }
    }

    return $years[0] ?? (int) gmdate('Y');
}

/**
 * Retorna lista de anos disponíveis para reuniões.
 *
 * @return int[]
 */
function agert_available_years(): array {
    return agert_get_reuniao_years();
}

/**
 * Retorna o ano ativo considerando querystring.
 *
 * @return int
 */
function agert_active_year(): int {
    $years     = agert_available_years();
    $requested = isset($_GET['ano']) ? (int) $_GET['ano'] : 0;
    if ($requested && in_array($requested, $years, true)) {
        return $requested;
    }
    return $years[0] ?? (int) gmdate('Y');
}

/**
 * Monta WP_Query para reuniões filtradas.
 *
 * @param array $p Parâmetros de filtro.
 *
 * @return WP_Query
 */
function agert_query_reunioes_filtradas(array $p): WP_Query {
    $args = wp_parse_args($p, array(
        'ano'            => 0,
        'q'              => '',
        'tipo'           => '',
        'status'         => '',
        'de'             => '',
        'ate'            => '',
        'local'          => '',
        'ordem'          => 'data_desc',
        'paged'          => 1,
        'posts_per_page' => 9,
        'fields'         => '',
    ));

    $query_args = array(
        'post_type'      => 'reuniao',
        'post_status'    => 'publish',
        'paged'          => (int) $args['paged'],
        'posts_per_page' => (int) $args['posts_per_page'],
    );

    $meta_query = array();
    $tax_query  = array();

    if ($args['ano']) {
        $meta_query[] = array(
            'key'     => 'data_hora',
            'value'   => array($args['ano'] . '-01-01', $args['ano'] . '-12-31'),
            'compare' => 'BETWEEN',
            'type'    => 'DATETIME',
        );
    }

    if ($args['de'] || $args['ate']) {
        $de  = $args['de'] ? $args['de'] : ($args['ano'] ? $args['ano'] . '-01-01' : '');
        $ate = $args['ate'] ? $args['ate'] : ($args['ano'] ? $args['ano'] . '-12-31' : '');
        if ($de && $ate) {
            $meta_query[] = array(
                'key'     => 'data_hora',
                'value'   => array($de, $ate),
                'compare' => 'BETWEEN',
                'type'    => 'DATETIME',
            );
        }
    }

    if ($args['status'] === 'video') {
        $meta_query[] = array(
            'key'     => 'videos',
            'value'   => '',
            'compare' => '!=',
        );
    } elseif ($args['status'] === 'docs') {
        $meta_query[] = array(
            'key'     => 'documentos',
            'value'   => '',
            'compare' => '!=',
        );
    }

    if ($args['local']) {
        $meta_query[] = array(
            'key'     => 'local',
            'value'   => $args['local'],
            'compare' => 'LIKE',
        );
    }

    if ($args['tipo']) {
        if (taxonomy_exists('tipo_reuniao')) {
            $tax_query[] = array(
                'taxonomy' => 'tipo_reuniao',
                'field'    => 'slug',
                'terms'    => $args['tipo'],
            );
        } else {
            $meta_query[] = array(
                'key'   => 'tipo_reuniao',
                'value' => $args['tipo'],
            );
        }
    }

    if ($args['q']) {
        $query_args['s'] = $args['q'];
        $meta_query[]    = array(
            'relation' => 'OR',
            array(
                'key'     => 'resumo',
                'value'   => $args['q'],
                'compare' => 'LIKE',
            ),
            array(
                'key'     => 'pauta',
                'value'   => $args['q'],
                'compare' => 'LIKE',
            ),
        );
    }

    if ($meta_query) {
        $query_args['meta_query'] = array_merge(array('relation' => 'AND'), $meta_query);
    }
    if ($tax_query) {
        $query_args['tax_query'] = $tax_query;
    }

    switch ($args['ordem']) {
        case 'data_asc':
            $query_args['orderby']   = 'meta_value';
            $query_args['meta_key']  = 'data_hora';
            $query_args['order']     = 'ASC';
            $query_args['meta_type'] = 'DATETIME';
            break;
        case 'titulo_az':
            $query_args['orderby'] = 'title';
            $query_args['order']   = 'ASC';
            break;
        case 'titulo_za':
            $query_args['orderby'] = 'title';
            $query_args['order']   = 'DESC';
            break;
        case 'data_desc':
        default:
            $query_args['orderby']   = 'meta_value';
            $query_args['meta_key']  = 'data_hora';
            $query_args['order']     = 'DESC';
            $query_args['meta_type'] = 'DATETIME';
            break;
    }

    if ($args['fields']) {
        $query_args['fields'] = $args['fields'];
    }

    return new WP_Query($query_args);
}

/**
 * Coleta documentos agregados das reuniões filtradas.
 *
 * @param array $args Filtros para agert_query_reunioes_filtradas.
 * @return array {results,total,pages}
 */
function agert_coletar_documentos(array $args): array {
    $per_page = $args['posts_per_page'] ?? 10;
    $q = agert_query_reunioes_filtradas(array_merge($args, array(
        'status' => 'docs',
        'posts_per_page' => -1,
        'paged' => 1,
    )));
    $results = array();
    if ($q->have_posts()) {
        foreach ($q->posts as $p) {
            $docs = agert_meta($p->ID, 'documentos', array());
            if (is_array($docs)) {
                foreach ($docs as $d) {
                    if (!empty($d['arquivo_id']) || !empty($d['arquivo_url'])) {
                        $results[] = array('doc' => $d, 'meeting' => $p);
                    }
                }
            }
        }
    }
    $total = count($results);
    $pages = $per_page > 0 ? (int) ceil($total / $per_page) : 1;
    return array('results' => $results, 'total' => $total, 'pages' => $pages);
}

/**
 * Coleta vídeos agregados das reuniões filtradas.
 *
 * @param array $args Filtros.
 * @return array {results,total,pages}
 */
function agert_coletar_videos(array $args): array {
    $per_page = $args['posts_per_page'] ?? 9;
    $q = agert_query_reunioes_filtradas(array_merge($args, array(
        'status' => 'video',
        'posts_per_page' => -1,
        'paged' => 1,
    )));
    $results = array();
    if ($q->have_posts()) {
        foreach ($q->posts as $p) {
            $videos = agert_meta($p->ID, 'videos', array());
            if (is_array($videos)) {
                foreach ($videos as $v) {
                    $results[] = array('video' => $v, 'meeting' => $p);
                }
            }
        }
    }
    $total = count($results);
    $pages = $per_page > 0 ? (int) ceil($total / $per_page) : 1;
    return array('results' => $results, 'total' => $total, 'pages' => $pages);
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
