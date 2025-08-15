<?php
/**
 * Template da página de Reuniões
 * 
 * @package AGERT
 */

get_header();

// Processar formulário de criação de reunião
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_meeting_nonce'])) {
    if (wp_verify_nonce($_POST['create_meeting_nonce'], 'create_meeting') && agert_user_can_create_posts()) {
        
        $title = sanitize_text_field($_POST['meeting_title']);
        $content = sanitize_textarea_field($_POST['meeting_content']);
        $data_hora = sanitize_text_field($_POST['data_hora']);
        $local = sanitize_text_field($_POST['local']);
        $pauta = preg_split("/\r\n|\r|\n/", $_POST['pauta']);
        $pauta = array_filter(array_map('sanitize_text_field', array_map('trim', $pauta)));
        $tipo_reuniao = sanitize_text_field($_POST['tipo_reuniao']);
        
        if (!empty($title) && !empty($data_hora)) {
            $meeting_id = wp_insert_post(array(
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'reuniao',
                'post_author' => get_current_user_id()
            ));
            
            if ($meeting_id && !is_wp_error($meeting_id)) {
                // Salvar meta fields
                update_post_meta($meeting_id, '_data_hora', $data_hora);
                update_post_meta($meeting_id, '_local', $local);
                update_post_meta($meeting_id, 'pauta', $pauta);
                
                // Definir taxonomia se selecionada
                if (!empty($tipo_reuniao)) {
                    wp_set_object_terms($meeting_id, $tipo_reuniao, 'tipo_reuniao');
                }
                
                wp_redirect(add_query_arg('status', 'reuniao_created', get_permalink()));
                exit;
            } else {
                wp_redirect(add_query_arg('status', 'error', get_permalink()));
                exit;
            }
        } else {
            $error_message = __('Por favor, preencha todos os campos obrigatórios.', 'agert');
        }
    } else {
        $error_message = __('Você não tem permissão para criar reuniões ou o nonce é inválido.', 'agert');
    }
}
?>

