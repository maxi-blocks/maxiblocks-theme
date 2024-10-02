<?php
/**
 * Admin Install Plugin Notice
 *
 * @package MaxiBlocks Go theme
 * @author MaxiBlocks
 * @since 1.0.0
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!defined('MAXIBLOCKS_GO_PLUGIN_NOTICE_JS')) {
    define('MAXIBLOCKS_GO_PLUGIN_NOTICE_JS', MAXIBLOCKS_GO_PREFIX . 'install-plugin-notice');
}

if (!defined('MAXIBLOCKS_GO_PLUGIN_NOTICE_DISMISS')) {
    define('MAXIBLOCKS_GO_PLUGIN_NOTICE_DISMISS', MAXIBLOCKS_GO_PREFIX . 'dismiss-plugin-notice');
}

add_action('admin_notices', 'maxiblocks_go_render_install_plugin_notice', 0);
add_action('wp_ajax_maxiblocks-go-theme-dismiss-plugin-notice', 'maxiblocks_go_close_install_plugin_notice');

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
function maxiblocks_go_render_install_plugin_notice()
{
    // Check if the notice should be displayed.
    if (!maxiblocks_go_plugin_notice_display()) {
        return;
    }

    // Get the plugin status.
    $plugin_status = maxiblocks_go_is_maxiblocks_plugin_status();

    // Determine the JavaScript file URL based on the debug mode.
    $notice_js_url = defined('MAXIBLOCKS_GO_DEBUG') && MAXIBLOCKS_GO_DEBUG ?
                     MAXIBLOCKS_GO_URL_SRC_ADMIN . '/js/install-plugin-notice.js' :
                     MAXIBLOCKS_GO_URL_BUILD_ADMIN . '/js/install-plugin-notice.js';

    // Enqueue the script.
    wp_enqueue_script(MAXIBLOCKS_GO_PLUGIN_NOTICE_JS, $notice_js_url, [], MAXIBLOCKS_GO_VERSION, true);
    wp_localize_script(MAXIBLOCKS_GO_PLUGIN_NOTICE_JS, 'maxiblocks', maxiblocks_go_localize_install_plugin_notice_js($plugin_status));

    // Define other variables.
    $more_info_url = 'https://maxiblocks.com/go/maxi-theme-activation-more-info';

    // Start output buffering.
    ob_start();
    ?>
<div class="maxiblocks-go-notice maxiblocks-go-notice--info">
    <button type="button" class="maxiblocks-go-notice__dismiss">
        <span class="maxiblocks-go-notice__dismiss-text">&#10799;</span>
    </button>
    <div class="maxiblocks-go-notice__row">
        <div class="maxiblocks-go-notice__col">
            <div class="maxiblocks-go-notice__content">
                <p class="maxiblocks-go-notice__subtitle">
                    <?php esc_html_e('Thank you for choosing the MaxiBlocks Go theme', 'maxiblocks-go');?>
                </p>
                <h2 class="maxiblocks-go-notice__title">
                    <?php $plugin_status === 'installed' ?  esc_html_e('Please activate the MaxiBlocks companion plugin', 'maxiblocks-go') : esc_html_e('Please install the MaxiBlocks companion plugin', 'maxiblocks-go'); ?>
                </h2>
                <p class="maxiblocks-go-notice__description">
                    <?php esc_html_e('Get advanced block editing features, responsive controls, interactions and hover effects. Explore 2,200+ designer-made patterns, 190 pages, 14,200 icons and 100 global style cards to speed up your design process.', 'maxiblocks-go'); ?>
                </p>
                <div class="maxiblocks-go-notice__actions">
                    <button id="maxiblocks-go-notice-install-maxiblocks" class="maxiblocks-go-button maxiblocks-go-button--primary maxiblocks-go-button--hero">
                        <span class="maxiblocks-go-button__text">
                            <?php $plugin_status === 'installed' ?  esc_html_e('Activate MaxiBlocks plugin', 'maxiblocks-go') : esc_html_e('Install MaxiBlocks plugin', 'maxiblocks-go');?>
                        </span><span class="maxiblocks-go-button__icon">&rsaquo;</span></button>
                    <a href="<?php echo esc_url($more_info_url); ?>" target="_blank"
                        class="maxiblocks-go-button maxiblocks-go-button--primary maxiblocks-go-button--hero">
                        <span class="maxiblocks-go-button__text"><?php esc_html_e('More info', 'maxiblocks-go'); ?>
                        </span><span class="maxiblocks-go-button__icon">&rsaquo;</span>
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
function maxiblocks_go_close_install_plugin_notice()
{
    if (!isset($_POST['nonce'])) {
        return;
    }

    if (isset($_POST['nonce']) && is_string($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['nonce']), MAXIBLOCKS_GO_PLUGIN_NOTICE_DISMISS . '-nonce')) {
        return;
    }
    update_option(MAXIBLOCKS_GO_PLUGIN_NOTICE_DISMISS, 'yes');
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
function maxiblocks_go_plugin_notice_display()
{
    $screen = get_current_screen();

    // Check if plugin is active, if notice was dismissed, or if current user lacks required capabilities.
    if (is_plugin_active(MAXIBLOCKS_GO_PLUGIN_PATH) ||
        'yes' === get_option(MAXIBLOCKS_GO_PLUGIN_NOTICE_DISMISS, 'no') ||
        !current_user_can('manage_options') ||
        !current_user_can('install_plugins')) {
        return false;
    }

    // Restrict notice display to specific admin pages and contexts.
    if (null !== $screen && // in_array($screen->id, ['dashboard', 'themes']) ||
        ((defined('DOING_AJAX') && DOING_AJAX) ||
         is_network_admin() ||
         $screen->is_block_editor())) {
        return false;
    }

    return true;
}


/**
 * Retrieves the status of the MaxiBlocks plugin.
 *
 * The function checks the status of the plugin and returns it as a string:
 * - 'activated' if the plugin is active.
 * - 'installed' if the plugin is installed but not active.
 * - 'not-installed' if the plugin is not installed.
 *
 * @since 1.0.0
 * @return string The status of the MaxiBlocks plugin.
 */
