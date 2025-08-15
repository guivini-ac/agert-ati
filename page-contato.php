<?php
/**
 * Template da página "Contato".
 *
 * @package AGERT
 */

get_header();

$success_message = '';
$error_message   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_nonce'])) {
    if (wp_verify_nonce($_POST['contact_nonce'], 'agert_contact')) {
        $name    = sanitize_text_field($_POST['name'] ?? '');
        $email   = sanitize_email($_POST['email'] ?? '');
        $phone   = sanitize_text_field($_POST['phone'] ?? '');
        $message = sanitize_textarea_field($_POST['message'] ?? '');

        if ($name && $email && $message) {
            $to      = get_option('admin_email');
            $subject = sprintf(__('Contato de %s', 'agert'), $name);
            $body    = "Nome: $name\nEmail: $email\nTelefone: $phone\n\nMensagem:\n$message";

            if (wp_mail($to, $subject, $body)) {
                $success_message = __('Mensagem enviada com sucesso!', 'agert');
            } else {
                $error_message = __('Ocorreu um erro ao enviar a mensagem.', 'agert');
            }
        } else {
            $error_message = __('Por favor, preencha os campos obrigatórios.', 'agert');
        }
    } else {
        $error_message = __('Falha na validação do formulário.', 'agert');
    }
}

$contact_address = get_option('agert_contact_address', 'Rua dos Reguladores, 123 - Centro, Timon/MA');
$contact_phone   = get_option('agert_contact_phone', '(99) 3212-3456');
$contact_email   = get_option('agert_contact_email', 'contato@agert.timon.ma.gov.br');
$contact_map_url = get_option('agert_contact_map_url', 'https://www.google.com/maps?q=Timon+MA&output=embed');
?>

<div class="container py-5">
    <h1 class="mb-4"><?php the_title(); ?></h1>

    <?php if ($success_message) : ?>
        <div class="alert alert-success" role="alert"><?php echo esc_html($success_message); ?></div>
    <?php elseif ($error_message) : ?>
        <div class="alert alert-danger" role="alert"><?php echo esc_html($error_message); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-6">
            <form method="post" class="mb-4">
                <?php wp_nonce_field('agert_contact', 'contact_nonce'); ?>
                <div class="mb-3">
                    <label for="name" class="form-label"><?php _e('Nome', 'agert'); ?>*</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"><?php _e('E-mail', 'agert'); ?>*</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label"><?php _e('Telefone', 'agert'); ?></label>
                    <input type="text" id="phone" name="phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label"><?php _e('Mensagem', 'agert'); ?>*</label>
                    <textarea id="message" name="message" rows="5" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><?php _e('Enviar', 'agert'); ?></button>
            </form>
        </div>

        <div class="col-lg-6">
            <h2 class="h5 mb-3"><?php _e('Informações de Contato', 'agert'); ?></h2>
            <p class="mb-2"><i class="bi bi-geo-alt me-1"></i><?php echo esc_html($contact_address); ?></p>
            <p class="mb-2"><i class="bi bi-telephone me-1"></i><?php echo esc_html($contact_phone); ?></p>
            <p class="mb-4"><i class="bi bi-envelope me-1"></i><?php echo esc_html($contact_email); ?></p>

            <div class="ratio ratio-16x9">
                <iframe src="<?php echo esc_url($contact_map_url); ?>" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<?php get_footer();
