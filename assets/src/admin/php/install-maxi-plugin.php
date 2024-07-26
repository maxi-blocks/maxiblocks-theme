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

if (!defined('MBT_PLUGIN_NOTICE_JS')) {
    define('MBT_PLUGIN_NOTICE_JS', MBT_PREFIX . 'install-plugin-notice');
}

if (!defined('MBT_PLUGIN_NOTICE_DISMISS')) {
    define('MBT_PLUGIN_NOTICE_DISMISS', MBT_PREFIX . 'dismiss-plugin-notice');
}

add_action('admin_notices', 'mbt_render_install_plugin_notice', 0);
add_action('wp_ajax_maxiblocks-theme-dismiss-plugin-notice', 'mbt_close_install_plugin_notice');

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
function mbt_render_install_plugin_notice()
{
    // Check if the notice should be displayed.
    if (!mbt_plugin_notice_display()) {
        return;
    }

    // Get the plugin status.
    $plugin_status = mbt_is_maxiblocks_plugin_status();

    // Determine the JavaScript file URL based on the debug mode.
    $notice_js_url = defined('MBT_DEBUG') && MBT_DEBUG ?
                     MBT_URL_SRC_ADMIN . '/js/install-plugin-notice.js' :
                     MBT_URL_BUILD_ADMIN . '/js/install-plugin-notice.js';

    // Enqueue the script.
    wp_enqueue_script(MBT_PLUGIN_NOTICE_JS, $notice_js_url, [], MBT_VERSION, true);
    wp_localize_script(MBT_PLUGIN_NOTICE_JS, 'maxiblocks', mbt_localize_install_plugin_notice_js($plugin_status));

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
                    <?php esc_html_e('Thank you for choosing the MaxiBlocks theme', 'maxiblocks');?>
                </p>
                <h2 class="mbt-notice__title">
                    <?php $plugin_status === 'installed' ?  esc_html_e('Please activate the MaxiBlocks companion plugin', 'maxiblocks') : esc_html_e('Please install the MaxiBlocks companion plugin', 'maxiblocks'); ?>
                </h2>
                <p class="mbt-notice__description">
                    <?php esc_html_e('Get advanced block editing features, responsive controls, interactions and hover effects. Explore 2,200+ designer-made patterns, 190 pages, 14,200 icons and 100 global style cards to speed up your design process.', 'maxiblocks'); ?>
                </p>
                <div class="mbt-notice__actions">
                    <button id="mbt-notice-install-maxiblocks" class="mbt-button mbt-button--primary mbt-button--hero">
                        <span class="mbt-button__text">
                            <?php $plugin_status === 'installed' ?  esc_html_e('Activate MaxiBlocks plugin', 'maxiblocks') : esc_html_e('Install MaxiBlocks plugin', 'maxiblocks');?>
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
function mbt_close_install_plugin_notice()
{
    if (!isset($_POST['nonce'])) {
        return;
    }

    if (isset($_POST['nonce']) && is_string($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['nonce']), MBT_PLUGIN_NOTICE_DISMISS . '-nonce')) {
        return;
    }
    update_option(MBT_PLUGIN_NOTICE_DISMISS, 'yes');
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
function mbt_plugin_notice_display()
{
    $screen = get_current_screen();

    // Check if plugin is active, if notice was dismissed, or if current user lacks required capabilities.
    if (is_plugin_active(MBT_PLUGIN_PATH) ||
        'yes' === get_option(MBT_PLUGIN_NOTICE_DISMISS, 'no') ||
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
function mbt_is_maxiblocks_plugin_status()
{
    $plugin_slug = MBT_PLUGIN_PATH;

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
function mbt_localize_install_plugin_notice_js($plugin_status)
{

    return array(
        'nonce'         => wp_create_nonce(MBT_PLUGIN_NOTICE_DISMISS . '-nonce'),
        'ajaxUrl'       => esc_url(admin_url('admin-ajax.php')),
        'pluginStatus'  => $plugin_status,
        'pluginSlug'    => 'maxi-blocks',
        'activationUrl' => esc_url(
            add_query_arg(
                array(
                    'plugin_status' => 'all',
                    'paged'         => '1',
                    'action'        => 'activate',
                    'plugin'        => rawurlencode(MBT_PLUGIN_PATH),
                    '_wpnonce'      => wp_create_nonce('activate-plugin_maxi-blocks/plugin.php'),
                ),
                admin_url('plugins.php')
            )
        ),
        'activating'    => __('Activating', 'maxiblocks') . '&#9203;',
        'installing'    => __('Installing', 'maxiblocks') . ' &#9203;',
        'done'          => __('Done', 'maxiblocks') . ' &#10003;',
    );
}
