<?php
/*
Plugin Name: Blog Generator
Description: Automação de posts com IA.
Version: 1.0
Author: João Paulo Santos
*/

if (!defined('ABSPATH')) exit;

define('GEMINI_PLUGIN_PATH', plugin_dir_path(__FILE__));

require_once GEMINI_PLUGIN_PATH . 'admin/settings-page.php';
require_once GEMINI_PLUGIN_PATH . 'admin/generator-page.php';
require_once GEMINI_PLUGIN_PATH . 'core/class-api-gemini.php';


// Função de ativação
function blog_generator_initialize()
{
    // Template padrão em formato JSON
    $default_template = '{
  "persona": "Aja como um redator sênior especializado no nicho do site {site_name}.",
  "directives": {
    "tone": "Profissional e didático, explicando termos técnicos de forma simples.",
    "seo_rules": {
      "focus_keyword_placement": "A palavra-chave de foco deve aparecer no início do título SEO e nos primeiros 10% do conteúdo.",
      "char_limits": { "seo_title": 60, "meta_description": 160, "url_slug": 75 },
      "elements": "Incluir marcações de onde as imagens devem ser inseridas e criar pelo menos uma tabela informativa."
    },
    "table_format": "As tabelas devem ser retornadas em HTML com CSS inline."
  },
  "output_schema": {
    "h1_title": "Título do artigo",
    "summary": "Breve resumo",
    "seo_title": "Título otimizado",
    "meta_description": "Descrição para buscadores",
    "url_slug": "Slug da URL",
    "focus_keyword": "Palavra principal",
    "secondary_keywords": "Lista com 4 palavras",
    "tags": "Tags separadas por vírgula",
    "article_content": "Texto completo em HTML sobre {theme} com min {min} e max {max} palavras."
  }
}';

    // Adiciona ao banco se ainda não existir
    add_option('gemini_prompt_template', $default_template);
    add_option('gemini_api_token', '');
}

// Registra o hook de ativação
register_activation_hook(__FILE__, 'blog_generator_initialize');