<?php
/**
 * MaxiBlocks Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MaxiBlocks Theme
 * @since MaxiBlocks Theme 1.6.0
 */

/**
 * Register block styles.
 */

if (! function_exists('maxiblocks_block_styles')) :
    /**
     * Register custom block styles
     *
     * @since MaxiBlocks Theme 1.5.0
     * @return void
     */
    function maxiblocks_block_styles()
    {

        register_block_style(
            'core/details',
            array(
                'name'         => 'arrow-icon-details',
                'label'        => __('Arrow icon', 'maxiblocks'),
                /*
                 * Styles for the custom Arrow icon style of the Details block
                 */
                'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
            )
        );
        register_block_style(
            'core/post-terms',
            array(
                'name'         => 'pill',
                'label'        => __('Pill', 'maxiblocks'),
                /*
                 * Styles variation for post terms
                 * https://github.com/WordPress/gutenberg/issues/24956
                 */
                'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;
					border-radius: var(--wp--preset--spacing--20);
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
            )
        );
        register_block_style(
            'core/list',
            array(
                'name'         => 'checkmark-list',
                'label'        => __('Checkmark', 'maxiblocks'),
                /*
                 * Styles for the custom checkmark list block style
                 * https://github.com/WordPress/gutenberg/issues/51480
                 */
                'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
            )
        );
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

add_action('init', 'maxiblocks_block_styles');


function maxiblocks_customize_register($wp_customize)
{
    $wp_customize->add_setting('maxiblocks_custom_theme_css', array(
        'default'     => '',
        'transport'   => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Code_Editor_Control($wp_customize, 'maxiblocks_custom_theme_css', array(
        'label'       => __('Custom Theme CSS', 'maxiblocks'),
        'section'     => 'maxiblocks_new_section_name',
        'settings'    => 'maxiblocks_custom_theme_css',
        'code_type'   => 'text/css',
    )));
}
add_action('customize_register', 'maxiblocks_customize_register');

function maxiblocks_enqueue_fonts()
{
    // Check if the 'maxi-blocks' plugin is active
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (!is_plugin_active('maxi-blocks/plugin.php')) {
        // Enqueue Roboto font here if 'maxi-blocks' is NOT active
        wp_enqueue_style('maxiblocks-theme-roboto-font', get_template_directory().'/assets/fonts/roboto/Roboto-Regular.woff2');
        // Replace 'path_to_your_font_file' with the actual path to the Roboto font file
    }
}

add_action('wp_enqueue_scripts', 'maxiblocks_enqueue_fonts');

function maxiblocks_enqueue_admin_styles()
{
    // Use get_template_directory_uri() if the theme is not a child theme.
    // Use get_stylesheet_directory_uri() if the theme is a child theme.
    $admin_css_url = get_template_directory_uri() . '/assets/build/admin/css/styles.min.css';

    // Enqueue the admin stylesheet.
    wp_enqueue_style('maxiblocks-admin-styles', $admin_css_url, array(), '1.6.0');
}

add_action('admin_enqueue_scripts', 'maxiblocks_enqueue_admin_styles');

function maxiblocks_custom_theme_css()
{
    $custom_css = get_theme_mod('maxiblocks_custom_theme_css');
    wp_add_inline_style('maxiblocks-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'maxiblocks_custom_theme_css');