<div class="container py-5">
    <?php agert_show_status_message(); ?>
    
    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?php echo esc_html($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1><i class="bi bi-calendar-event text-primary me-2"></i><?php _e('Reuniões', 'agert'); ?></h1>
                    <p class="text-muted"><?php _e('Gerencie as reuniões da AGERT', 'agert'); ?></p>
                </div>
                <?php if (agert_user_can_create_posts()) : ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMeetingModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        <?php _e('Nova Reunião', 'agert'); ?>
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Lista de reuniões existentes -->
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $meetings = new WP_Query(array(
                'post_type' => 'reuniao',
                'posts_per_page' => 10,
                'paged' => $paged,
                'post_status' => 'publish',
                'orderby' => 'meta_value',
                'meta_key' => '_data_hora',
                'order' => 'DESC'
            ));
            
            if ($meetings->have_posts()) :
            ?>
                <div class="row g-4">
                    <?php while ($meetings->have_posts()) : $meetings->the_post();
                        $data_hora = get_post_meta(get_the_ID(), '_data_hora', true);
                        $local = get_post_meta(get_the_ID(), '_local', true);
                        $status_class = agert_get_meeting_status_class(get_the_ID());
                        $status_text = agert_get_meeting_status_text(get_the_ID());
                        $tipos = get_the_terms(get_the_ID(), 'tipo_reuniao');

                        get_template_part('template-parts/reunioes/meeting-card', null, array(
                            'status_class' => $status_class,
                            'status_text'  => $status_text,
                            'tipos'        => $tipos,
                            'data_hora'    => $data_hora,
                            'local'        => $local,
                        ));
                    endwhile; ?>
                </div>
                
                <!-- Paginação -->
                <?php if ($meetings->max_num_pages > 1) : ?>
                    <div class="mt-5">
                        <nav aria-label="<?php _e('Navegação de páginas', 'agert'); ?>">
                            <?php
                            echo paginate_links(array(
                                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                                'format' => '?paged=%#%',
                                'current' => max(1, get_query_var('paged')),
                                'total' => $meetings->max_num_pages,
                                'type' => 'list',
                                'prev_text' => '<i class="bi bi-chevron-left"></i> ' . __('Anterior', 'agert'),
                                'next_text' => __('Próxima', 'agert') . ' <i class="bi bi-chevron-right"></i>'
                            ));
                            ?>
                        </nav>
                    </div>
                <?php endif; ?>
                
            <?php else : ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted mb-3"></i>
                    <h5><?php _e('Nenhuma reunião encontrada', 'agert'); ?></h5>
                    <p class="text-muted"><?php _e('Ainda não há reuniões cadastradas no sistema.', 'agert'); ?></p>
                    <?php if (agert_user_can_create_posts()) : ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMeetingModal">
                            <i class="bi bi-plus-circle me-2"></i>
                            <?php _e('Criar primeira reunião', 'agert'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; wp_reset_postdata(); ?>
        </div>
        
        <div class="col-lg-4">
            <!-- Sidebar com filtros e informações -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-funnel me-2"></i><?php _e('Filtros', 'agert'); ?></h6>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-0">
                        <div class="mb-3">
                            <label for="tipo_reuniao_filter" class="form-label"><?php _e('Tipo de Reunião', 'agert'); ?></label>
                            <select name="tipo_reuniao" id="tipo_reuniao_filter" class="form-select">
                                <option value=""><?php _e('Todos os tipos', 'agert'); ?></option>
                                <?php
                                $tipos = get_terms(array(
                                    'taxonomy' => 'tipo_reuniao',
                                    'hide_empty' => false
                                ));
                                foreach ($tipos as $tipo) {
                                    echo '<option value="' . esc_attr($tipo->slug) . '"' . selected($_GET['tipo_reuniao'] ?? '', $tipo->slug, false) . '>' . esc_html($tipo->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="search_filter" class="form-label"><?php _e('Buscar', 'agert'); ?></label>
                            <input type="text" name="search" id="search_filter" class="form-control" value="<?php echo esc_attr($_GET['search'] ?? ''); ?>" placeholder="<?php _e('Digite para buscar...', 'agert'); ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search me-2"></i>
                            <?php _e('Filtrar', 'agert'); ?>
                        </button>
                        
                        <?php if (!empty($_GET['tipo_reuniao']) || !empty($_GET['search'])) : ?>
                            <a href="<?php echo esc_url(remove_query_arg(array('tipo_reuniao', 'search'))); ?>" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                                <i class="bi bi-x me-2"></i>
                                <?php _e('Limpar filtros', 'agert'); ?>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            
            <!-- Links úteis -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-links me-2"></i><?php _e('Links Úteis', 'agert'); ?></h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo esc_url(get_post_type_archive_link('reuniao')); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-archive me-2"></i>
                            <?php _e('Arquivo de Reuniões', 'agert'); ?>
                        </a>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('anexos'))); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-paperclip me-2"></i>
                            <?php _e('Gerenciar Anexos', 'agert'); ?>
                        </a>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('participantes'))); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-people me-2"></i>
                            <?php _e('Gerenciar Participantes', 'agert'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (agert_user_can_create_posts()) : ?>
<!-- Modal para criar reunião -->
<div class="modal fade" id="createMeetingModal" tabindex="-1" aria-labelledby="createMeetingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
                <?php wp_nonce_field('create_meeting', 'create_meeting_nonce'); ?>
                
                <div class="modal-header">
                    <h5 class="modal-title" id="createMeetingModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        <?php _e('Nova Reunião', 'agert'); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="meeting_title" class="form-label"><?php _e('Título da Reunião *', 'agert'); ?></label>
                            <input type="text" class="form-control" id="meeting_title" name="meeting_title" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="data_hora" class="form-label"><?php _e('Data e Hora *', 'agert'); ?></label>
                            <input type="datetime-local" class="form-control" id="data_hora" name="data_hora" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="tipo_reuniao" class="form-label"><?php _e('Tipo de Reunião', 'agert'); ?></label>
                            <select class="form-select" id="tipo_reuniao" name="tipo_reuniao">
                                <option value=""><?php _e('Selecione o tipo', 'agert'); ?></option>
                                <?php
                                $tipos = get_terms(array(
                                    'taxonomy' => 'tipo_reuniao',
                                    'hide_empty' => false
                                ));
                                foreach ($tipos as $tipo) {
                                    echo '<option value="' . esc_attr($tipo->slug) . '">' . esc_html($tipo->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label for="local" class="form-label"><?php _e('Local', 'agert'); ?></label>
                            <input type="text" class="form-control" id="local" name="local" placeholder="<?php _e('Ex: Sede da AGERT - Sala de Reuniões', 'agert'); ?>">
                        </div>
                        
                        <div class="col-12">
                            <label for="pauta" class="form-label"><?php _e('Pauta', 'agert'); ?></label>
                            <textarea class="form-control" id="pauta" name="pauta" rows="3" placeholder="<?php _e('Descreva os principais pontos da reunião...', 'agert'); ?>"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label for="meeting_content" class="form-label"><?php _e('Descrição/Observações', 'agert'); ?></label>
                            <textarea class="form-control" id="meeting_content" name="meeting_content" rows="4" placeholder="<?php _e('Informações adicionais sobre a reunião...', 'agert'); ?>"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <?php _e('Cancelar', 'agert'); ?>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php _e('Criar Reunião', 'agert'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php else : ?>
<!-- Mensagem para usuários não logados -->
<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginRequiredModalLabel">
                    <i class="bi bi-lock me-2"></i>
                    <?php _e('Login Necessário', 'agert'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-shield-lock display-1 text-muted mb-3"></i>
                <p><?php _e('Para criar reuniões, você precisa estar logado e ter as permissões necessárias.', 'agert'); ?></p>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    <?php _e('Fazer Login', 'agert'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php get_footer(); ?>