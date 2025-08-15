<?php
/**
 * Página de gerenciamento de participantes.
 *
 * @package AGERT
 */

get_header();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_participant_nonce'])) {
    if (wp_verify_nonce($_POST['create_participant_nonce'], 'create_participant') && agert_user_can_create_posts()) {
        $nome       = agert_sanitize_text($_POST['nome_participante'] ?? '');
        $cargo      = agert_sanitize_text($_POST['cargo'] ?? '');
        $email      = sanitize_email($_POST['email'] ?? '');
        $reuniao_id = intval($_POST['reuniao_id'] ?? 0);

        if ($nome && $email && $reuniao_id) {
            $participant_id = wp_insert_post(array(
                'post_title'  => $nome,
                'post_type'   => 'participante',
                'post_status' => 'publish',
                'post_author' => get_current_user_id(),
            ));
            if ($participant_id && !is_wp_error($participant_id)) {
                update_post_meta($participant_id, '_nome_participante', $nome);
                update_post_meta($participant_id, '_cargo', $cargo);
                update_post_meta($participant_id, '_email', $email);
                update_post_meta($participant_id, '_reuniao_id', $reuniao_id);
                wp_redirect(add_query_arg('status', 'participante_created', get_permalink()));
                exit;
            }
        } else {
            $error_message = __('Preencha todos os campos obrigatórios.', 'agert');
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
        <h1 class="mb-0"><i class="bi bi-people text-primary me-2"></i><?php _e('Participantes', 'agert'); ?></h1>
        <?php if (agert_user_can_create_posts()) : ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createParticipantModal">
                <i class="bi bi-plus-circle me-2"></i><?php _e('Novo Participante', 'agert'); ?>
            </button>
        <?php endif; ?>
    </div>

    <?php
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $participants = new WP_Query(array(
        'post_type'      => 'participante',
        'posts_per_page' => 10,
        'paged'          => $paged,
        'post_status'    => 'publish',
    ));

    if ($participants->have_posts()) : ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php _e('Nome', 'agert'); ?></th>
                        <th><?php _e('Cargo', 'agert'); ?></th>
                        <th><?php _e('E-mail', 'agert'); ?></th>
                        <th><?php _e('Reunião', 'agert'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($participants->have_posts()) : $participants->the_post();
                        $reuniao_id = get_post_meta(get_the_ID(), '_reuniao_id', true);
                        $cargo      = get_post_meta(get_the_ID(), '_cargo', true);
                        $email      = get_post_meta(get_the_ID(), '_email', true);
                    ?>
                        <tr>
                            <td><?php the_title(); ?></td>
                            <td><?php echo esc_html($cargo); ?></td>
                            <td><?php echo esc_html($email); ?></td>
                            <td><?php echo $reuniao_id ? esc_html(get_the_title($reuniao_id)) : '-'; ?></td>
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
        <p><?php _e('Nenhum participante encontrado.', 'agert'); ?></p>
    <?php endif; wp_reset_postdata(); ?>
</div>

<?php if (agert_user_can_create_posts()) : ?>
<div class="modal fade" id="createParticipantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <?php wp_nonce_field('create_participant', 'create_participant_nonce'); ?>
                <div class="modal-header">
                    <h5 class="modal-title"><?php _e('Novo Participante', 'agert'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nome_participante" class="form-label"><?php _e('Nome', 'agert'); ?></label>
                        <input type="text" id="nome_participante" name="nome_participante" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="cargo" class="form-label"><?php _e('Cargo', 'agert'); ?></label>
                        <input type="text" id="cargo" name="cargo" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"><?php _e('E-mail', 'agert'); ?></label>
                        <input type="email" id="email" name="email" class="form-control" required>
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
