<?php
/**
 * Hub de Reuniões com filtros e abas.
 *
 * @package AGERT
 */

get_header();

$base_url = get_post_type_archive_link('reuniao');

$years = agert_available_years();
$tab   = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'reunioes';
if ($tab === 'anexos') {
    $tab = 'documentos';
}

$params = array();
$params['ano']    = agert_active_year();
$params['q']      = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
$params['tipo']   = isset($_GET['tipo']) ? sanitize_key($_GET['tipo']) : '';
$params['status'] = isset($_GET['status']) ? sanitize_key($_GET['status']) : '';
$params['de']     = isset($_GET['de']) ? sanitize_text_field($_GET['de']) : '';
$params['ate']    = isset($_GET['ate']) ? sanitize_text_field($_GET['ate']) : '';
$params['local']  = isset($_GET['local']) ? sanitize_text_field($_GET['local']) : '';
$params['ordem']  = isset($_GET['ordem']) ? sanitize_key($_GET['ordem']) : 'data_desc';
$params['paged']  = get_query_var('paged') ? (int) get_query_var('paged') : 1;

$view = isset($_GET['view']) ? sanitize_key($_GET['view']) : 'grid';
$ano  = $params['ano'];

$common_args = array(
    'q'     => $params['q'],
    'tipo'  => $params['tipo'],
    'status'=> $params['status'],
    'de'    => $params['de'],
    'ate'   => $params['ate'],
    'local' => $params['local'],
    'ordem' => $params['ordem'],
    'view'  => $view,
);

// Contadores
$query_reunioes = agert_query_reunioes_filtradas(array_merge($params, array('status' => '', 'posts_per_page' => 9)));
$total_reunioes = $query_reunioes->found_posts;

$videos_data = agert_coletar_videos($params);
$total_videos = $videos_data['total'];

$docs_data = agert_coletar_documentos($params);
$docs_flat = $docs_data['results'];
$total_docs = $docs_data['total'];

