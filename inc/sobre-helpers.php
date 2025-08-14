<?php
/**
 * Helpers para página Sobre.
 *
 * @package AGERT
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Recupera metadados da página "Sobre" com suporte a aliases.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Chave principal.
 * @param mixed  $default Valor padrão.
 *
 * @return mixed
 */
function agert_sobre_meta($post_id, $key, $default = '') {
    $aliases = array(
        'sobre_texto' => array('sobre_descricao'),
        'valores'     => array('nossos_valores'),
        'orgaos'      => array('estrutura_org'),
    );

    $value = agert_meta($post_id, $key, null);
    if ($value !== null && $value !== '' && $value !== array()) {
        return $value;
    }

    if (isset($aliases[$key])) {
        foreach ($aliases[$key] as $alias) {
            $value = agert_meta($post_id, $alias, null);
            if ($value !== null && $value !== '' && $value !== array()) {
                return $value;
            }
        }
    }

    return $default;
}

/**
 * Imprime ícone do Bootstrap Icons.
 *
 * @param string $name  Nome do ícone.
 * @param string $class Classes adicionais.
 * @return void
 */
function agert_icon($name, $class = '') {
    if (empty($name)) {
        return;
    }
    $classes = trim('bi bi-' . sanitize_html_class($name) . ' ' . $class);
    echo '<i class="' . esc_attr($classes) . '" aria-hidden="true"></i>';
}

/**
 * Preenche a página "Sobre" com dados de exemplo se estiver vazia.
 *
 * @param int $page_id ID da página.
 *
 * @return void
 */
function agert_sobre_seed_if_empty(int $page_id): void {
    $has_intro = (bool) agert_meta($page_id, 'sobre_texto', '');
    $has_vals  = agert_meta($page_id, 'valores', array());
    $has_org   = agert_meta($page_id, 'orgaos', array());

    if ($has_intro && !empty($has_vals) && !empty($has_org)) {
        return;
    }

    update_post_meta($page_id, 'sobre_texto',
        'A Agência Reguladora de Serviços Públicos Delegados do Município de Timon é responsável por regular, fiscalizar e controlar os serviços públicos delegados no município.');

    update_post_meta($page_id, 'valores', array(
        array('titulo' => 'Transparência',     'descricao' => 'Atuação clara e aberta à sociedade.',          'icone' => 'eye'),
        array('titulo' => 'Eficiência',        'descricao' => 'Otimização de recursos e processos.',          'icone' => 'speedometer'),
        array('titulo' => 'Responsabilidade',  'descricao' => 'Compromisso com o interesse público.',         'icone' => 'heart'),
        array('titulo' => 'Imparcialidade',    'descricao' => 'Decisões técnicas e isentas.',                'icone' => 'scale'),
        array('titulo' => 'Inovação',          'descricao' => 'Busca por melhores práticas.',               'icone' => 'cpu'),
    ));

    update_post_meta($page_id, 'orgaos', array(
        array('titulo' => 'Presidência',       'descricao' => 'Direção geral da agência.',                   'icone' => 'person-badge'),
        array('titulo' => 'Diretoria Técnica', 'descricao' => 'Análises e pareceres técnicos.',               'icone' => 'clipboard-data'),
        array('titulo' => 'Diretoria Jurídica','descricao' => 'Assessoria jurídica e legal.',                'icone' => 'hammer'),
    ));
}

