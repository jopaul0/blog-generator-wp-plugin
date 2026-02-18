<?php

add_action('admin_menu', function() {
    // Menu Principal
    add_menu_page(
        __('Blog Generator', 'blog-generator'),
        __('Blog Generator', 'blog-generator'),
        'manage_options',
        'blog-generator',
        'render_blog_generator',
        'dashicons-welcome-write-blog',
        6
    );

    // Submenu: Gerador
    add_submenu_page(
        'blog-generator',
        __('Generate New Post', 'blog-generator'),
        __('Generator', 'blog-generator'),
        'manage_options',
        'blog-generator',
        'render_blog_generator'
    );

    // Submenu: Configurações
    add_submenu_page(
        'blog-generator',
        __('Blog Generator Settings', 'blog-generator'),
        __('Settings', 'blog-generator'),
        'manage_options',
        'blog-generator-settings',
        'render_settings'
    );
});