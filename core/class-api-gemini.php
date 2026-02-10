<?php

if (!defined('ABSPATH')) exit;

require_once BLOG_PLUGIN_PATH . 'core/class-ai.php';

class Gemini_API extends AI
{
    /**
     * Envia a requisição para a API do Gemini.
     */
    public static function send_to_api($final_prompt)
    {
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
            'method' => 'POST',
            'body' => json_encode($payload),
            'headers' => ['Content-Type' => 'application/json'],
            'timeout' => 120,
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
}