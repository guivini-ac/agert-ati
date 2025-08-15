<?php
/**
 * Página de gerenciamento de anexos.
 *
 * @package AGERT
 */

get_header();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_attachment_nonce'])) {
    if (wp_verify_nonce($_POST['create_attachment_nonce'], 'create_attachment') && agert_user_can_create_posts()) {
        $title      = agert_sanitize_text($_POST['attachment_title'] ?? '');
        $reuniao_id = intval($_POST['reuniao_id'] ?? 0);
        $file       = $_FILES['arquivo'] ?? null;

        if ($title && $reuniao_id && $file && !empty($file['name'])) {
            $valid = agert_validate_file_upload($file);
            if ($valid === true) {
                $upload = wp_handle_upload($file, array('test_form' => false));
                if (!isset($upload['error'])) {
                    $anexo_id = wp_insert_post(array(
                        'post_title'  => $title,
                        'post_type'   => 'anexo',
                        'post_status' => 'publish',
                        'post_author' => get_current_user_id(),
                    ));
                    if ($anexo_id && !is_wp_error($anexo_id)) {
                        update_post_meta($anexo_id, '_arquivo', $upload['url']);
                        update_post_meta($anexo_id, '_reuniao_id', $reuniao_id);
                        wp_redirect(add_query_arg('status', 'anexo_created', get_permalink()));
                        exit;
                    }
                } else {
                    $error_message = $upload['error'];
                }
            } else {
                $error_message = $valid->get_error_message();
            }
        } else {
            $error_message = __('Preencha todos os campos.', 'agert');
        }
    } else {
        $error_message = __('Permissão negada.', 'agert');
    }
}
?>
<div class="container py-5">
    <?php agert_show_status_message(); ?>
    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo esc_html($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="bi bi-paperclip text-primary me-2"></i><?php _e('Anexos', 'agert'); ?></h1>
        <?php if (agert_user_can_create_posts()) : ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAttachmentModal">
                <i class="bi bi-plus-circle me-2"></i><?php _e('Novo Anexo', 'agert'); ?>
            </button>
        <?php endif; ?>
    </div>

    <?php
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $attachments = new WP_Query(array(
        'post_type'      => 'anexo',
        'posts_per_page' => 10,
        'paged'          => $paged,
        'post_status'    => 'publish',
    ));

    if ($attachments->have_posts()) : ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php _e('Título', 'agert'); ?></th>
                        <th><?php _e('Reunião', 'agert'); ?></th>
                        <th><?php _e('Arquivo', 'agert'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($attachments->have_posts()) : $attachments->the_post();
                        $reuniao_id = get_post_meta(get_the_ID(), '_reuniao_id', true);
                        $arquivo    = get_post_meta(get_the_ID(), '_arquivo', true);
                    ?>
                        <tr>
                            <td><?php the_title(); ?></td>
                            <td><?php echo $reuniao_id ? esc_html(get_the_title($reuniao_id)) : '-'; ?></td>
                            <td><a class="btn btn-sm btn-outline-primary" href="<?php echo esc_url($arquivo); ?>" target="_blank"><?php _e('Baixar', 'agert'); ?></a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php the_posts_pagination(array(
            'prev_text' => __('Anterior', 'agert'),
            'next_text' => __('Próxima', 'agert'),
        )); ?>
    <?php else : ?>
        <p><?php _e('Nenhum anexo encontrado.', 'agert'); ?></p>
    <?php endif; wp_reset_postdata(); ?>
</div>

<?php if (agert_user_can_create_posts()) : ?>
<div class="modal fade" id="createAttachmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('create_attachment', 'create_attachment_nonce'); ?>
                <div class="modal-header">
                    <h5 class="modal-title"><?php _e('Novo Anexo', 'agert'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="attachment_title" class="form-label"><?php _e('Título', 'agert'); ?></label>
                        <input type="text" id="attachment_title" name="attachment_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="attachment_file" class="form-label"><?php _e('Arquivo', 'agert'); ?></label>
                        <input type="file" id="attachment_file" name="arquivo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="reuniao_id" class="form-label"><?php _e('Reunião', 'agert'); ?></label>
                        <select id="reuniao_id" name="reuniao_id" class="form-select" required data-load-meetings>
                            <option value=""><?php _e('Selecione uma reunião', 'agert'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php _e('Cancelar', 'agert'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php _e('Salvar', 'agert'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php get_footer();
