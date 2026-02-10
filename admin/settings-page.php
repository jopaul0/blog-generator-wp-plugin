<?php
if (!defined('ABSPATH')) exit;

/**
 * Renderiza o formulário de configurações.
 */
function render_settings() {
    ?>
    <div class="wrap">
        <h1>Gemini API Settings</h1>
        <form method="post" action="options.php">
            <?php
            // Campos de segurança e identificação do grupo de opções
            settings_fields('gemini_settings_group');
            do_settings_sections('gemini_settings_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Gemini API Token</th>
                    <td>
                        <input type="password" name="gemini_api_token"
                               value="<?php echo esc_attr(get_option('gemini_api_token')); ?>"
                               class="regular-text" />
                        <p class="description">Insira sua chave da API do Google Gemini.</p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Prompt Template (JSON)</th>
                    <td>
                        <textarea name="gemini_prompt_template" rows="15" cols="50"
                                  class="large-text" style="font-family: monospace; font-size: 12px;"><?php echo esc_textarea(get_option('gemini_prompt_template')); ?></textarea>
                        <p class="description">
                            Edite o esquema JSON abaixo. Use os marcadores:
                            <code>{site_name}</code>, <code>{theme}</code>, <code>{min}</code> e <code>{max}</code>.
                        </p>
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
    register_setting('gemini_settings_group', 'gemini_api_token');
    register_setting('gemini_settings_group', 'gemini_prompt_template');
});