<?php
/**
 * MaxiBlocks Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MaxiBlocks Theme
 * @since MaxiBlocks Theme 1.6.0
 */

if (!defined('MBT_DEBUG')) {  // Set to false in production
    define('MBT_DEBUG', true);
}
if (!defined('MBT_VERSION')) {
    define('MBT_VERSION', wp_get_theme()->get('Version'));
}
if (!defined('MBT_PREFIX')) {
    define('MBT_PREFIX', 'maxiblocks-theme-');
}
if (!defined('MBT_PATH')) { // path to the root theme folder
    define('MBT_PATH', get_template_directory());
}
if (!defined('MBT_MAXI_PATTERNS_PATH')) { // path to the .maxi-patterns folder
    define('MBT_MAXI_PATTERNS_PATH', get_template_directory() . '/maxi-patterns/');
}
if (!defined('MBT_MAXI_PATTERNS_URL')) {
    define('MBT_MAXI_PATTERNS_URL', get_template_directory_uri() . '/maxi-patterns/');
}
if (!defined('MBT_PATH_BUILD_ADMIN_PHP')) { // path to the /assets/build/admin/php theme folder
    define('MBT_PATH_BUILD_ADMIN_PHP', get_template_directory() . '/assets/build/admin/php');
}
if (!defined('MBT_PATH_SRC_ADMIN_PHP')) { // path to the /assets/src/admin/php theme folder
    define('MBT_PATH_SRC_ADMIN_PHP', get_template_directory() . '/assets/src/admin/php');
}
if (!defined('MBT_URL')) { // url to the root theme folder
    define('MBT_URL', get_template_directory_uri());
}
if (!defined('MBT_URL_BUILD_ADMIN')) { // url to the /assets/build/admin theme folder
    define('MBT_URL_BUILD_ADMIN', get_template_directory_uri() . '/assets/build/admin');
}
if (!defined('MBT_URL_BUILD_FRONTEND')) { // url to the /assets/build/frontend theme folder
    define('MBT_URL_BUILD_FRONTEND', get_template_directory_uri() . '/assets/build/frontend');
}
if (!defined('MBT_URL_SRC_ADMIN')) { // url to the /assets/src/admin theme folder
    define('MBT_URL_SRC_ADMIN', get_template_directory_uri() . '/assets/src/admin');
}
if (!defined('MBT_URL_SRC_FRONTEND')) { // url to the /assets/src/frontend theme folder
    define('MBT_URL_SRC_FRONTEND', get_template_directory_uri() . '/assets/src/frontend');
}
if (!defined('MBT_PLUGIN_PATH')) { // maxi-blocks plugin path
    define('MBT_PLUGIN_PATH', 'maxi-blocks/plugin.php');
}
if (!defined('MBT_FSE_JS')) {
    define('MBT_FSE_JS', MBT_PREFIX . 'fse');
}

function mbt_include_php_files($directory)
{
    foreach (glob("{$directory}/*.php") as $file) {
        require_once($file);
    }
}

if (defined('MBT_DEBUG') && MBT_DEBUG) {
    // Include files from the SRC directory for development
    mbt_include_php_files(MBT_PATH_SRC_ADMIN_PHP);
} else {
    // Include files from the BUILD directory for production
    mbt_include_php_files(MBT_PATH_BUILD_ADMIN_PHP);
}


/**
 * Register block styles.
 */

