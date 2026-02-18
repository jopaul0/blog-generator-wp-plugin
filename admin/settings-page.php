<?php
if (!defined('ABSPATH')) exit;

/**
 * Renderiza o formulário de configurações (View).
 */
function render_settings()
{
    ?>
    <div class="wrap">
        <h1><?php _e('Blog Generator Settings', 'blog-generator'); ?></h1>

        <form method="post" action="options.php">
            <?php
            // Gera campos ocultos de segurança e identificação do grupo de opções
            settings_fields('blog_generator_settings_group');

            // Renderiza as secções registradas para esta página
            do_settings_sections('blog-generator-settings');
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
                    <th scope="row"><?php _e('Gemini Model', 'blog-generator'); ?></th>
                    <td>
                        <?php $current_model = get_option('gemini_model', 'gemini-2.5-flash'); ?>
                        <select name="gemini_model">
                            <option value="gemini-2.5-flash" <?php selected($current_model, 'gemini-2.5-flash'); ?>>Gemini 2.5 Flash (Recommended)</option>
                            <option value="gemini-2.5-pro" <?php selected($current_model, 'gemini-2.5-pro'); ?>>Gemini 2.5 Pro (Smarter)</option>
                            <option value="gemini-3-flash-preview" <?php selected($current_model, 'gemini-3-flash-preview'); ?>>Gemini 3 Flash (Preview)</option>
                            <option value="gemini-3-pro-preview" <?php selected($current_model, 'gemini-3-pro-preview'); ?>>Gemini 3 Pro (Preview)</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php _e('AI Persona', 'blog-generator'); ?></th>
                    <td>
                        <textarea name="ai_persona" rows="3"
                                  class="large-text"><?php echo esc_textarea(get_option('ai_persona')); ?></textarea>
                        <p class="description"><?php _e('Define how the AI should behave.', 'blog-generator'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php _e('Tone of Voice', 'blog-generator'); ?></th>
                    <td>
                        <input type="text" name="ai_tone"
                               value="<?php echo esc_attr(get_option('ai_tone')); ?>"
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
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}