$tabs = array(
    'reunioes'   => array('label' => 'Reuniões', 'icon' => 'bi-buildings', 'count' => $total_reunioes),
    'documentos' => array('label' => 'Anexos', 'icon' => 'bi-file-earmark-text', 'count' => $total_docs),
    'videos'     => array('label' => 'Vídeos', 'icon' => 'bi-play-circle', 'count' => $total_videos),
);
?>
<section class="py-5 text-center">
    <div class="container">
        <h1 class="text-center">Reuniões</h1>
        <p class="lead">Atas, resoluções, relatórios e vídeos disponíveis.</p>
        <div class="pills-ano d-flex justify-content-center overflow-auto flex-nowrap mb-4">
            <div class="btn-group" role="group" aria-label="Filtro por ano">
                <?php foreach ($years as $y) :
                    $url = esc_url(add_query_arg(array_merge($common_args, array('ano' => $y, 'tab' => $tab)), $base_url));
                    $active = $ano === $y ? 'btn-brand' : 'btn-outline-brand';
                ?>
                    <a href="<?php echo $url; ?>" class="btn <?php echo $active; ?> btn-sm"><?php echo esc_html($y); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="nav tabbar justify-content-center" role="tablist">
            <?php foreach ($tabs as $key => $info) :
                $active = $tab === $key;
                $url = esc_url(add_query_arg(array_merge($common_args, array('tab' => $key, 'ano' => $ano)), $base_url));
            ?>
                <a class="btn <?php echo $active ? 'btn-brand' : 'btn-outline-brand'; ?> me-2 mb-2" href="<?php echo $url; ?>" aria-current="<?php echo $active ? 'page' : 'false'; ?>">
                    <i class="bi <?php echo esc_attr($info['icon']); ?> me-1"></i><?php echo esc_html($info['label']); ?>
                    <span class="badge-chip ms-1"><?php echo (int) $info['count']; ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="filter-bar mb-3">
        <form method="get" class="row g-2 align-items-end">
            <input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>">
            <input type="hidden" name="ano" value="<?php echo esc_attr($ano); ?>">
            <input type="hidden" name="view" value="<?php echo esc_attr($view); ?>">
            <div class="col-md-3">
                <label for="f-q" class="form-label">Busca</label>
                <input id="f-q" type="search" class="form-control" name="q" placeholder="Buscar por título, pauta, resumo…" value="<?php echo esc_attr($params['q']); ?>">
            </div>
            <div class="col-md-2">
                <label for="f-tipo" class="form-label">Tipo</label>
                <select id="f-tipo" name="tipo" class="form-select">
                    <option value="">Todos</option>
                    <?php $tipos = get_terms(array('taxonomy' => 'tipo_reuniao', 'hide_empty' => false));
                    if (!is_wp_error($tipos)) {
                        foreach ($tipos as $t) {
                            $sel = $params['tipo'] === $t->slug ? 'selected' : '';
                            echo '<option value="' . esc_attr($t->slug) . '" ' . $sel . '>' . esc_html($t->name) . '</option>';
                        }
                    } ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="f-status" class="form-label">Status</label>
                <select id="f-status" name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="video" <?php selected($params['status'], 'video'); ?>>Com vídeo</option>
                    <option value="docs" <?php selected($params['status'], 'docs'); ?>>Com documentos</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="f-de" class="form-label">De</label>
                <input id="f-de" type="date" name="de" class="form-control" value="<?php echo esc_attr($params['de']); ?>">
            </div>
            <div class="col-md-2">
                <label for="f-ate" class="form-label">Até</label>
                <input id="f-ate" type="date" name="ate" class="form-control" value="<?php echo esc_attr($params['ate']); ?>">
            </div>
            <div class="col-md-2">
                <label for="f-local" class="form-label">Local</label>
                <input id="f-local" type="text" name="local" class="form-control" value="<?php echo esc_attr($params['local']); ?>">
            </div>
            <div class="col-md-2">
                <label for="f-ordem" class="form-label">Ordenar por</label>
                <select id="f-ordem" name="ordem" class="form-select">
                    <option value="data_desc" <?php selected($params['ordem'], 'data_desc'); ?>>Data ↓</option>
                    <option value="data_asc" <?php selected($params['ordem'], 'data_asc'); ?>>Data ↑</option>
                    <option value="titulo_az" <?php selected($params['ordem'], 'titulo_az'); ?>>Título A–Z</option>
                    <option value="titulo_za" <?php selected($params['ordem'], 'titulo_za'); ?>>Título Z–A</option>
                </select>
            </div>
            <div class="col-md-2 ms-auto">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand flex-grow-1">Aplicar filtros</button>
                    <?php $clear_url = esc_url(add_query_arg(array('tab' => $tab, 'ano' => $ano, 'view' => $view), $base_url)); ?>
                    <a href="<?php echo $clear_url; ?>" class="btn btn-outline-brand">Limpar</a>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end mt-2">
                <div class="btn-group" role="group">
                    <button type="button" class="btn <?php echo $view === 'grid' ? 'btn-brand' : 'btn-outline-brand'; ?>" data-view-toggle="grid"><i class="bi bi-grid"></i></button>
                    <button type="button" class="btn <?php echo $view === 'list' ? 'btn-brand' : 'btn-outline-brand'; ?>" data-view-toggle="list"><i class="bi bi-list"></i></button>
                </div>
            </div>
        </form>
        <?php
        $chip_labels = array(
            'q' => 'Busca',
            'tipo' => 'Tipo',
            'status' => 'Status',
            'de' => 'De',
            'ate' => 'Até',
            'local' => 'Local',
            'ordem' => 'Ordenar',
        );
        $active_filters = array_filter($params, function ($v, $k) {
            return $k !== 'ano' && $k !== 'paged' && $v !== '' && $v !== 'data_desc';
        }, ARRAY_FILTER_USE_BOTH);
        if ($active_filters) : ?>
            <div class="filter-chips">
                <?php foreach ($active_filters as $key => $val) :
                    $remove_url = esc_url(remove_query_arg($key));
                    $label = $chip_labels[$key] ?? $key;
                    if ($key === 'tipo') {
                        $term = get_term_by('slug', $val, 'tipo_reuniao');
                        $val = $term ? $term->name : $val;
                    }
                    if ($key === 'status') {
                        $val = $val === 'video' ? 'Com vídeo' : 'Com documentos';
                    }
                    if ($key === 'ordem') {
                        $map = array('data_asc' => 'Data ↑', 'data_desc' => 'Data ↓', 'titulo_az' => 'Título A–Z', 'titulo_za' => 'Título Z–A');
                        $val = $map[$val] ?? $val;
                    }
                    ?>
                    <span class="filter-chip badge-chip"><?php echo esc_html($label . ': ' . $val); ?> <a href="<?php echo $remove_url; ?>" aria-label="Remover filtro <?php echo esc_attr($label); ?>">&times;</a></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php
