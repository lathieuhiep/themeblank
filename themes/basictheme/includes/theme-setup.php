<?php
// Setup Theme

function basictheme_setup(): void {
	// Set the content width based on the theme's design and stylesheet.
	global $content_width;

	if ( ! isset( $content_width ) ) {
		$content_width = 1200;
	}

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'basictheme', get_parent_theme_file_path( '/languages' ) );

	/**
	 * Set up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support post thumbnails.
	 *
	 */
	add_theme_support( 'custom-header' );

	add_theme_support( 'custom-background' );

	//Enable support for Post Thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Menu chính', 'basictheme' ),
		)
	);

	// add theme support title-tag
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'basictheme_setup' );