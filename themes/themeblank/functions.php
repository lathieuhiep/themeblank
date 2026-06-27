<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Required: Theme constants
require get_parent_theme_file_path( '/includes/theme-constants.php' );

// Required: Theme setup
require get_parent_theme_file_path( '/includes/theme-setup.php' );

// Required: Plugin Activation
require get_parent_theme_file_path( '/includes/class-tgm-plugin-activation.php' );
require get_parent_theme_file_path( '/includes/plugin-activation.php' );

// Required: Theme functions
require get_parent_theme_file_path( '/includes/theme-hooks.php' );
require get_parent_theme_file_path( '/includes/theme-functions.php' );
require get_parent_theme_file_path( '/includes/theme-scripts.php' );
require get_parent_theme_file_path( '/includes/theme-sidebar.php' );

// Required: Theme options
require get_theme_file_path( '/includes/theme-helper-options.php' );

// Required: Meta box options
require get_parent_theme_file_path( '/includes/theme-meta-box-options.php' );

// Required: Widgets
require get_parent_theme_file_path( '/includes/widgets/contact-info-widget.php' );
require get_parent_theme_file_path( '/includes/widgets/recent-post.php' );
require get_parent_theme_file_path( '/includes/widgets/social-widget.php' );

// Required: Woocommerce
if ( class_exists( 'Woocommerce' ) ) :
    require get_parent_theme_file_path( '/includes/woocommerce/woo-helpers.php' );
	require get_parent_theme_file_path( '/includes/woocommerce/woo-scripts.php' );
	require get_parent_theme_file_path( '/includes/woocommerce/woo-quick-view.php' );
	require get_parent_theme_file_path( '/includes/woocommerce/woo-template-hooks.php' );
	require get_parent_theme_file_path( '/includes/woocommerce/woo-template-functions.php' );
endif;

