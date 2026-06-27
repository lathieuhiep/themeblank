<?php
/**
 * Include the TGM_Plugin_Activation class.
 */

add_action( 'tgmpa_register', 'themeblank_register_required_plugins' );
function themeblank_register_required_plugins(): void {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$themeblank_plugins = array(
		// This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name'      =>  'Elementor',
			'slug'      =>  'elementor',
			'required'  =>  true,
		),

		// This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name'      =>  'Contact Form 7',
			'slug'      =>  'contact-form-7',
			'required'  =>  false,
		),

        // This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name'      =>  'Loco Translate',
			'slug'      =>  'loco-translate',
			'required'  =>  false,
		),

	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$themeblank_config = array(
		'id'           => 'themeblank',          // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $themeblank_plugins, $themeblank_config );
}
