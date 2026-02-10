<?php
if (!defined('ABSPATH')) exit;

function render_blog_generator() {
    ?>
    <div class="wrap">
        <h1>Generate New Blog Post</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="theme">Detailed Theme</label></th>
                    <td><textarea name="theme" id="theme" rows="5" class="large-text" required placeholder="Describe the topic for the AI..."></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="min_words">Min Words</label></th>
                    <td><input name="min_words" type="number" id="min_words" value="500" class="small-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="max_words">Max Words</label></th>
                    <td><input name="max_words" type="number" id="max_words" value="1000" class="small-text"></td>
                </tr>
            </table>
            <?php submit_button('Generate Article', 'primary', 'generate_action'); ?>
        </form>

        <?php
        // Lógica de Processamento
        if (isset($_POST['generate_action'])) {
            $theme = sanitize_text_field($_POST['theme']);
            $min   = intval($_POST['min_words']);
            $max   = intval($_POST['max_words']);

            echo "<h3>Processing with Gemini... Please wait.</h3>";

            // Constrói o prompt usando as variáveis do usuário
            $prompt = Gemini_API::build_prompt($theme, $min, $max);

            // Envia para a API e recebe o JSON
            $response_data = Gemini_API::send_to_gemini($prompt);

            if ($response_data && !isset($response_data['error'])) {
                // Cria o rascunho usando o método que criamos na classe
                $post_id = Gemini_API::create_draft_post($response_data);

                if ($post_id) {
                    $edit_link = get_edit_post_link($post_id);
                    echo "<div class='updated'><p><strong>Success!</strong> Draft created. <a href='$edit_link' target='_blank'>Click here to edit the post.</a></p></div>";
                } else {
                    echo "<div class='error'><p>Failed to create the WordPress post.</p></div>";
                }
            } else {
                $error_msg = isset($response_data['error']) ? $response_data['error'] : 'Unknown API error.';
                echo "<div class='error'><p>Error: $error_msg</p></div>";
            }
        }
        ?>
    </div>
    <?php
}