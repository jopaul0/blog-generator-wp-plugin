<?php

if (!defined('ABSPATH')) exit;

abstract class AI
{
    /**
     * Prepara o prompt substituindo as variáveis no template JSON.
     */
    public static function build_prompt($theme_user, $min, $max)
    {
        $site_name = get_bloginfo('name');
        $persona = get_option('ai_persona', 'Act as a senior copywriter specialized in the niche of the website {site_name}.');
        $tone = get_option('ai_tone', 'Professional and educational.');
        $language = get_option('ai_language', 'Portuguese');

        // Regras fixas
        $seo_standards = [
            "rules" => [
                "focus_keyword_placement" => "The focus keyword must appear at the beginning of the SEO title, in the first 10% of the content, in the meta description, and in the URL. It should have a good density (appearing more than once, but not overused).",
                "char_limits" => [
                    "seo_title" => 60,
                    "meta_description" => 160,
                    "url_slug" => 75
                ],
                "html_format" => "Content must be returned in HTML without the <body> tag, using only formatting tags like <p>, <h2>, <h3>, <ul>, and <table>.",
                "table_format" => "Tables must be returned in HTML with inline CSS for basic styling."
            ]
        ];

        // Saída fixa
        $output_schema = [
            "h1_title" => "The H1 title of the post",
            "article_blocks" => "An array of strings, where each item is a paragraph or a subheading (H2, H3)",
            "summary" => "A 2-sentence summary",
            "seo_title" => "Optimized SEO title",
            "meta_description" => "Meta description",
            "url_slug" => "User-friendly URL slug",
            "focus_keyword" => "Main focus keyword",
            "secondary_keywords" => "List of secondary keywords",
            "tags" => "Tags separated by commas"
        ];

        $prompt_structure = [
            "persona" => str_replace('{site_name}', $site_name, $persona),
            "directives" => [
                "tone" => $tone,
                "seo_optimization" => $seo_standards,
                "instructions" => "Create a complete article about the topic: $theme_user.",
                "language_rule" => "IMPORTANT: Generate all content within the JSON fields (title, blocks, summary, tags, etc.) in $language." // Força o idioma de saída
            ],
            "constraints" => [
                "min_words" => intval($min),
                "max_words" => intval($max)
            ],
            "output_schema" => $output_schema
        ];

        $final_json = json_encode($prompt_structure, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $instruction = "\n\nRespond strictly with the JSON object above. All field values must be written in $language. Do not add any text before or after the JSON.";

        return $final_json . $instruction;
    }

    /**
     * Processa o JSON recebido e cria o rascunho no WordPress.
     */
    public static function create_draft_post($data)
    {
        if (!$data || isset($data['error'])) {
            return false;
        }

        $blocks_html = '';

        if (isset($data['article_blocks']) && is_array($data['article_blocks'])) {
            foreach ($data['article_blocks'] as $block_text) {
                $block_text = trim($block_text);

                if (preg_match('/^<h([1-6])>(.*?)<\/h\1>/i', $block_text, $matches)) {
                    $level = $matches[1];
                    $content = $matches[2];
                    $blocks_html .= "<h$level>$content</h$level>";
                } else {
                    $content = preg_replace('/^<p>(.*?)<\/p>$/i', '$1', $block_text);
                    $blocks_html .= "<p>$content</p>";
                }
            }
        } else {
            $content = isset($data['article_content']) ? $data['article_content'] : '';
            $blocks_html = "<p>$content</p>";
        }

        $post_args = [
            'post_title' => sanitize_text_field($data['h1_title']),
            'post_content' => $blocks_html,
            'post_status' => 'draft',
            'post_excerpt' => sanitize_textarea_field($data['summary']),
            'post_type' => 'post',
            'post_name' => sanitize_title($data['url_slug']),
        ];

        $post_id = wp_insert_post($post_args);

        if (is_wp_error($post_id)) {
            return false;
        }

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

        return $post_id;
    }

    abstract public static function send_to_api($final_prompt);
}