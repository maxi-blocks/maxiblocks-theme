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

if (!function_exists('mbt_block_styles')) :
    /**
     * Register custom block styles
     *
     * @since MaxiBlocks Theme 1.0.0
     * @return void
     */
    function mbt_block_styles()
    {
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
        'mbt-post-single' => array('label' => __('MaxiBlocks post single', 'maxiblocks')),
        'mbt-homepage' => array('label' => __('MaxiBlocks homepage', 'maxiblocks')),
        'mbt-services-page' => array('label' => __('MaxiBlocks services page', 'maxiblocks')),
        'mbt-faq-page' => array('label' => __('MaxiBlocks faq page', 'maxiblocks')),
        'mbt-footer' => array('label' => __('MaxiBlocks footer', 'maxiblocks')),
        'mbt-header-navigation' => array('label' => __('MaxiBlocks header navigation', 'maxiblocks')),
        'mbt-blog-index' => array('label' => __('MaxiBlocks blog index', 'maxiblocks')),
        'mbt-not-found-404' => array('label' => __('MaxiBlocks not found 404', 'maxiblocks')),
        'mbt-all-archives' => array('label' => __('MaxiBlocks all archives', 'maxiblocks')),
        'mbt-date-archive' => array('label' => __('MaxiBlocks date archive', 'maxiblocks')),
        'mbt-tag-archive' => array('label' => __('MaxiBlocks tag archive', 'maxiblocks')),
        'mbt-author-archive' => array('label' => __('MaxiBlocks author archive', 'maxiblocks')),
        'mbt-category-archive' => array('label' => __('MaxiBlocks category archive', 'maxiblocks')),
        'mbt-taxonomy-archive' => array('label' => __('MaxiBlocks taxonomy archive', 'maxiblocks')),
        'mbt-search-results' => array('label' => __('MaxiBlocks search results', 'maxiblocks')),
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
            error_log(__('MaxiBlocks Theme: Block pattern file not found:', 'maxiblocks').' '. $pattern_file_path);
        }
    }

}

// Hook the function to the init action.
add_action('init', 'mbt_register_maxi_block_patterns');

function mbt_after_theme_activation()
{
    if (!get_option('maxiblocks_theme_db_done')) {
        mbt_add_styles_meta_fonts_to_db();
    }
}

add_action('after_switch_theme', 'mbt_after_theme_activation');

function mbt_add_styles_meta_fonts_to_db()
{
    global $wpdb;

    // Check if the function has already run
    if (get_option('maxiblocks_theme_db_done')) {
        return;
    }

    // Check if the necessary tables exist
    $styles_table = "{$wpdb->prefix}maxi_blocks_styles_blocks";
    $custom_data_table = "{$wpdb->prefix}maxi_blocks_custom_data_blocks";

    if ($wpdb->get_var("SHOW TABLES LIKE '$styles_table'") != $styles_table ||
        $wpdb->get_var("SHOW TABLES LIKE '$custom_data_table'") != $custom_data_table) {
        return;
    }

    $db_folder = get_template_directory() . '/db/';

    // Check if the 'db' folder exists
    if (is_dir($db_folder)) {
        // Get all JSON files in the 'db' folder
        $json_files = glob($db_folder . '*.json');

        foreach ($json_files as $json_file) {
            // Read the JSON file contents
            $json_data = file_get_contents($json_file);

            // Decode the JSON data
            $data = json_decode($json_data, true);

            // Extract the necessary information from the decoded data
            $unique_id = $data['block_style_id'] ?? '';
            if ($unique_id === '') {
                continue;
            }
            
            $css_value = $data['css_value'] ?? '';
            $fonts_value = $data['fonts_value'] ?? '';
            if ($css_value === '' && $fonts_value === '') {
                continue;
            }

            $active_custom_data = $data['active_custom_data'] ?? 0;
            $custom_data_value = $data['custom_data_value'] ?? '';

            if ($custom_data_value === '' || $custom_data_value === '[]') {
                $custom_data_value = '';
            }

            // Check if a row with the same unique_id already exists
            $exists = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM $styles_table WHERE block_style_id = %s",
                    $unique_id
                ),
                OBJECT
            );

            if (empty($exists)) {
                // Insert a new row into the styles table
                $wpdb->insert(
                    $styles_table,
                    array(
                        'block_style_id' => $unique_id,
                        'css_value' => $css_value,
                        'fonts_value' => $fonts_value,
                        'active_custom_data' => $active_custom_data,
                    ),
                    array('%s', '%s', '%s', '%d')
                );

                // Insert a new row into the custom data table if custom_data_value is not empty or '[]'
                if ($custom_data_value !== '') {
                    $wpdb->insert(
                        $custom_data_table,
                        array(
                            'block_style_id' => $unique_id,
                            'custom_data_value' => $custom_data_value,
                        ),
                        array('%s', '%s')
                    );
                }
            }
        }
    }

    // Set the option to indicate that the function has run
    update_option('maxiblocks_theme_db_done', true);
}

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

function mbt_setup_default_menu()
{
    $existing_menus = wp_get_nav_menus();
    
    // Check if there are no menus existing on the site
    if (empty($existing_menus)) {
        $menu_name = 'MaxiBlocks Menu';
        $menu_id = wp_create_nav_menu($menu_name);
        
        // Add default menu items here
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Home', 'maxiblocks'),
            'menu-item-url' => home_url('/'),
            'menu-item-status' => 'publish'
        ));

        // Add Features menu item and get its ID
        $features_id = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Features', 'maxiblocks'),
            'menu-item-url' => '#',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ));

        // Add sub-menu items under Features
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Sub-menu #1', 'maxiblocks'),
            'menu-item-url' => '#',
            'menu-item-parent-id' => $features_id,
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Sub-menu #2', 'maxiblocks'),
            'menu-item-url' => '#',
            'menu-item-parent-id' => $features_id,
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('How it works', 'maxiblocks'),
            'menu-item-url' => '#',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Get in touch', 'maxiblocks'),
            'menu-item-url' => '#',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ));

        // Assign the newly created menu to a theme location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;  // 'primary' is the theme location identifier
        set_theme_mod('nav_menu_locations', $locations);
    }
}
add_action('after_setup_theme', 'mbt_setup_default_menu');
