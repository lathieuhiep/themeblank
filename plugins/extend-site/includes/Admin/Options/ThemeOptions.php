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
use ExtendSite\Admin\Options\Modules\WooOptions;
use ExtendSite\Admin\Options\Modules\WooSingleOptions;

defined('ABSPATH') || exit;

class ThemeOptions
{
    // Khởi tạo hook để đăng ký các tùy chọn
    public static function boot(): void
    {
        add_action('carbon_fields_register_fields', [self::class, 'register']);
    }

    // Đăng ký các tùy chọn chủ đề
    public static function register(): void
    {
        // 1. Định nghĩa cấu trúc phân nhóm
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

        // 2. Thêm nhóm WooCommerce nếu tồn tại
        if (class_exists('WooCommerce')) {
            $groups['woo'] = [
                'title' => esc_html__('Shop Settings', 'extend-site'),
                'tabs'  => [
                    ['label' => esc_html__('WooCommerce', 'extend-site'), 'class' => WooOptions::class],
                    ['label' => esc_html__('Product Detail', 'extend-site'), 'class' => WooSingleOptions::class],
                ]
            ];
        }

        // 3. Nhóm các phần bổ trợ (Footer, Social, Code)
        $groups['extra'] = [
            'title' => esc_html__('Extra Options', 'extend-site'),
            'tabs'  => [
                ['label' => esc_html__('Social Links', 'extend-site'), 'class' => SocialLinkOptions::class],
                ['label' => esc_html__('Footer', 'extend-site'), 'class' => FooterOptions::class],
                ['label' => esc_html__('Copyright', 'extend-site'), 'class' => CopyrightOptions::class],
                ['label' => esc_html__('Insert Code', 'extend-site'), 'class' => InsertCodeOptions::class],
            ]
        ];

        // 4. Đăng ký các Container
        $main_container = null;

        foreach ($groups as $id => $group) {
            $container = Container::make('theme_options', $group['title']);

            // Chỉ container đầu tiên (Theme Settings) là có Icon và Menu Position
            if ($id === 'general') {
                $container->set_icon('dashicons-admin-generic')
                    ->set_page_menu_position(3);
                $main_container = $container; // Lưu lại để làm cha cho các container sau
            } else {
                // Các nhóm sau sẽ biến thành Sub-menu của nhóm đầu tiên
                $container->set_page_parent($main_container);
            }

            // Add các tab thuộc nhóm này
            foreach ($group['tabs'] as $tab) {
                $class = $tab['class'];
                if (class_exists($class)) {
                    $container->add_tab($tab['label'], $class::fields());
                }
            }
        }
    }
}
