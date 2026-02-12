<?php
if (!defined('ABSPATH')) exit;

/**
 * Renderiza o formulário gerador de artigos.
 */
function render_blog_generator()
{
    ?>
    <div class="wrap">
        <h1><?php _e('Generate New Blog Post', 'blog-generator'); ?></h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="theme"><?php _e('Detailed Theme', 'blog-generator'); ?></label></th>
                    <td>
                        <textarea name="theme" id="theme" rows="5" class="large-text" required
                                  placeholder="<?php esc_attr_e('Describe the topic for the AI...', 'blog-generator'); ?>"></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="min_words"><?php _e('Min Words', 'blog-generator'); ?></label></th>
                    <td><input name="min_words" type="number" id="min_words" value="500" class="small-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="max_words"><?php _e('Max Words', 'blog-generator'); ?></label></th>
                    <td><input name="max_words" type="number" id="max_words" value="1000" class="small-text"></td>
                </tr>
            </table>
            <?php submit_button(__('Generate Article', 'blog-generator'), 'primary', 'generate_action'); ?>
        </form>

        <?php
        // Lógica de Processamento
        if (isset($_POST['generate_action'])) {
            $theme = sanitize_text_field($_POST['theme']);
            $min = intval($_POST['min_words']);
            $max = intval($_POST['max_words']);

            echo "<h3>" . __('Processing with AI... Please wait.', 'blog-generator') . "</h3>";

            // Constrói o prompt usando as variáveis do usuário
            $prompt = Gemini_API::build_prompt($theme, $min, $max);

            // Envia para a API e recebe o JSON
            $response_data = Gemini_API::send_to_api($prompt);

            if ($response_data && !isset($response_data['error'])) {
                // Cria o rascunho usando o método que criamos na classe
                $post_id = Builder::create_draft_post($response_data);

                if ($post_id) {
                    $edit_link = get_edit_post_link($post_id);
                    ?>
                    <div class='updated'>
                        <p>
                            <strong><?php _e('Success!', 'blog-generator'); ?></strong>
                            <?php _e('Draft created.', 'blog-generator'); ?>
                            <a href='<?php echo esc_url($edit_link); ?>' target='_blank'>
                                <?php _e('Click here to edit the post.', 'blog-generator'); ?>
                            </a>
                        </p>
                    </div>
                    <?php
                } else {
                    echo "<div class='error'><p>" . __('Failed to create the WordPress post.', 'blog-generator') . "</p></div>";
                }
            } else {
                $error_msg = isset($response_data['error']) ? $response_data['error'] : __('Unknown API error.', 'blog-generator');
                echo "<div class='error'><p>" . sprintf(__('Error: %s', 'blog-generator'), esc_html($error_msg)) . "</p></div>";
            }
        }
        ?>
    </div>
    <?php
}