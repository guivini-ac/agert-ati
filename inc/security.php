<?php
/**
 * Funções de segurança e sanitização
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sanitiza texto simples.
 */
function agert_sanitize_text($text) {
    return sanitize_text_field($text);
}

/**
 * Sanitiza conteúdo de textarea permitindo tags básicas.
 */
function agert_sanitize_textarea($text) {
    return wp_kses_post($text);
}

/**
 * Valida upload de arquivos.
 */
function agert_validate_file_upload($file) {
    $allowed_types = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif');
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_types)) {
        return new WP_Error('invalid_file_type', __('Tipo de arquivo não permitido.', 'agert'));
    }

    if ($file['size'] > 10 * 1024 * 1024) {
        return new WP_Error('file_too_large', __('Arquivo muito grande. Máximo 10MB.', 'agert'));
    }

    return true;
}
