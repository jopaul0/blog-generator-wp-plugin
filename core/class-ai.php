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
                    "seo_title" => 58,
                    "meta_description" => 156,
                    "url_slug" => 70
                ],
                "html_format" => "Content must be returned in HTML without the <body> tag, using only formatting tags like <p>, <h2>, <h3>, <ul>, and <table>.",
                "table_format" => "Tables must be returned in HTML with inline CSS for basic styling."
            ]
        ];

        // Saída fixa
        $output_schema = [
            "h1_title" => "The H1 title of the post",
            "article_blocks" => "An array of strings (paragraphs/subheadings). If there is a table, insert the placeholder [TABLE_HERE] as a single item in this array at the correct position.",
            "table_html" => "The complete HTML <table> code. If no table is needed, leave it as an empty string.",
            "summary" => "A 2-sentence summary",
            "seo_title" => "Optimized SEO title and must have a number",
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

    abstract public static function send_to_api($final_prompt);
}