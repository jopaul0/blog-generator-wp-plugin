<?php

class ViewController
{
    /**
     * Subimissão de geração de artigo
     */
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
        exit;
    }

    /**
     * Registra as configurações que o WP deve aceitar e salvar.
     */
    public static function register_plugin_settings()
    {
        register_setting('blog_generator_settings_group', 'gemini_api_token');
        register_setting('blog_generator_settings_group', 'gemini_model');
        register_setting('blog_generator_settings_group', 'ai_persona');
        register_setting('blog_generator_settings_group', 'ai_tone');
        register_setting('blog_generator_settings_group', 'ai_language');
    }

    /**
     * Detecta qual plugin de SEO está ativo no momento.
     */
    public static function detect_active_seo()
    {
        $seo_slug = Builder::detect_active_seo();

        $seo_display_names = [
            'yoast' => 'Yoast SEO',
            'rankmath' => 'RankMath',
            'aioseo' => 'All in One SEO',
            'none' => __('None detected (WP Default)', 'blog-generator')
        ];

        $active_seo = $seo_display_names[$seo_slug];

        return $active_seo;
    }

    /**
     * Detecta se o Elementor está instalado e ativo.
     * Retorna 'elementor' se ativo, caso contrário 'gutenberg'.
     */
    public static function detect_preferred_editor()
    {
        return ucfirst(Builder::detect_preferred_editor());
    }

}