switch ($tab) {
    case 'documentos':
        $per_page = 10;
        $total = $total_docs;
        $total_pages = max(1, (int) ceil($total / $per_page));
        $paged = $params['paged'];
        $docs_slice = array_slice($docs_flat, ($paged - 1) * $per_page, $per_page);
        if ($docs_slice) {
            foreach ($docs_slice as $item) {
                $doc = $item['doc'];
                $meeting = $item['meeting'];
                include locate_template('parts/reunioes/row-documento.php', false, false);
            }
        } else {
            $reset_url = esc_url(add_query_arg(array('tab' => 'documentos', 'ano' => $ano), $base_url));
            include locate_template('parts/reunioes/empty-state.php', false, false);
        }
        if ($total_pages > 1) {
            echo '<nav class="mt-4">' . paginate_links(array(
                'base'    => esc_url(add_query_arg(array_merge($common_args, array('tab' => 'documentos', 'ano' => $ano, 'paged' => '%#%')), $base_url)),
                'format'  => '',
                'current' => $paged,
                'total'   => $total_pages,
            )) . '</nav>';
        }
        break;

    case 'videos':
        $per_page = 9;
        $paged = $params['paged'];
        $videos_slice = array_slice($videos_data['results'], ($paged - 1) * $per_page, $per_page);
        if ($videos_slice) {
            echo '<div class="row g-4">';
            foreach ($videos_slice as $item) {
                $video = $item['video'];
                $meeting = $item['meeting'];
                echo '<div class="col-sm-6 col-lg-4">';
                include locate_template('parts/reunioes/card-video.php', false, false);
                echo '</div>';
            }
            echo '</div>';
            $total_pages = max(1, (int) ceil($videos_data['total'] / $per_page));
            echo '<nav class="mt-4">' . paginate_links(array(
                'current' => $paged,
                'total'   => $total_pages,
                'add_args' => array_merge($common_args, array('tab' => 'videos', 'ano' => $ano)),
            )) . '</nav>';
        } else {
            $reset_url = esc_url(add_query_arg(array('tab' => 'videos', 'ano' => $ano), $base_url));
            $message = __('Nenhum vídeo encontrado para os filtros aplicados.', 'agert');
            include locate_template('parts/reunioes/empty-state.php', false, false);
        }
        break;

    case 'reunioes':
    default:
        if ($query_reunioes->have_posts()) {
            if ($view === 'list') {
                echo '<div class="list-view d-flex flex-column gap-3">';
                while ($query_reunioes->have_posts()) {
                    $query_reunioes->the_post();
                    include locate_template('parts/reunioes/card-reuniao.php', false, false);
                }
                echo '</div>';
            } else {
                echo '<div class="row g-4">';
                while ($query_reunioes->have_posts()) {
                    $query_reunioes->the_post();
                    echo '<div class="col-sm-6 col-lg-4">';
                    include locate_template('parts/reunioes/card-reuniao.php', false, false);
                    echo '</div>';
                }
                echo '</div>';
            }
            echo '<nav class="mt-4">' . paginate_links(array(
                'total'    => $query_reunioes->max_num_pages,
                'current'  => $params['paged'],
                'add_args' => array_merge($common_args, array('tab' => 'reunioes', 'ano' => $ano)),
            )) . '</nav>';
            wp_reset_postdata();
        } else {
            $reset_url = esc_url(add_query_arg(array('tab' => 'reunioes', 'ano' => $ano), $base_url));
            $message = __('Nenhuma reunião encontrada para os filtros aplicados.', 'agert');
            include locate_template('parts/reunioes/empty-state.php', false, false);
        }
        break;
}
?>
</div>
<?php get_footer(); ?>
