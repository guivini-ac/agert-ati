<?php
/**
 * Shared meta helper functions.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('agert_meta')) {
    /**
     * Retrieves post meta with optional ACF support.
     *
     * @param int    $post_id Post ID.
     * @param string $key     Meta key.
     * @param mixed  $default Default value if meta not found.
     *
     * @return mixed
     */
    function agert_meta($post_id, $key, $default = '') {
        if (function_exists('get_field')) {
            $value = get_field($key, $post_id);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        $value = get_post_meta($post_id, $key, true);
        return $value !== '' ? $value : $default;
    }
}
