<?php
/*
Plugin Name: Blog Generator
Description: Automação de posts com IA.
Version: 1.0
Author: João Paulo Santos
*/

if (!defined('ABSPATH')) exit;

define('BLOG_PLUGIN_PATH', plugin_dir_path(__FILE__));

require_once BLOG_PLUGIN_PATH . 'admin/navigation.php';
require_once BLOG_PLUGIN_PATH . 'core/class-api-gemini.php';

// Função de ativar o idioma
function blog_generator_load_textdomain() {
    load_plugin_textdomain('blog-generator', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'blog_generator_load_textdomain');

// Função de ativação
function blog_generator_initialize()
{
    add_option('gemini_api_token', '');
    add_option('ai_persona', 'Act as a senior copywriter specialized in the niche of the website {site_name}.');
    add_option('ai_tone', 'Professional and educational.');
    add_option('ai_language', 'Portuguese');

}

// Registra o hook de ativação
register_activation_hook(__FILE__, 'blog_generator_initialize');