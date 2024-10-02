<?php
/**
 * MaxiBlocks Go theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MaxiBlocks Go theme
 * @since MaxiBlocks Go theme 1.0.1
 */

if (!defined('MAXIBLOCKS_GO_DEBUG')) {  // Set to false in production
    define('MAXIBLOCKS_GO_DEBUG', false);
}
if (!defined('MAXIBLOCKS_GO_VERSION')) {
    define('MAXIBLOCKS_GO_VERSION', wp_get_theme()->get('Version'));
}
if (!defined('MAXIBLOCKS_GO_PREFIX')) {
    define('MAXIBLOCKS_GO_PREFIX', 'maxiblocks-go-theme-');
}
if (!defined('MAXIBLOCKS_GO_PATH')) { // path to the root theme folder
    define('MAXIBLOCKS_GO_PATH', get_template_directory());
}
if (!defined('MAXIBLOCKS_GO_PATH_BUILD_ADMIN_PHP')) { // path to the /assets/build/admin/php theme folder
    define('MAXIBLOCKS_GO_PATH_BUILD_ADMIN_PHP', get_template_directory() . '/assets/build/admin/php');
}
if (!defined('MAXIBLOCKS_GO_PATH_SRC_ADMIN_PHP')) { // path to the /assets/src/admin/php theme folder
    define('MAXIBLOCKS_GO_PATH_SRC_ADMIN_PHP', get_template_directory() . '/assets/src/admin/php');
}
if (!defined('MAXIBLOCKS_GO_URL')) { // url to the root theme folder
    define('MAXIBLOCKS_GO_URL', get_template_directory_uri());
}
if (!defined('MAXIBLOCKS_GO_URL_BUILD_ADMIN')) { // url to the /assets/build/admin theme folder
    define('MAXIBLOCKS_GO_URL_BUILD_ADMIN', get_template_directory_uri() . '/assets/build/admin');
}
if (!defined('MAXIBLOCKS_GO_URL_BUILD_FRONTEND')) { // url to the /assets/build/frontend theme folder
    define('MAXIBLOCKS_GO_URL_BUILD_FRONTEND', get_template_directory_uri() . '/assets/build/frontend');
}
if (!defined('MAXIBLOCKS_GO_URL_SRC_ADMIN')) { // url to the /assets/src/admin theme folder
    define('MAXIBLOCKS_GO_URL_SRC_ADMIN', get_template_directory_uri() . '/assets/src/admin');
}if (!defined('MAXIBLOCKS_GO_MAXI_PATTERNS_PATH')) { // path to the maxi/patterns folder
    define('MAXIBLOCKS_GO_MAXI_PATTERNS_PATH', get_template_directory() . '/maxi/patterns/');
}
if (!defined('MAXIBLOCKS_GO_MAXI_PATTERNS_URL')) {
    define('MAXIBLOCKS_GO_MAXI_PATTERNS_URL', get_template_directory_uri() . '/maxi/patterns/');
}
if (!defined('MAXIBLOCKS_GO_MAXI_TEMPLATES_PATH')) { // path to the maxi/templates folder
    define('MAXIBLOCKS_GO_MAXI_TEMPLATES_PATH', get_template_directory() . '/maxi/templates/');
}
if (!defined('MAXIBLOCKS_GO_MAXI_PARTS_PATH')) { // path to the maxi/parts folder
    define('MAXIBLOCKS_GO_MAXI_PARTS_PATH', get_template_directory() . '/maxi/parts/');
}
if (!defined('MAXIBLOCKS_GO_URL_SRC_FRONTEND')) { // url to the /assets/src/frontend theme folder
    define('MAXIBLOCKS_GO_URL_SRC_FRONTEND', get_template_directory_uri() . '/assets/src/frontend');
}
if (!defined('MAXIBLOCKS_GO_PLUGIN_PATH')) { // maxi-blocks plugin path
    define('MAXIBLOCKS_GO_PLUGIN_PATH', 'maxi-blocks/plugin.php');
}

/**
 * Load the theme's translated strings.
 */
