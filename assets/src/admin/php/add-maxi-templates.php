<?php
/**
 * Admin Install Plugin Notice
 *
 * @package MaxiBlocks Theme
 * @author MaxiBlocks Team
 * @since 1.0.0
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!defined('MBT_TEMPLATES_NOTICE_JS')) {
    define('MBT_TEMPLATE_NOTICE_JS', MBT_PREFIX . 'templates-notice');
}

if (!defined('MBT_TEMPLATE_NOTICE_DISMISS')) {
    define('MBT_TEMPLATE_NOTICE_DISMISS', MBT_PREFIX . 'dismiss-templates-notice');
}

add_action('admin_notices', 'mbt_render_templates_notice', 0);
add_action('wp_ajax_maxiblocks-theme-dismiss-plugin-notice', 'mbt_close_templates_notice');

/**
 * Renders the installation notice for the MaxiBlocks plugin.
 *
 * This function checks if the notice should be displayed based on certain conditions
 * and then enqueues the necessary JavaScript file (minified or unminified based on the debug mode).
 * It also outputs HTML markup for the notice, including dynamic text and URLs.
 *
 * @since 1.0.0
 * @return void
 */
function mbt_render_templates_notice()
{

    // Check if the notice should be displayed.
    if (!mbt_templates_notice_display()) {
        return;
    }

    // Get the plugin status.
    $plugin_status = mbt_is_maxiblocks_plugin_status();

    // Determine the JavaScript file URL based on the debug mode.
    $notice_js_url = defined('MBT_DEBUG') && MBT_DEBUG ?
                     MBT_URL_SRC_ADMIN . '/js/templates-notice.js' :
                     MBT_URL_BUILD_ADMIN . '/js/templates-notice.js';

    // Enqueue the script.
    wp_enqueue_script(MBT_TEMPLATE_NOTICE_JS, $notice_js_url, [], MBT_VERSION, true);
    wp_localize_script(MBT_TEMPLATE_NOTICE_JS, 'maxiblocks', mbt_localize_templates_notice_js($plugin_status));

    // Define other variables.
    $install_plugin_image  = MBT_URL_BUILD_ADMIN . '/images/maxiblocks-plugin-install-notice.jpg';
    $more_info_url = 'https://maxiblocks.com/go/maxi-theme-activation-more-info';

    // Start output buffering.
    ob_start();
    ?>
<div class="mbt-notice mbt-notice--info" style="background-image: url(<?php echo esc_url($install_plugin_image); ?>);">
    <button type="button" class="mbt-notice__dismiss">
        <span class="mbt-notice__dismiss-text">&#10799;</span>
    </button>
    <div class="mbt-notice__row">
        <div class="mbt-notice__col">
            <div class="mbt-notice__content">
                <p class="mbt-notice__subtitle">
                    <?php esc_html_e('Thanks for choosing the MaxiBlocks theme', 'maxiblocks');?>
                </p>
                <h2 class="mbt-notice__title">
                <?php esc_html_e('Please import MaxiBlocks templates and patterns', 'maxiblocks');?>
                </h2>
                <p class="mbt-notice__description">
                    <?php esc_html_e('Important: MaxiBlocks templates and template parts will replace current MaxiBlocks theme templates and template parts', 'maxiblocks'); ?>
                </p>
                <div class="mbt-notice__actions">
                    <button id="mbt-notice-import-templates-patterns" class="mbt-button mbt-button--primary mbt-button--hero" onclick="mbt_copy_patterns()">
                        <span class="mbt-button__text">
                            <?php esc_html_e('Import', 'maxiblocks')?>
                        </span><span class="mbt-button__icon">&rsaquo;</span></button>
                    <a href="<?php echo esc_url($more_info_url); ?>" target="_blank"
                        class="mbt-button mbt-button--primary mbt-button--hero">
                        <span class="mbt-button__text"><?php esc_html_e('More info', 'maxiblocks'); ?>
                        </span><span class="mbt-button__icon">&rsaquo;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
        // Output the buffered content.
        echo wp_kses_post(ob_get_clean());
}

/**
 * Close install notice.
 *
 * @since 1.0.0
 */
function mbt_close_templates_notice()
{
    if (!isset($_POST['nonce'])) {
        return;
    }

    if (isset($_POST['nonce']) && is_string($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['nonce']), MBT_TEMPLATE_NOTICE_DISMISS . '-nonce')) {
        return;
    }
    update_option(MBT_TEMPLATE_NOTICE_DISMISS, 'yes');
    wp_die();
}

