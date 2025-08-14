<?php
/**
 * Helpers específicos da página do Presidente.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Retorna a tag <img> de um attachment ID com atributos escapados.
 */
if (!function_exists('agert_img')) {
    function agert_img($id, $size = 'large', $attrs = array()) {
        if (!$id) {
            return '';
        }
        $clean = array();
        foreach ($attrs as $attr => $val) {
            $clean[$attr] = esc_attr($val);
        }
        return wp_get_attachment_image($id, $size, false, $clean);
    }
}

if (!function_exists('agert_meta')) {
    /**
     * Busca meta com suporte a ACF, com fallback para get_post_meta.
     *
     * @param int    $id       ID do post.
     * @param string $key      Chave do meta.
     * @param mixed  $default  Valor padrão caso não encontre.
     * @return mixed
     */
    function agert_meta($id, $key, $default = '') {
        if (function_exists('get_field')) {
            $value = get_field($key, $id);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }
        $value = get_post_meta($id, $key, true);
        return $value !== '' ? $value : $default;
    }
}
