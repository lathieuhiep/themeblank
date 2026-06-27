<?php
/**
 * Plugin Name:       Extend Site
 * Description:       Essential toolkit for WordPress: custom post types, widgets, and site extensions.
 * Version:           1.0.0
 * Author:            La Thieu Hiep
 * Text Domain:       extend-site
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Tested up to:      6.6
 * Requires PHP:      7.4
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

use ExtendSite\Constants\Config;
use ExtendSite\Core\Autoloader;
use ExtendSite\Core\Plugin;
use ExtendSite\PostType\PostTypeManager;

defined('ABSPATH') || exit;

/**
 * -----------------------------------------------------
 * Constants
 * -----------------------------------------------------
 */
define('EXTEND_SITE_ACTIVE', true);

/**
 * -----------------------------------------------------
 * Activation
 * -----------------------------------------------------
 */
register_activation_hook(__FILE__, static function () {
    require_once plugin_dir_path(__FILE__) . 'includes/Core/Autoloader.php';
    Autoloader::register();

    Config::init(__FILE__);
    PostTypeManager::load();

    flush_rewrite_rules();
});

/**
 * -----------------------------------------------------
 * Boot plugin after all plugins loaded
 * -----------------------------------------------------
 */
add_action('plugins_loaded', static function () {

    // Load autoloader
    require_once plugin_dir_path(__FILE__) . 'includes/Core/Autoloader.php';
    Autoloader::register();

    // Initialize config
    Config::init(__FILE__);

    // Boot main plugin kernel
    (new Plugin())->boot();
});
