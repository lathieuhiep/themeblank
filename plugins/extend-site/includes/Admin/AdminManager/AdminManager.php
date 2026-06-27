<?php

namespace ExtendSite\Admin\AdminManager;

defined('ABSPATH') || exit;

/**
 * Kernel điều phối toàn bộ Admin Menu Framework
 */
final class AdminManager
{
    /**
     * Boot admin framework
     */
    public static function boot(): void
    {
        add_action('admin_menu', [self::class, 'register']);
    }

    public static function register(): void
    {
        $modules = self::get_modules();

        if (empty($modules)) {
            return;
        }

        self::register_main_menu();
        self::boot_modules($modules);
    }

    /**
     * Register main menu "Extend Site"
     */
    public static function register_main_menu(): void
    {
        add_menu_page(
            esc_html__('Extend Site Framework', 'extend-site'),
            esc_html__('Extend Site', 'extend-site'),
            AdminConstants::CAPABILITY_MANAGE,
            AdminConstants::MENU_PARENT,
            '__return_null',
            'dashicons-superhero',
            65
        );
    }

    /**
     * Boot all admin modules
     */
    protected static function boot_modules(array $modules): void
    {
        foreach ($modules as $module) {
            if ($module instanceof BaseAdminModule) {
                $module->boot();
            }
        }
    }

    /**
     * Danh sách các module admin
     */
    protected static function get_modules(): array
    {
        return [
            // new SeoAdmin(),
            // new SchemaAdmin(),
        ];
    }
}
