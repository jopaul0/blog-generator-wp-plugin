<?php

class ViewController
{
    public static function handle_submission()
    {
        // Verificação de Segurança (Nonce)
        if (!isset($_POST['bg_security']) || !wp_verify_nonce($_POST['bg_security'], 'bg_generate_action')) {
            wp_die('Erro de segurança no formulário.');
        }

        // Verificação de Permissão
        if (!current_user_can('manage_options')) {
            wp_die('Você não tem permissão para acessar esta página.');
        }

        // Sanitização e Processamento
        $theme = sanitize_text_field($_POST['theme']);
        $min = intval($_POST['min_words']);
        $max = intval($_POST['max_words']);

        // Chamada ao Core
        $prompt = Gemini_API::build_prompt($theme, $min, $max);
        $response = Gemini_API::send_to_api($prompt);

        // Cria o post
        $post_id = Builder::create_draft_post($response);

        // Redirecionamento Final (Crucial para não dar erro no admin-post.php)
        if ($post_id) {
            wp_redirect(admin_url('admin.php?page=blog-generator&bg_post_id=' . $post_id));
        } else {
            wp_redirect(admin_url('admin.php?page=blog-generator&bg_message=error'));
        }
        exit; // Sempre use exit após wp_redirect
    }
}