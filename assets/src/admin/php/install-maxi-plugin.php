<?php
/**
 * Admin Welcome Notice
 *
 * @package MaxiBlocks Theme
 * @author MaxiBlocks Team
 * @since 1.6.0
 */

declare(strict_types=1);

namespace Swt;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('admin_notices', SWT_NS . 'render_install_plugin_notice', 0);
add_action('wp_ajax_swt_dismiss_install_plugin_notice', SWT_NS . 'close_install_plugin_notice');

/**
 * Render the welcome notice.
 *
 * @since 0.0.1
 * @return void
 */
function maxiblocks_render_install_plugin_notice(): void
{
    if (! maxiblocks_install_plugin_notice_display_conditions()) {
        return;
    }

    $plugin_status = maxiblocks_is_maxiblocks_plugin_status();

    $file_prefix = defined('SWT_DEBUG') && SWT_DEBUG ? '' : '.min';
    $dir_name    = defined('SWT_DEBUG') && SWT_DEBUG ? 'unminified' : 'minified';
    $css_uri     = get_uri() . 'assets/css/' . $dir_name . '/admin';

    /* Check and added rtl prefix */
    if (is_rtl()) {
        $file_prefix .= '-rtl';
    }

    /* Load Theme Styles*/
    wp_enqueue_style(SWT_SLUG . '-welcome-notice', $css_uri . '/welcome-notice' . $file_prefix . '.css', array(), SWT_VER);

    $js    = defined('SWT_DEBUG') && SWT_DEBUG ? get_uri() . 'build/' : get_uri() . 'assets/js/';
    $asset = defined('SWT_DEBUG') && SWT_DEBUG ? require SWT_DIR . 'build/install_plugin_notice.asset.php' : require SWT_DIR . 'assets/js/install_plugin_notice.asset.php';
    $deps  = $asset['dependencies'];

    wp_register_script(SWT_SLUG . '-welcome-notice', $js . 'install_plugin_notice.js', $deps, SWT_VER, true);

    wp_enqueue_script(SWT_SLUG . '-welcome-notice');

    wp_localize_script(
        SWT_SLUG . '-welcome-notice',
        SWT_LOC,
        maxiblocks_localize_install_plugin_notice_js($plugin_status)
    );

    ob_start();

    $banner_image  = get_uri() . 'assets/image/maxiblocks-plugin-banner.png';
    $lean_more_url = 'https://maxiblocks.com/';
    ?>

<div class="notice notice-info maxiblocks-welcome-notice">
    <button type="button" class="notice-dismiss"><span
            class="screen-reader-text"><?php esc_html_e('Close this notice..', 'maxiblocks'); ?></span></button>
    <div class="maxiblocks-row">
        <div class="maxiblocks-col">
            <div class="notice-content">
                <p class="sub-notice-title">
                    <?php esc_html_e('Thanks for installing the Spectra One theme &#x1F389;', 'maxiblocks'); ?>
                </p>
                <h2 class="notice-title">
                    <?php esc_html_e('Please install the Spectra Builder', 'maxiblocks'); ?>
                </h2>
                <p class="description">
                    <?php esc_html_e('Once you have installed the Spectra Builder plugin, you will be ready to build amazing, fast-loading websites.', 'maxiblocks'); ?>
                </p>
                <div class="notice-actions">
                    <button id="maxiblocks-install-maxiblocks" class="button button-primary button-hero">
                        <span class="text">
                            <?php
                                'installed' === $plugin_status ? esc_html_e('Activate Spectra Builder', 'maxiblocks') : esc_html_e('Install Spectra Builder', 'maxiblocks');
    ?>
                        </span>
                    </button>
                    <a href="<?php echo esc_url($lean_more_url); ?>" target="_blank"
                        class="button button-primary button-hero">
                        <?php esc_html_e('Learn More', 'maxiblocks'); ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="maxiblocks-col maxiblocks-col-right">
            <div class="image-container">
                <img src="<?php echo esc_url($banner_image); ?>" alt="maxiblocks-install-banner">
            </div>
        </div>
    </div>
</div>
<?php
    echo wp_kses_post(ob_get_clean());
}

/**
 * Close welcome notice.
 *
 * @since 0.0.1
 */
function maxiblocks_close_install_plugin_notice()
{
    if (! isset($_POST['nonce'])) {
        return;
    }

    if (isset($_POST['nonce']) && is_string($_POST['nonce']) && ! wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'maxiblocks-dismiss-welcome-notice-nonce')) {
        return;
    }
    update_option('maxiblocks-dismiss-welcome-notice', 'yes');
    wp_die();
}

/**
 * Welcome notice condition.
 *
 * @since 0.0.1
 * @return bool
 */
function maxiblocks_install_plugin_notice_display_conditions(): bool
{

    // Check if plugin is active.
    if (is_plugin_active('maxi-blocks/plugin.php')) {
        return false;
    }

    // Check if welcome notice was closed.
    if ('yes' === get_option('maxiblocks-dismiss-welcome-notice', 'no')) {
        return false;
    }

    $screen = get_current_screen();

    // Show the notice on dashboard.
    if (null !== $screen && ! in_array($screen->id, array( 'dashboard', 'themes' ))) {
        return false;
    }

    // Check AJAX actions.
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return false;
    }

    // Hide from network admin.
    if (is_network_admin()) {
        return false;
    }

    // Check if use can 'manage_options'.
    if (! current_user_can('manage_options')) {
        return false;
    }

    // Check if use can 'install_plugins'.
    if (! current_user_can('install_plugins')) {
        return false;
    }

    // Block editor context.
    if (null !== $screen && $screen->is_block_editor()) {
        return false;
    }

    return true;
}

/**
 * Spectra plugin status.
 *
 * @since 0.0.1
 * @return string
 */
function maxiblocks_is_maxiblocks_plugin_status(): string
{
    $plugin_slug = 'maxi-blocks/plugin.php';
    $status      = 'not-installed';

    if (is_plugin_active($plugin_slug)) {
        return 'activated';
    }

    if (file_exists(WP_PLUGIN_DIR . '/' . $plugin_slug)) {
        return 'installed';
    }

    return $status;
}

/**
 * Localize js.
 *
 * @since 0.0.1
 * @param string $plugin_status plugin current status.
 * @return array
 */
function maxiblocks_localize_install_plugin_notice_js($plugin_status): array
{

    return array(
        'nonce'         => wp_create_nonce('maxiblocks-dismiss-welcome-notice-nonce'),
        'ajaxUrl'       => esc_url(admin_url('admin-ajax.php')),
        'pluginStatus'  => $plugin_status,
        'pluginSlug'    => 'maxi-blocks',
        'activationUrl' => esc_url(
            add_query_arg(
                array(
                    'plugin_status' => 'all',
                    'paged'         => '1',
                    'action'        => 'activate',
                    'plugin'        => rawurlencode('maxi-blocks/plugin.php'),
                    '_wpnonce'      => wp_create_nonce('activate-plugin_maxi-blocks/plugin.php'),
                ),
                admin_url('plugins.php')
            )
        ),
        'activating'    => __('Activating', 'maxiblocks') . '&hellip;',
        'installing'    => __('Installing', 'maxiblocks') . '&hellip;',
        'done'          => __('Done', 'maxiblocks'),
    );
}