function maxiblocks_go_load_theme_textdomain()
{
    load_theme_textdomain('maxiblocks-go', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'maxiblocks_go_load_theme_textdomain');

function maxiblocks_go_include_php_files($directory)
{
    foreach (glob("{$directory}/*.php") as $file) {
        require_once($file);
    }
}

if (defined('MAXIBLOCKS_GO_DEBUG') && MAXIBLOCKS_GO_DEBUG) {
    // Include files from the SRC directory for development
    maxiblocks_go_include_php_files(MAXIBLOCKS_GO_PATH_SRC_ADMIN_PHP);
} else {
    // Include files from the BUILD directory for production
    maxiblocks_go_include_php_files(MAXIBLOCKS_GO_PATH_BUILD_ADMIN_PHP);
}

/**
 * Registers customization options for the theme.
 *
 * This function adds a new setting for custom theme CSS and a corresponding control
 * to the WordPress Customizer, allowing users to add their own CSS to the theme.
 * The 'transport' method is set to 'refresh', meaning changes will be applied by reloading the page.
 *
 * @param WP_Customize_Manager $wp_customize WordPress Customizer object. It's used to add settings and controls to the customizer.
 */
function maxiblocks_go_customize_register($wp_customize)
{
    // Add a new setting for custom theme CSS.
    $wp_customize->add_setting('maxiblocks_go_custom_theme_css', array(
        'default'     => '',
        'transport'   => 'refresh',
    ));

    // Add a new control to the customizer for the custom theme CSS setting.
    $wp_customize->add_control(new WP_Customize_Code_Editor_Control($wp_customize, 'maxiblocks_go_custom_theme_css', array(
        'label'       => __('Custom Theme CSS', 'maxiblocks-go'),
        'section'     => 'maxiblocks_go_new_section_name',
        'settings'    => 'maxiblocks_go_custom_theme_css',
        'code_type'   => 'text/css',
    )));
}

add_action('customize_register', 'maxiblocks_go_customize_register', 11);

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (!is_plugin_active(MAXIBLOCKS_GO_PLUGIN_PATH)) {
    function maxiblocks_go_enqueue_fonts()
    {
        $font_url = MAXIBLOCKS_GO_URL . '/assets/fonts/roboto/roboto-font.css';
        wp_enqueue_style(MAXIBLOCKS_GO_PREFIX . 'roboto-font', $font_url, array(), null);
    }
    
    // Hook into both front-end and admin scripts
    add_action('wp_enqueue_scripts', 'maxiblocks_go_enqueue_fonts');
    add_action('admin_enqueue_scripts', 'maxiblocks_go_enqueue_fonts');
}

function maxiblocks_go_enqueue_admin_styles()
{
    // Check if we are in debug mode
    if (defined('MAXIBLOCKS_GO_DEBUG') && MAXIBLOCKS_GO_DEBUG) {
        // Use the unminified CSS file in the SRC directory
        $admin_css_url = MAXIBLOCKS_GO_URL_SRC_ADMIN . '/css/style.css';
    } else {
        // Use the minified CSS file in the BUILD directory
        $admin_css_url = MAXIBLOCKS_GO_URL_BUILD_ADMIN . '/css/styles.min.css';
    }

    // Enqueue the admin stylesheet.
    wp_enqueue_style(MAXIBLOCKS_GO_PREFIX . 'admin-styles', $admin_css_url, array(), MAXIBLOCKS_GO_VERSION, 'all');
}
add_action('admin_enqueue_scripts', 'maxiblocks_go_enqueue_admin_styles');

function maxiblocks_go_enqueue_frontend_styles()
{
    // Check if we are in debug mode
    if (defined('MAXIBLOCKS_GO_DEBUG') && MAXIBLOCKS_GO_DEBUG) {
        // Use the unminified CSS file in the build directory
        $frontend_css_url = MAXIBLOCKS_GO_URL_BUILD_FRONTEND . '/css/styles.css';
    } else {
        // Use the minified CSS file in the BUILD directory
        $frontend_css_url = MAXIBLOCKS_GO_URL_BUILD_FRONTEND . '/css/styles.min.css';
    }

    // Enqueue the admin stylesheet.
    wp_enqueue_style(MAXIBLOCKS_GO_PREFIX . 'frontend-styles', $frontend_css_url, array(), MAXIBLOCKS_GO_VERSION, 'all');
}
add_action('wp_enqueue_scripts', 'maxiblocks_go_enqueue_frontend_styles');

function maxiblocks_go_custom_theme_css()
{
    $custom_css = get_theme_mod('maxiblocks_go_custom_theme_css');
    wp_add_inline_style(MAXIBLOCKS_GO_PREFIX . 'custom-styles', $custom_css);
}
add_action('wp_enqueue_scripts', 'maxiblocks_go_custom_theme_css');

/** Add widgets support for Customizer **/
if (is_customize_preview() && ! current_theme_supports('widgets')) {
    add_theme_support('widgets');
}
