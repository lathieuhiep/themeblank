<?php
/*
 * Action
 * */

// optimize WordPress
use ExtendSite\Admin\Options\Modules\InsertCodeOptions;

function themeblank_optimize_wordpress(): void {
	// Disable WordPress Emoji
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'themeblank_disable_emojis_tinymce' );

	// Disable WordPress REST API links
	remove_action('wp_head', 'rest_output_link_wp_head');
	remove_action('template_redirect', 'rest_output_link_header', 11);

	// Disable RSD link and WLW manifest link
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');

	// Disable WordPress version
	remove_action('wp_head', 'wp_generator');
}

function themeblank_disable_emojis_tinymce( $plugins ): array {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

add_action('init', 'themeblank_optimize_wordpress');

// add code to head
function themeblank_custom_header_code(): void {
	$header_code = themeblank_opt(InsertCodeOptions::class)::get_head_code() ?? '';

	if ($header_code) {
		echo $header_code;
	}
}
add_action('wp_head', 'themeblank_custom_header_code');

// add code to body
function themeblank_custom_body_code(): void {
	$body_code = themeblank_opt(InsertCodeOptions::class)::get_after_body_code() ?? '';

	if ($body_code) {
		echo $body_code;
	}
}
add_action('wp_body_open', 'themeblank_custom_body_code');

// add code to footer
function themeblank_custom_footer_code(): void {
	$footer_code = themeblank_opt(InsertCodeOptions::class)::get_footer_code() ?? '';

	if ($footer_code) {
		echo $footer_code;
	}
}
add_action('wp_footer', 'themeblank_custom_footer_code');

/*
 * Filter
 * */

// disable WordPress xmlrpc
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Thêm thuộc tính preconnect cho Google Fonts
 */
function themeblank_partner_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = array(
            'href'       => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'themeblank_partner_resource_hints', 10, 2 );

/**
 * Chuyển đổi thẻ link phông chữ sang chuẩn Preload/Async
 */
function themeblank_partner_async_google_fonts( $tag, $handle, $src ) {
    // Chỉ áp dụng cho handle 'google-font' đã đăng ký
    if ( 'google-font' === $handle ) {
        $tag = '<link rel="preload" as="style" href="' . esc_url( $src ) . '" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        $tag .= '<noscript><link rel="stylesheet" href="' . esc_url( $src ) . '"></noscript>';
    }
    return $tag;
}
add_filter( 'style_loader_tag', 'themeblank_partner_async_google_fonts', 10, 3 );

// Walker for the main menu
add_filter( 'walker_nav_menu_start_el', 'themeblank_add_arrow',10,4);
function themeblank_add_arrow( $output, $item, $depth, $args ){
	if('primary' == $args->theme_location && $depth >= 0 ){
		if (in_array("menu-item-has-children", $item->classes)) {
			$output .='<span class="sub-menu-toggle icon-theme-mask"></span>';
		}
	}

	return $output;
}