<?php

namespace ExtendSite\Admin\Options;

use Carbon_Fields\Container;
use ExtendSite\Admin\Options\Modules\ContactOptions;
use ExtendSite\Admin\Options\Modules\CopyrightOptions;
use ExtendSite\Admin\Options\Modules\FooterOptions;
use ExtendSite\Admin\Options\Modules\GeneralOptions;
use ExtendSite\Admin\Options\Modules\HeaderOptions;
use ExtendSite\Admin\Options\Modules\InsertCodeOptions;
use ExtendSite\Admin\Options\Modules\PostArchiveOptions;
use ExtendSite\Admin\Options\Modules\SinglePostOptions;
use ExtendSite\Admin\Options\Modules\SocialLinkOptions;

defined('ABSPATH') || exit;

class ThemeOptions
{
    // Khá»Ÿi táº¡o hook Ä‘á»ƒ Ä‘Äƒng kÃ½ cÃ¡c tÃ¹y chá»n
    public static function boot(): void
    {
        add_action('carbon_fields_register_fields', [self::class, 'register']);
    }

    // ÄÄƒng kÃ½ cÃ¡c tÃ¹y chá»n chá»§ Ä‘á»
    public static function register(): void
    {
        // 1. Äá»‹nh nghÄ©a cáº¥u trÃºc phÃ¢n nhÃ³m
        $groups = [
            'general' => [
                'title' => esc_html__('Theme Settings', 'extend-site'),
                'tabs'  => [
                    ['label' => esc_html__('General', 'extend-site'), 'class' => GeneralOptions::class],
                    ['label' => esc_html__('Header', 'extend-site'), 'class' => HeaderOptions::class],
                    ['label' => esc_html__('Contact', 'extend-site'), 'class' => ContactOptions::class],
                ]
            ],
            'blog' => [
                'title' => esc_html__('Blog Settings', 'extend-site'),
                'tabs'  => [
                    ['label' => esc_html__('Archive', 'extend-site'), 'class' => PostArchiveOptions::class],
                    ['label' => esc_html__('Single Post', 'extend-site'), 'class' => SinglePostOptions::class],
                ]
            ]
        ];

        // 3. NhÃ³m cÃ¡c pháº§n bá»• trá»£ (Footer, Social, Code)
        $groups['extra'] = [
            'title' => esc_html__('Extra Options', 'extend-site'),
            'tabs'  => [
                ['label' => esc_html__('Social Links', 'extend-site'), 'class' => SocialLinkOptions::class],
                ['label' => esc_html__('Footer', 'extend-site'), 'class' => FooterOptions::class],
                ['label' => esc_html__('Copyright', 'extend-site'), 'class' => CopyrightOptions::class],
                ['label' => esc_html__('Insert Code', 'extend-site'), 'class' => InsertCodeOptions::class],
            ]
        ];

        // 4. ÄÄƒng kÃ½ cÃ¡c Container
        $main_container = null;

        foreach ($groups as $id => $group) {
            $container = Container::make('theme_options', $group['title']);

            // Chá»‰ container Ä‘áº§u tiÃªn (Theme Settings) lÃ  cÃ³ Icon vÃ  Menu Position
            if ($id === 'general') {
                $container->set_icon('dashicons-admin-generic')
                    ->set_page_menu_position(3);
                $main_container = $container; // LÆ°u láº¡i Ä‘á»ƒ lÃ m cha cho cÃ¡c container sau
            } else {
                // CÃ¡c nhÃ³m sau sáº½ biáº¿n thÃ nh Sub-menu cá»§a nhÃ³m Ä‘áº§u tiÃªn
                $container->set_page_parent($main_container);
            }

            // Add cÃ¡c tab thuá»™c nhÃ³m nÃ y
            foreach ($group['tabs'] as $tab) {
                $class = $tab['class'];
                if (class_exists($class)) {
                    $container->add_tab($tab['label'], $class::fields());
                }
            }
        }
    }
}
