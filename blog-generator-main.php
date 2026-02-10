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


// Função de ativação
function blog_generator_initialize()
{
    add_option('gemini_api_token', '');
    add_option('ai_persona', 'Aja como um redator sênior especializado no nicho do site {site_name}.');
    add_option('ai_tone', 'Profissional e didático');
}

// Registra o hook de ativação
register_activation_hook(__FILE__, 'blog_generator_initialize');