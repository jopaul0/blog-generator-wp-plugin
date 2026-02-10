<?php

if (!defined('ABSPATH')) exit;

require_once BLOG_PLUGIN_PATH . 'admin/generator-page.php';
require_once BLOG_PLUGIN_PATH . 'admin/settings-page.php';

add_action('admin_menu', function() {
    add_menu_page(
        'Blog Generator',
        'Blog Generator',
        'manage_options',
        'blog-generator',
        'render_blog_generator',
        'dashicons-welcome-write-blog',
        6
    );

    add_submenu_page(
        'blog-generator',
        'Generate New Post',
        'Gerador',
        'manage_options',
        'blog-generator',
        'render_blog_generator'
    );

    add_submenu_page(
        'blog-generator',
        'Blog Generator Settings',
        'Configurações',
        'manage_options',
        'blog-generator-settings',
        'render_settings'
    );
});