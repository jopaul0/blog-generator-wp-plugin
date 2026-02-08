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