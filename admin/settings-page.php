<?php
if (!defined('ABSPATH')) exit;

/**
 * Renderiza o formulário de configurações.
 */
function render_settings()
{
    ?>
    <div class="wrap">
        <h1><?php _e('Blog Generator Settings', 'blog-generator'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('blog_generator_settings_group'); //
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Gemini API Token', 'blog-generator'); ?></th>
                    <td>
                        <input type="password" name="gemini_api_token"
                               value="<?php echo esc_attr(get_option('gemini_api_token')); ?>" class="regular-text"/>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php _e('AI Persona', 'blog-generator'); ?></th>
                    <td>
                        <textarea name="ai_persona" rows="3"
                                  class="large-text"><?php echo esc_textarea(get_option('ai_persona', 'Act as a senior copywriter specialized in the niche of the website {site_name}.')); ?></textarea>
                        <p class="description"><?php _e('Define how the AI should behave.', 'blog-generator'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php _e('Tone of Voice', 'blog-generator'); ?></th>
                    <td>
                        <input type="text" name="ai_tone"
                               value="<?php echo esc_attr(get_option('ai_tone', 'Professional and educational.')); ?>"
                               class="regular-text"/>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php _e('Generation Language', 'blog-generator'); ?></th>
                    <td>
                        <?php $current_lang = get_option('ai_language', 'Portuguese'); ?>
                        <select name="ai_language">
                            <option value="Portuguese" <?php selected($current_lang, 'Portuguese'); ?>>Português</option>
                            <option value="English" <?php selected($current_lang, 'English'); ?>>English</option>
                            <option value="Spanish" <?php selected($current_lang, 'Spanish'); ?>>Español</option>
                            <option value="French" <?php selected($current_lang, 'French'); ?>>Français</option>
                        </select>
                        <p class="description"><?php _e('The AI will generate the final content in this language.', 'blog-generator'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php _e('SEO Detection', 'blog-generator'); ?></th>
                    <td>
                        <?php
                        if (is_plugin_active('wordpress-seo/wp-seo.php')) {
                            echo '<span style="color: green;">' . __('✔ Yoast SEO detected and active.', 'blog-generator') . '</span>';
                        } elseif (is_plugin_active('seo-by-rank-math/rank-math.php')) {
                            echo '<span style="color: green;">' . __('✔ RankMath detected and active.', 'blog-generator') . '</span>';
                        } elseif (is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php')) {
                            echo '<span style="color: green;">' . __('✔ All in One SEO detected and active.', 'blog-generator') . '</span>';
                        } else {
                            echo '<span style="color: orange;">' . __('⚠ No supported SEO plugin detected. Using WP default.', 'blog-generator') . '</span>';
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <?php submit_button(); // ?>
        </form>
    </div>
    <?php
}

/**
 * Registra as configurações no sistema do WordPress.
 */
add_action('admin_init', function () {
    register_setting('blog_generator_settings_group', 'gemini_api_token');
    register_setting('blog_generator_settings_group', 'ai_persona');
    register_setting('blog_generator_settings_group', 'ai_tone');
    register_setting('blog_generator_settings_group', 'ai_language');
});