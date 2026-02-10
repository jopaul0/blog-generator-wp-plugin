<?php

namespace core;

if (!defined('ABSPATH')) exit;

class Gemini_API {

    /**
     * Prepara o prompt substituindo as variáveis no template JSON.
     */
    public static function build_prompt($theme_user, $min, $max) {
        $site_name = get_bloginfo('name');
        $persona   = get_option('ai_persona', 'Aja como um redator sênior especializado no nicho do site {site_name}.');
        $tone      = get_option('ai_tone', 'Profissional e didático.');

        $seo_standards = [
            "rules" => [
                "focus_keyword_placement" => "A palavra-chave de foco deve aparecer no início do título SEO e nos primeiros 10% do conteúdo.",
                "char_limits" => [
                    "seo_title" => 60,
                    "meta_description" => 160,
                    "url_slug" => 75
                ],
                "html_format" => "O conteúdo deve ser retornado em HTML sem a tag <body>, usando apenas tags de formatação como <p>, <h2>, <h3>, <ul> e <table>."
            ]
        ];

        // Monta o array que será convertido em JSON
        $prompt_structure = [
            "persona" => str_replace('{site_name}', $site_name, $persona),
            "directives" => [
                "tone" => $tone,
                "seo_optimization" => $seo_standards,
                "instructions" => "Crie um artigo completo sobre o tema: $theme_user."
            ],
            "constraints" => [
                "min_words" => intval($min),
                "max_words" => intval($max)
            ],
            "output_schema" => [
                "h1_title"         => "O título H1 do post",
                "article_content"  => "O texto do artigo em HTML",
                "summary"          => "Um resumo de 2 frases",
                "seo_title"        => "Título SEO (máx 60 caracteres)",
                "meta_description" => "Meta descrição (máx 160 caracteres)",
                "url_slug"         => "Slug amigável baseado no título",
                "focus_keyword"    => "A palavra-chave principal",
                "tags"             => "Lista de 5 tags separadas por vírgula"
            ]
        ];

        $final_json = json_encode($prompt_structure, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $instruction = "\n\nResponda estritamente com o objeto JSON acima. Não adicione texto antes ou depois do JSON.";

        return $final_json . $instruction;
    }

    /**
     * Envia a requisição para a API do Gemini.
     */
    public static function send_to_gemini($final_prompt) {
        $api_key = get_option('gemini_api_token');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $api_key;

        $payload = [
            "contents" => [
                ["parts" => [["text" => $final_prompt]]]
            ],
            "generationConfig" => [
                "response_mime_type" => "application/json",
                "temperature" => 0.7
            ]
        ];

        $response = wp_remote_post($url, [
            'method'      => 'POST',
            'body'        => json_encode($payload),
            'headers'     => ['Content-Type' => 'application/json'],
            'timeout'     => 120,
        ]);

        if (is_wp_error($response)) {
            return ['error' => $response->get_error_message()];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        $final_text = isset($body['candidates'][0]['content']['parts'][0]['text']) ? $body['candidates'][0]['content']['parts'][0]['text'] : null;

        if (!$final_text) {
            return ['error' => 'Empty response from API.'];
        }

        return json_decode($final_text, true);
    }

    /**
     * Processa o JSON recebido (com chaves em inglês) e cria o rascunho no WordPress.
     */
    public static function create_draft_post($data) {
        if (!$data || isset($data['error'])) {
            return false;
        }

        // Mapeamento usando as novas chaves em inglês vindas da IA
        $post_args = [
            'post_title'   => sanitize_text_field($data['h1_title']),
            'post_content' => $data['article_content'],
            'post_status'  => 'draft',
            'post_excerpt' => sanitize_textarea_field($data['summary']),
            'post_type'    => 'post',
            'post_name'    => sanitize_title($data['url_slug']),
        ];

        $post_id = wp_insert_post($post_args);

        if (is_wp_error($post_id)) {
            return false;
        }

        // Configuração de Tags
        if (!empty($data['tags'])) {
            wp_set_post_tags($post_id, $data['tags']);
        }

        // Metadados de SEO (Compatibilidade Yoast SEO)
        update_post_meta($post_id, '_yoast_wpseo_title', $data['seo_title']);
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $data['meta_description']);
        update_post_meta($post_id, '_yoast_wpseo_focuskw', $data['focus_keyword']);

        // Palavras-chave secundárias
        update_post_meta($post_id, '_gemini_secondary_keywords', $data['secondary_keywords']);

        return $post_id;
    }
}