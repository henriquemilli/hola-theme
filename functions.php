<?php
/**
 * Theme functions and definitions
 *
 * @package holaElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( is_admin() ) {
	include_once 'settings.php'; //settings page
}

define( 'HOLA_ELEMENTOR_VERSION', wp_get_theme('hola-theme')->get('Version') );


/**
 * Updater
 */
require 'updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/henriquemilli/hola-theme',
	__FILE__,
	'hola-theme'
);
$myUpdateChecker->setBranch('master');
//$myUpdateChecker->setAuthentication('your-token-here');



if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}



if ( ! function_exists( 'hola_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hola_elementor_setup() {
		$hook_result = apply_filters_deprecated( 'elementor_hola_theme_load_textdomain', [ true ], '2.0', 'hola_elementor_load_textdomain' );
		if ( apply_filters( 'hola_elementor_load_textdomain', $hook_result ) ) {
			load_theme_textdomain( 'hola-elementor', get_template_directory() . '/languages' );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hola_theme_register_menus', [ true ], '2.0', 'hola_elementor_register_menus' );
		if ( apply_filters( 'hola_elementor_register_menus', $hook_result ) ) {
			register_nav_menus( array( 'menu-1' => __( 'Primary', 'hola-elementor' ) ) );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hola_theme_add_theme_support', [ true ], '2.0', 'hola_elementor_add_theme_support' );
		if ( apply_filters( 'hola_elementor_add_theme_support', $hook_result ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				)
			);
			add_theme_support(
				'custom-logo',
				array(
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				)
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'editor-style.css' );
			
			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			$hook_result = apply_filters_deprecated( 'elementor_hola_theme_add_woocommerce_support', [ true ], '2.0', 'hola_elementor_add_woocommerce_support' );
			if ( apply_filters( 'hola_elementor_add_woocommerce_support', $hook_result ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hola_elementor_setup' );


if ( ! function_exists( 'hola_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hola_elementor_scripts_styles() {
		
		if ( get_option( 'webp' ) ) {
			wp_enqueue_script(
				'hola_elementor_webp_polyfill',
				get_template_directory_uri() . '/assets/js/mod/' . 'webp-injector.min.js',
				[],
				HOLA_ELEMENTOR_VERSION,
				true //in footer
			);
		}
		
	}
}
add_action( 'wp_enqueue_scripts', 'hola_elementor_scripts_styles' );


/**
 * filter woo styles
 */
if ( get_option( 'no_woo_style' ) ) {
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
}


/**
 * filter elementor google fonts
 */
if ( get_option( 'no_e_google_fonts' ) ) {
	add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
}


/**
 * filter elementor font awesome
 */
function hola_filter_font_awesome() {
	if ( get_option( 'no_e_font_awesome' ) ) {
		foreach( [ 'solid', 'regular', 'brands' ] as $style ) {
			wp_deregister_style( 'elementor-icons-fa-' . $style );
		}
	}
} add_action('elementor/frontend/after_register_styles', 'hola_filter_font_awesome', 20 );


/**
 * dequeue elementor eicons
 */
function hola_remove_eicons() {
	if ( get_option( 'no_e_eicons' ) ) {
		wp_dequeue_style( 'elementor-icons' );
	}
} add_action( 'elementor/frontend/after_enqueue_styles', 'hola_remove_eicons', 20 ); 


/**
 * Permit SVG and webp files upload
 */
function add_file_types_to_uploads ( $file_types ) {
	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg';
	$new_filetypes['webp'] = 'image/webp';
	

	$file_types = array_merge( $file_types, $new_filetypes );

	return $file_types;
}
add_filter( 'upload_mimes', 'add_file_types_to_uploads' );


/**
 * Register Elementor Locations.
 *
 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
 *
 * @return void
 */
if ( ! function_exists( 'hola_elementor_register_elementor_locations' ) ) {
	function hola_elementor_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'elementor_hola_theme_register_elementor_locations', [ true ], '2.0', 'hola_elementor_register_elementor_locations' );
		if ( apply_filters( 'hola_elementor_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
} add_action( 'elementor/theme/register_locations', 'hola_elementor_register_elementor_locations' );


/**
 * Set default content width.
 *
 * @return void
 */
if ( ! function_exists( 'hola_elementor_content_width' ) ) {
	function hola_elementor_content_width() {
		$defaultS['content_width'] = apply_filters( 'hola_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hola_elementor_content_width', 0 );


/**
 * Check hide title.
 *
 * @param bool $val default value.
 *
 * @return bool
 */
if ( ! function_exists( 'hola_elementor_check_hide_title' ) ) {
	function hola_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = \Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hola_elementor_page_title', 'hola_elementor_check_hide_title' );


/**
 * Wrapper function to deal with backwards compatibility.
 */
if ( ! function_exists( 'hola_elementor_body_open' ) ) {
	function hola_elementor_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}