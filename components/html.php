<?php
/**
 * Simple HTML helpers for theme components.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('agert_button')) {
    function agert_button(string $label, string $href, string $variant = 'brand', array $attrs = array()): string {
        $class   = 'btn btn-' . ($variant === 'outline' ? 'outline-brand' : 'brand');
        $attrs['class'] = trim(($attrs['class'] ?? '') . ' ' . $class);
        $attr_str = '';
        foreach ($attrs as $k => $v) {
            $attr_str .= sprintf(' %s="%s"', esc_attr($k), esc_attr($v));
        }
        return sprintf('<a href="%s"%s>%s</a>', esc_url($href), $attr_str, esc_html($label));
    }
}

if (!function_exists('agert_badge')) {
    function agert_badge(string $text): string {
        return sprintf('<span class="badge-chip">%s</span>', esc_html($text));
    }
}

if (!function_exists('agert_meta_icon')) {
    function agert_meta_icon(string $icon, string $text): string {
        return sprintf('<span class="me-2"><i class="bi %s"></i>%s</span>', esc_attr($icon), esc_html($text));
    }
}
