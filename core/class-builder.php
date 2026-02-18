<?php

if (!defined('ABSPATH')) exit;

class Builder
{
    /**
     * Fluxo principal de criação do post.
     * Detecta automaticamente o editor e orquestra a geração.
     */
    public static function create_draft_post($data)
    {
        if (!$data || isset($data['error'])) return false;

        // Detecta automaticamente se o Elementor está ativo
        $editor_mode = self::detect_preferred_editor();

        // Processa o conteúdo bruto da IA para o formato detectado
        $processed_content = self::process_article_content($data, $editor_mode);

        $post_args = [
            'post_title' => sanitize_text_field($data['h1_title']),
            'post_content' => $processed_content['html'],
            'post_excerpt' => sanitize_text_field($data['summary']),
            'post_status' => 'draft',
            'post_type' => 'post',
            'post_name' => sanitize_title($data['url_slug']),
        ];

        $post_id = wp_insert_post($post_args);

        if ($post_id && !is_wp_error($post_id)) {
            // Se o Elementor for detectado, salva os metadados específicos
            if ($editor_mode === 'elementor') {
                self::save_elementor_meta($post_id, $processed_content['elementor_data']);
            }

            // Salva metadados de plugins de SEO
            self::save_seo_metadata($post_id, $data);

            // Adiciona as Tags
            if (!empty($data['tags'])) {
                wp_set_post_tags($post_id, $data['tags']);
            }
        }

        return $post_id;
    }

    /**
     * Detecta se o Elementor está instalado e ativo.
     * Retorna 'elementor' se ativo, caso contrário 'gutenberg'.
     */
    private static function detect_preferred_editor()
    {
        if (!function_exists('is_plugin_active')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        // Verifica se o diretório principal do Elementor está ativo
        if (is_plugin_active('elementor/elementor.php')) {
            return 'elementor';
        }

        return 'gutenberg';
    }

    /**
     * Varre os blocos do artigo e decide como renderizá-los.
     */
    private static function process_article_content($data, $mode)
    {
        $html_output = '';
        $elementor_elements = [];

        if (isset($data['article_blocks']) && is_array($data['article_blocks'])) {
            foreach ($data['article_blocks'] as $block_text) {
                $block_text = trim($block_text);
                if (empty($block_text)) continue;

                // Identifica o tipo de bloco
                if (strpos($block_text, '[TABLE_HERE]') !== false && !empty($data['table_html'])) {
                    $type = 'table';
                    $content = $data['table_html'];
                } elseif (preg_match('/^<h([1-6])>(.*?)<\/h\1>/i', $block_text, $matches)) {
                    $type = 'heading';
                    $content = $matches[2];
                    $level = $matches[1];
                } else {
                    $type = 'paragraph';
                    $content = preg_replace('/^<p>(.*?)<\/p>$/i', '$1', $block_text);
                }

                // Constrói conforme o editor
                if ($mode === 'elementor') {
                    $elementor_elements[] = self::build_elementor_widget_data($type, $content, $level ?? 2);
                } else {
                    $html_output .= self::render_gutenberg_block($type, $content, $level ?? 2);
                }
            }
        }

        return [
            'html' => $html_output,
            'elementor_data' => $elementor_elements
        ];
    }

    /**
     * Renderiza blocos HTML compatíveis com o Gutenberg.
     */
    private static function render_gutenberg_block($type, $content, $level)
    {
        switch ($type) {
            case 'table':
                return "\n$content\n\n";
            case 'heading':
                return "\n<h$level>$content</h$level>\n\n";
            default:
                return "\n<p>$content</p>\n\n";
        }
    }

    /**
     * Monta a estrutura de dados (JSON) para widgets do Elementor.
     */
    private static function build_elementor_widget_data($type, $content, $level)
    {
        $id = substr(md5(uniqid()), 0, 7);

        switch ($type) {
            case 'table':
                return [
                    'id' => $id,
                    'elType' => 'widget',
                    'widgetType' => 'html',
                    'settings' => ['html' => $content]
                ];
            case 'heading':
                return [
                    'id' => $id,
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => ['title' => $content, 'header_size' => "h$level"]
                ];
            default:
                return [
                    'id' => $id,
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => ['editor' => "<p>$content</p>"]
                ];
        }
    }

    /**
     * Salva os metadados específicos do Elementor para o post.
     */
    private static function save_elementor_meta($post_id, $elements)
    {
        $elementor_data = [
            [
                'id' => substr(md5(uniqid()), 0, 7),
                'elType' => 'section',
                'elements' => [
                    [
                        'id' => substr(md5(uniqid()), 0, 7),
                        'elType' => 'column',
                        'elements' => $elements,
                        'settings' => ['_column_size' => 100]
                    ]
                ]
            ]
        ];

        update_post_meta($post_id, '_elementor_data', wp_slash(json_encode($elementor_data)));
        update_post_meta($post_id, '_elementor_edit_mode', 'builder');
        update_post_meta($post_id, '_elementor_template_type', 'wp-post');
    }

    /**
     * Centraliza a integração com plugins de SEO.
     */
    private static function save_seo_metadata($post_id, $data)
    {
        if (!function_exists('is_plugin_active')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        // Yoast SEO
        if (is_plugin_active('wordpress-seo/wp-seo.php')) {
            update_post_meta($post_id, '_yoast_wpseo_title', $data['seo_title']);
            update_post_meta($post_id, '_yoast_wpseo_metadesc', $data['meta_description']);
            update_post_meta($post_id, '_yoast_wpseo_focuskw', $data['focus_keyword']);
        }

        // RankMath
        elseif (is_plugin_active('seo-by-rank-math/rank-math.php')) {
            update_post_meta($post_id, 'rank_math_title', $data['seo_title']);
            update_post_meta($post_id, 'rank_math_description', $data['meta_description']);

            // Combina a palavra de foco com as secundárias em uma única string separada por vírgulas
            $all_keywords = $data['focus_keyword'];
            if (!empty($data['secondary_keywords'])) {
                $secondary = is_array($data['secondary_keywords']) ? implode(', ', $data['secondary_keywords']) : $data['secondary_keywords'];
                $all_keywords .= ', ' . $secondary;
            }

            update_post_meta($post_id, 'rank_math_focus_keyword', $all_keywords);
        }

        // All in One SEO
        elseif (is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php')) {
            update_post_meta($post_id, '_aioseo_title', $data['seo_title']);
            update_post_meta($post_id, '_aioseo_description', $data['meta_description']);
            update_post_meta($post_id, '_aioseo_keywords', $data['focus_keyword']);
        }
    }
}