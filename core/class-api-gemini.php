<?php
if (!defined('ABSPATH')) exit;

class Gemini_API {

    /**
     * Prepara o prompt substituindo as variáveis no template JSON.
     */
    public static function build_prompt($theme_user, $min, $max) {
        $template = get_option('gemini_prompt_template');
        $site_name = get_bloginfo('name');

        $find = [
            '{site_name}',
            '{theme}',
            '{min}',
            '{max}'
        ];

        $to_replace = [
            $site_name,
            $theme_user,
            $min,
            $max
        ];

        $final_prompt = str_replace($find, $to_replace, $template);

        $instruction = "\n\nResponda estritamente com um objeto JSON seguindo o 'output_schema' fornecido. Não adicione saudações ou explicações.";

        return $final_prompt . $instruction;
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