<?php

namespace ExtendSite\Core;

use ExtendSite\Admin\AdminManager\AdminManager;
use ExtendSite\Admin\Options\ThemeOptions;
use ExtendSite\Constants\Config;
use ExtendSite\PostType\PostTypeManager;

defined('ABSPATH') || exit;

class Plugin
{
    public function boot(): void
    {
        // Load plugin text domain
        self::load_text_domain();

        // Load Admin Manager
        AdminManager::boot();

        // Load Carbon Fields
        CarbonLoader::boot();

        // Load Carbon Fields theme options
        ThemeOptions::boot();

        // Load asset enqueuing
        Enqueue::boot();

        // Load custom post types
        PostTypeManager::load();
    }

    /**
     * Load the plugin text domain for translations.
     */
    public static function load_text_domain(): void
    {
        load_plugin_textdomain(
            'extend-site',
            false,
            dirname(Config::$basename) . '/languages'
        );
    }
}
