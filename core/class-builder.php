<?php

class Builder
{

    /**
     * Processa o JSON recebido e cria o rascunho no WordPress.
     */
    public static function create_draft_post($data)
    {
        if (!$data || isset($data['error'])) return false;

        $blocks_html = ''; // Para o Gutenberg
        $elementor_elements = []; // Para o Elementor


        if (isset($data['article_blocks']) && is_array($data['article_blocks'])) {
            foreach ($data['article_blocks'] as $block_text) {
                $block_text = trim($block_text);
                if (empty($block_text)) continue;

                // 1. VERIFICA SE É O LUGAR DA TABELA
                if (strpos($block_text, '[TABLE_HERE]') !== false && !empty($data['table_html'])) {
                    $table_code = $data['table_html'];

                    // Gutenberg
                    $blocks_html .= "\n$table_code\n\n\n";

                    // Elementor (Widget HTML)
                    $elementor_elements[] = self::build_elementor_widget('html', [
                        'html' => $table_code
                    ]);
                    continue;
                }

                // 2. LÓGICA PARA CABEÇALHOS (H1-H6)
                if (preg_match('/^<h([1-6])>(.*?)<\/h\1>/i', $block_text, $matches)) {
                    $level = $matches[1];
                    $content = $matches[2];

                    $blocks_html .= "\n<h$level>$content</h$level>\n\n\n";

                    $elementor_elements[] = self::build_elementor_widget('heading', [
                        'title' => $content,
                        'header_size' => "h$level"
                    ]);
                } // 3. LÓGICA PARA PARÁGRAFOS
                else {
                    $content = preg_replace('/^<p>(.*?)<\/p>$/i', '$1', $block_text);

                    $blocks_html .= "\n<p>$content</p>\n\n\n";

                    $elementor_elements[] = self::build_elementor_widget('text-editor', [
                        'editor' => "<p>$content</p>"
                    ]);
                }
            }
        }


        $post_args = [
            'post_title' => sanitize_text_field($data['h1_title']),
            'post_content' => $blocks_html,
            'post_status' => 'draft',
            'post_type' => 'post',
            'post_name' => sanitize_title($data['url_slug']),
        ];

        $post_id = wp_insert_post($post_args);

        if ($post_id && !is_wp_error($post_id)) {
            // --- SEÇÃO ELEMENTOR ---
            // Se quisermos que ele já abra no Elementor direto:
            $elementor_data = [
                [
                    'id' => substr(md5(uniqid()), 0, 7),
                    'elType' => 'section',
                    'elements' => [
                        [
                            'id' => substr(md5(uniqid()), 0, 7),
                            'elType' => 'column',
                            'elements' => $elementor_elements,
                            'settings' => ['_column_size' => 100]
                        ]
                    ]
                ]
            ];


            // Salva os dados do Elementor
            update_post_meta($post_id, '_elementor_data', wp_slash(json_encode($elementor_data)));
            update_post_meta($post_id, '_elementor_edit_mode', 'builder');
            update_post_meta($post_id, '_elementor_template_type', 'wp-post');
            // -----------------------

            // SEO
            if (!function_exists('is_plugin_active')) {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            if (is_plugin_active('wordpress-seo/wp-seo.php')) {
                // Compatibilidade Yoast SEO
                update_post_meta($post_id, '_yoast_wpseo_title', $data['seo_title']);
                update_post_meta($post_id, '_yoast_wpseo_metadesc', $data['meta_description']);
                update_post_meta($post_id, '_yoast_wpseo_focuskw', $data['focus_keyword']);
            } elseif (is_plugin_active('seo-by-rank-math/rank-math.php')) {
                // Compatibilidade RankMath
                update_post_meta($post_id, 'rank_math_title', $data['seo_title']);
                update_post_meta($post_id, 'rank_math_description', $data['meta_description']);
                update_post_meta($post_id, 'rank_math_focus_keyword', $data['focus_keyword']);
            } elseif (is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php')) {
                // All in One SEO (AIOSEO)
                update_post_meta($post_id, '_aioseo_title', $data['seo_title']);
                update_post_meta($post_id, '_aioseo_description', $data['meta_description']);
                update_post_meta($post_id, '_aioseo_keywords', $data['focus_keyword']);
            }

            if (!empty($data['tags'])) {
                wp_set_post_tags($post_id, $data['tags']);
            }
        }

        //
        return $post_id;
    }

    /**
     * Função Auxiliar para montar o JSON do Widget
     */
    public static function build_elementor_widget($type, $settings)
    {
        return [
            'id' => substr(md5(uniqid()), 0, 7),
            'elType' => 'widget',
            'widgetType' => $type,
            'settings' => $settings
        ];
    }
}