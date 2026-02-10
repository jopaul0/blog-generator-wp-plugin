<?php
if (!defined('ABSPATH')) exit;

/**
 * Renderiza o formulário de configurações.
 */
function render_settings() {
    ?>
    <div class="wrap">
        <h1>Configurações do Blog Generator</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('blog_generator_settings_group');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Gemini API Token</th>
                    <td>
                        <input type="password" name="gemini_api_token" value="<?php echo esc_attr(get_option('gemini_api_token')); ?>" class="regular-text" />
                    </td>
                </tr>

                <tr>
                    <th scope="row">Persona da IA</th>
                    <td>
                        <textarea name="ai_persona" rows="3" class="large-text"><?php echo esc_textarea(get_option('ai_persona', 'Aja como um redator sênior especializado no nicho do site {site_name}.')); ?></textarea>
                        <p class="description">Defina como a IA deve se comportar.</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Tom de Voz</th>
                    <td>
                        <input type="text" name="ai_tone" value="<?php echo esc_attr(get_option('ai_tone', 'Profissional e didático')); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Registra as configurações no sistema do WordPress.
 * Isso permite que o WP salve os dados automaticamente ao clicar em "Save Changes".
 */
add_action('admin_init', function() {
    register_setting('blog_generator_settings_group', 'gemini_api_token');
    register_setting('blog_generator_settings_group', 'ai_persona');
    register_setting('blog_generator_settings_group', 'ai_tone');
});