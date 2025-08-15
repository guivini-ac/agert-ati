<?php
/**
 * Define campos personalizados para a página do Presidente.
 *
 * @package AGERT
 */

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('acf_add_local_field_group')) {
    add_action('acf/init', function () {
        acf_add_local_field_group(array(
            'key'    => 'group_presidente',
            'title'  => __('Página Presidente', 'agert'),
            'fields' => array(
                array(
                    'key'           => 'field_foto_presidente_id',
                    'label'         => __('Foto do Presidente', 'agert'),
                    'name'          => 'foto_presidente_id',
                    'type'          => 'image',
                    'return_format' => 'id',
                    'preview_size'  => 'medium',
                    'library'       => 'all',
                ),
                array(
                    'key'   => 'field_nome_presidente',
                    'label' => __('Nome', 'agert'),
                    'name'  => 'nome_presidente',
                    'type'  => 'text',
                ),
                array(
                    'key'   => 'field_cargo_titulo',
                    'label' => __('Cargo/Título', 'agert'),
                    'name'  => 'cargo_titulo',
                    'type'  => 'text',
                ),
                array(
                    'key'   => 'field_mandato_periodo',
                    'label' => __('Mandato', 'agert'),
                    'name'  => 'mandato_periodo',
                    'type'  => 'text',
                ),
                array(
                    'key'   => 'field_formacao',
                    'label' => __('Formação', 'agert'),
                    'name'  => 'formacao',
                    'type'  => 'text',
                ),
                array(
                    'key'   => 'field_especializacao',
                    'label' => __('Especialização', 'agert'),
                    'name'  => 'especializacao',
                    'type'  => 'text',
                ),
                array(
                    'key'   => 'field_bio_breve',
                    'label' => __('Biografia', 'agert'),
                    'name'  => 'bio_breve',
                    'type'  => 'textarea',
                    'rows'  => 5,
                ),
                array(
                    'key'        => 'field_experiencias',
                    'label'      => __('Experiências Profissionais', 'agert'),
                    'name'       => 'experiencias',
                    'type'       => 'repeater',
                    'layout'     => 'block',
                    'sub_fields' => array(
                        array(
                            'key'   => 'field_exp_cargo',
                            'label' => __('Cargo', 'agert'),
                            'name'  => 'cargo',
                            'type'  => 'text',
                        ),
                        array(
                            'key'   => 'field_exp_orgao',
                            'label' => __('Órgão', 'agert'),
                            'name'  => 'orgao',
                            'type'  => 'text',
                        ),
                        array(
                            'key'   => 'field_exp_periodo',
                            'label' => __('Período', 'agert'),
                            'name'  => 'periodo',
                            'type'  => 'text',
                        ),
                    ),
                ),
                array(
                    'key'        => 'field_formacoes',
                    'label'      => __('Formações Acadêmicas', 'agert'),
                    'name'       => 'formacoes',
                    'type'       => 'repeater',
                    'layout'     => 'block',
                    'sub_fields' => array(
                        array(
                            'key'   => 'field_form_curso',
                            'label' => __('Curso', 'agert'),
                            'name'  => 'curso',
                            'type'  => 'text',
                        ),
                        array(
                            'key'   => 'field_form_instituicao',
                            'label' => __('Instituição', 'agert'),
                            'name'  => 'instituicao',
                            'type'  => 'text',
                        ),
                        array(
                            'key'   => 'field_form_ano',
                            'label' => __('Ano', 'agert'),
                            'name'  => 'ano',
                            'type'  => 'text',
                        ),
                    ),
                ),
                array(
                    'key'   => 'field_mensagem',
                    'label' => __('Mensagem', 'agert'),
                    'name'  => 'mensagem',
                    'type'  => 'textarea',
                ),
                array(
                    'key'   => 'field_assinatura_nome',
                    'label' => __('Assinatura - Nome', 'agert'),
                    'name'  => 'assinatura_nome',
                    'type'  => 'text',
                ),
                array(
                    'key'   => 'field_assinatura_cargo',
                    'label' => __('Assinatura - Cargo', 'agert'),
                    'name'  => 'assinatura_cargo',
                    'type'  => 'text',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param'    => 'page_template',
                        'operator' => '==',
                        'value'    => 'page-presidente.php',
                    ),
                ),
            ),
        ));
    });
}