function maxiblocks_go_is_maxiblocks_plugin_status()
{
    $plugin_slug = MAXIBLOCKS_GO_PLUGIN_PATH;

    // Check if the plugin is active.
    if (is_plugin_active($plugin_slug)) {
        return 'activated';
    }

    // Check if the plugin is installed (exists in the plugins directory).
    if (file_exists(WP_PLUGIN_DIR . '/' . $plugin_slug)) {
        return 'installed';
    }

    // Default to 'not-installed' if the above conditions are not met.
    return 'not-installed';
}


/**
 * Localize js.
 *
 * @since 1.0.0
 * @param string $plugin_status plugin current status.
 * @return array
 */
function maxiblocks_go_localize_install_plugin_notice_js($plugin_status)
{

    return array(
        'nonce'         => wp_create_nonce(MAXIBLOCKS_GO_PLUGIN_NOTICE_DISMISS . '-nonce'),
        'ajaxUrl'       => esc_url(admin_url('admin-ajax.php')),
        'pluginStatus'  => $plugin_status,
        'pluginSlug'    => 'maxi-blocks',
        'activationUrl' => esc_url(
            add_query_arg(
                array(
                    'plugin_status' => 'all',
                    'paged'         => '1',
                    'action'        => 'activate',
                    'plugin'        => rawurlencode(MAXIBLOCKS_GO_PLUGIN_PATH),
                    '_wpnonce'      => wp_create_nonce('activate-plugin_maxi-blocks/plugin.php'),
                ),
                admin_url('plugins.php')
            )
        ),
        'activating'    => __('Activating', 'maxiblocks-go') . '&#9203;',
        'installing'    => __('Installing', 'maxiblocks-go') . ' &#9203;',
        'done'          => __('Done', 'maxiblocks-go') . ' &#10003;',
    );
}