/**
 * Determines if a plugin notice should be displayed.
 *
 * The function checks several conditions to determine if the notice should be shown:
 * - Verifies if a specific plugin is active.
 * - Checks if the notice has been dismissed by the user.
 * - Ensures the notice is only shown on specific admin pages (dashboard and themes).
 * - Excludes AJAX requests, network admin, users without specific capabilities, and block editor context.
 *
 * @since 1.0.0
 * @return bool True if the notice should be displayed, false otherwise.
 */
function mbt_templates_notice_display()
{
    $screen = get_current_screen();

    // Check if plugin is active, if notice was dismissed, or if current user lacks required capabilities.
    if (!is_plugin_active(MBT_PLUGIN_PATH) ||
        'yes' === get_option(MBT_TEMPLATE_NOTICE_DISMISS, 'no') ||
        !current_user_can('manage_options') ||
        !current_user_can('install_plugins')) {
        return false;
    }

    // Restrict notice display to specific admin pages and contexts.
    if (null !== $screen &&
        ((defined('DOING_AJAX') && DOING_AJAX) ||
         is_network_admin() ||
         $screen->is_block_editor())) {
        return false;
    }

    return true;
}

/**
 * Localize js.
 *
 * @since 1.0.0
 * @param string $plugin_status plugin current status.
 * @return array
 */
function mbt_localize_templates_notice_js($plugin_status)
{

    return array(
        'nonce'        => wp_create_nonce(MBT_TEMPLATE_NOTICE_DISMISS . '-nonce'),
        'ajaxurl'      => admin_url('admin-ajax.php'),
        'pluginStatus' => $plugin_status,
    );
}

/**
 * Copies the files from a source directory to a destination directory.
 *
 * @since 1.1.0
 * @param string $source_dir The source directory path.
 * @param string $destination_dir The destination directory path.
 * @return void
 */
function mbt_copy_directory($source_dir, $destination_dir)
{
    require_once ABSPATH . 'wp-admin/includes/file.php';

    // Check if the source directory exists and is readable
    if (!is_dir($source_dir) || !is_readable($source_dir)) {
        error_log(sprintf(__("Source directory does not exist or is not readable: %s", 'maxiblocks'), $source_dir));
        wp_send_json_error(sprintf(__("Source directory does not exist or is not readable: %s", 'maxiblocks'), $source_dir));
        return;
    }

    // Check if the destination directory is writable
    if (!wp_is_writable($destination_dir)) {
        error_log(sprintf(__("Destination directory is not writable: %s", 'maxiblocks'), $destination_dir));
        wp_send_json_error(sprintf(__("Destination directory is not writable: %s", 'maxiblocks'), $destination_dir));
        return;
    }

    // Create the destination directory if it doesn't exist
    wp_mkdir_p($destination_dir);

    // Open the source directory
    $dir = opendir($source_dir);
    if ($dir) {
        // Loop through the files in the source directory
        while (false !== ($file = readdir($dir))) {
            // Skip . and .. entries
            if (($file != '.') && ($file != '..')) {
                $source_path = $source_dir . '/' . $file;
                $destination_path = $destination_dir . '/' . $file;

                // Copy only files, ignore subdirectories
                if (is_file($source_path)) {
                    if (copy($source_path, $destination_path)) {
                        error_log(sprintf(__("Copied file: %s to %s", 'maxiblocks'), $source_path, $destination_path));
                    } else {
                        error_log(sprintf(__("Failed to copy file: %s to %s", 'maxiblocks'), $source_path, $destination_path));
                    }
                }
            }
        }
        closedir($dir);
    } else {
        error_log(sprintf(__("Failed to open directory: %s", 'maxiblocks'), $source_dir));
        wp_send_json_error(sprintf(__("Failed to open directory: %s", 'maxiblocks'), $source_dir));
    }
}

/**
 * Copies the content of the /maxi/patterns, /maxi/templates, and /maxi/parts folders
 * into the /patterns, /templates, and /parts folders respectively.
 *
 * @since 1.1.0
 * @return void
 */
function mbt_copy_patterns()
{
    // Copy patterns
    mbt_copy_directory(MBT_MAXI_PATTERNS_PATH, MBT_PATH . '/patterns');

    // Copy templates
    mbt_copy_directory(MBT_MAXI_TEMPLATES_PATH, MBT_PATH . '/templates');

    // Copy parts
    mbt_copy_directory(MBT_MAXI_PARTS_PATH, MBT_PATH . '/parts');

    wp_send_json_success('Patterns, templates, and parts copied successfully');
}

// Add the mbt_copy_patterns function to the WordPress AJAX actions
add_action('wp_ajax_mbt_copy_patterns', 'mbt_copy_patterns');