if (! function_exists('mbt_block_styles')) :
    /**
     * Register custom block styles
     *
     * @since MaxiBlocks Theme 1.6.0
     * @return void
     */
    function mbt_block_styles()
    {

        register_block_style(
            'core/navigation-link',
            array(
                'name'         => 'arrow-link',
                'label'        => __('With arrow', 'maxiblocks'),
                /*
                 * Styles for the custom arrow nav link block style
                 */
                'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
            )
        );
        register_block_style(
            'core/heading',
            array(
                'name'         => 'asterisk',
                'label'        => __('With asterisk', 'maxiblocks'),
                'inline_style' => "
				.is-style-asterisk:before {
					content: '';
					width: 1.5rem;
					height: 3rem;
					background: var(--wp--preset--color--contrast-2, currentColor);
					clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
					display: block;
				}

				/* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
				.is-style-asterisk:empty:before {
					content: none;
				}

				.is-style-asterisk:-moz-only-whitespace:before {
					content: none;
				}

				.is-style-asterisk.has-text-align-center:before {
					margin: 0 auto;
				}

				.is-style-asterisk.has-text-align-right:before {
					margin-left: auto;
				}

				.rtl .is-style-asterisk.has-text-align-left:before {
					margin-right: auto;
				}",
            )
        );
    }
endif;

add_action('init', 'mbt_block_styles');

/**
 * Registers customization options for the theme.
 *
 * This function adds a new setting for custom theme CSS and a corresponding control
 * to the WordPress Customizer, allowing users to add their own CSS to the theme.
 * The 'transport' method is set to 'refresh', meaning changes will be applied by reloading the page.
 *
 * @param WP_Customize_Manager $wp_customize WordPress Customizer object. It's used to add settings and controls to the customizer.
 */
function mbt_customize_register($wp_customize)
{
    // Add a new setting for custom theme CSS.
    $wp_customize->add_setting('mbt_custom_theme_css', array(
        'default'     => '',
        'transport'   => 'refresh',
    ));

    // Add a new control to the customizer for the custom theme CSS setting.
    $wp_customize->add_control(new WP_Customize_Code_Editor_Control($wp_customize, 'mbt_custom_theme_css', array(
        'label'       => __('Custom Theme CSS', 'maxiblocks'),
        'section'     => 'mbt_new_section_name',
        'settings'    => 'mbt_custom_theme_css',
        'code_type'   => 'text/css',
    )));
}
add_action('customize_register', 'mbt_customize_register');

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (!is_plugin_active(MBT_PLUGIN_PATH)) {
    function mbt_enqueue_fonts()
    {
        $font_url = MBT_URL . '/assets/fonts/roboto/roboto-font.css';
        wp_enqueue_style(MBT_PREFIX . 'roboto-font', $font_url, array(), null);
    }
    
    // Hook into both front-end and admin scripts
    add_action('wp_enqueue_scripts', 'mbt_enqueue_fonts');
    add_action('admin_enqueue_scripts', 'mbt_enqueue_fonts');
}

function mbt_enqueue_admin_styles()
{
    // Check if we are in debug mode
    if (defined('MBT_DEBUG') && MBT_DEBUG) {
        // Use the unminified CSS file in the SRC directory
        $admin_css_url = MBT_URL_SRC_ADMIN . '/css/style.css';
    } else {
        // Use the minified CSS file in the BUILD directory
        $admin_css_url = MBT_URL_BUILD_ADMIN . '/css/styles.min.css';
    }

    // Enqueue the admin stylesheet.
    wp_enqueue_style(MBT_PREFIX . 'admin-styles', $admin_css_url, array(), MBT_VERSION, 'all');
}
add_action('admin_enqueue_scripts', 'mbt_enqueue_admin_styles');

function mbt_custom_theme_css()
{
    $custom_css = get_theme_mod('mbt_custom_theme_css');
    wp_add_inline_style(MBT_PREFIX . 'custom-styles', $custom_css);
}
add_action('wp_enqueue_scripts', 'mbt_custom_theme_css');

function mbt_get_maxi_patterns()
{
    return glob(MBT_MAXI_PATTERNS_PATH . '*', GLOB_ONLYDIR);
}

/**
 * Registers custom block pattern categories and block patterns for MaxiBlocks.
 *
 * @since MaxiBlocks Theme 1.6.0
 */
function mbt_register_maxi_block_patterns()
{
    // Define block pattern categories with labels.
    $block_pattern_categories = array(
        'mbt-about-page' => array('label' => __('MaxiBlocks about page', 'maxiblocks')),
        'mbt-author-bio' => array('label' => __('MaxiBlocks author bio', 'maxiblocks')),
        'mbt-author-archive' => array('label' => __('MaxiBlocks author archive', 'maxiblocks')),
        'mbt-post-single' => array('label' => __('MaxiBlocks post single', 'maxiblocks')),
        'mbt-homepage' => array('label' => __('MaxiBlocks homepage', 'maxiblocks')),
        'mbt-services-page' => array('label' => __('MaxiBlocks services page', 'maxiblocks')),
        'mbt-faq-page' => array('label' => __('MaxiBlocks faq page', 'maxiblocks')),
        'mbt-footer' => array('label' => __('MaxiBlocks footer', 'maxiblocks')),
        'mbt-header-navigation' => array('label' => __('MaxiBlocks header navigation', 'maxiblocks')),
        'mbt-blog-index' => array('label' => __('MaxiBlocks blog index', 'maxiblocks')),
        'mbt-not-found-404' => array('label' => __('MaxiBlocks not found 404', 'maxiblocks')),
        'mbt-date-archive' => array('label' => __('MaxiBlocks date archive', 'maxiblocks')),
        'mbt-tag-archive' => array('label' => __('MaxiBlocks tag archive', 'maxiblocks')),
        'mbt-category-archive' => array('label' => __('MaxiBlocks category archive', 'maxiblocks')),
        'mbt-taxonomy-archive' => array('label' => __('MaxiBlocks taxonomy archive', 'maxiblocks')),
        'mbt-comments' => array('label' => __('MaxiBlocks comments', 'maxiblocks')),
        'mbt-sidebar' => array('label' => __('MaxiBlocks sidebar', 'maxiblocks'))
    );

    // Allow filtering the block pattern categories.
    $block_pattern_categories = apply_filters('mbt_block_pattern_categories', $block_pattern_categories);

    // Register each block pattern category.
    foreach ($block_pattern_categories as $name => $properties) {
        register_block_pattern_category($name, $properties);
    }

    // Get a list of directories inside the maxi-patterns directory.
    $pattern_directories = mbt_get_maxi_patterns();

    // Allow filtering the block patterns directories.
    $pattern_directories = apply_filters('mbt_block_pattern_directories', $pattern_directories);

    // Register each block pattern.
    foreach ($pattern_directories as $directory_path) {
        // Extract the block pattern name from the directory path.
        $block_pattern_name = basename($directory_path);

        // Construct the path to the pattern file.
        $pattern_file_path = $directory_path . '/pattern.php';

        // Check if the pattern file exists.
        if (file_exists($pattern_file_path)) {
            // Register the block pattern.
            register_block_pattern(
                'maxiblocks/' . $block_pattern_name,
                require $pattern_file_path
            );
        } else {
            error_log("Block pattern file not found: $pattern_file_path");
        }
    }
}

// Hook the function to the init action.
add_action('init', 'mbt_register_maxi_block_patterns');

function mbt_fse_admin_script()
{
    $fse_js_url = defined('MBT_DEBUG') && MBT_DEBUG ?
        MBT_URL_SRC_ADMIN . '/js/fse.js' :
        MBT_URL_BUILD_ADMIN . '/js/fse.js';
    
    wp_enqueue_script(
        MBT_FSE_JS,
        $fse_js_url,
        [],
        MBT_VERSION,
        true
    );


    $vars = array(
        'url'         => MBT_MAXI_PATTERNS_URL,
        'directories' => mbt_get_maxi_patterns(),
    );

    wp_localize_script(MBT_FSE_JS, 'maxiblocks', $vars);


}

add_action('admin_enqueue_scripts', 'mbt_fse_admin_script');
