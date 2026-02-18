<?php
if (!defined('ABSPATH')) exit;

/**
 * Renderiza o formulário gerador de artigos.
 */
function render_blog_generator()
{
    // Recupera mensagens de erro ou sucesso processadas pelo Controller
    $message = isset($_GET['bg_message']) ? $_GET['bg_message'] : null;
    $post_id = isset($_GET['bg_post_id']) ? intval($_GET['bg_post_id']) : null;
    ?>
    <div class="wrap">
        <h1><?php _e('Generate New Blog Post', 'blog-generator'); ?></h1>

        <?php if ($message === 'processing'): ?>
            <div class="notice notice-info">
                <p><strong><?php _e('Processing with AI... Please wait.', 'blog-generator'); ?></strong></p>
            </div>
        <?php endif; ?>

        <?php if ($post_id): ?>
            <div class="updated">
                <p>
                    <strong><?php _e('Success!', 'blog-generator'); ?></strong>
                    <?php _e('Draft created.', 'blog-generator'); ?>
                    <a href="<?php echo esc_url(get_edit_post_link($post_id)); ?>" target="_blank">
                        <?php _e('Click here to edit the post.', 'blog-generator'); ?>
                    </a>
                </p>
            </div>
        <?php endif; ?>

        <?php if ($message === 'error'): ?>
            <div class="error">
                <p><?php _e('An error occurred during generation. Please check your settings.', 'blog-generator'); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="bg_generate_article">

            <?php wp_nonce_field('bg_generate_action', 'bg_security'); ?>

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
                    <td><input name="min_words" type="number" id="min_words" value="600" class="small-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="max_words"><?php _e('Max Words', 'blog-generator'); ?></label></th>
                    <td><input name="max_words" type="number" id="max_words" value="1200" class="small-text"></td>
                </tr>
            </table>
            <?php submit_button(__('Generate Article', 'blog-generator'), 'primary', 'generate_action'); ?>
        </form>
    </div>
    <?php
}