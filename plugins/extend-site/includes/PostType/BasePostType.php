<?php

namespace ExtendSite\PostType;

defined('ABSPATH') || exit;

abstract class BasePostType
{
    protected static array $registry = [];
    protected static array $templates = [];

    // Định nghĩa hằng số cơ bản cho Post Type
    public const SLUG = '';
    public const SINGULAR = '';
    public const PLURAL = '';

    // Tên menu trong admin, mặc định rỗng
    public const MENU_NAME = '';

    // Đăng ký template mapping mặc định rỗng
    public const TEMPLATE_MAP = [];

    protected array $args = [];

    public function __construct(array $args = [])
    {
        // Kiểm tra nếu chưa định nghĩa SLUG thì thoát để tránh lỗi
        if (empty(static::SLUG)) {
            return;
        }

        $this->args = $args;

        // Quy trình khởi tạo tập trung
        add_action('init', [$this, 'boot_post_type']);

        if (did_action('init')) {
            $this->boot_post_type();
        }

        // Đăng ký thông tin vào Registry
        self::$registry[static::SLUG] = [
            'slug' => static::SLUG,
            'singular' => static::SINGULAR,
            'plural' => static::PLURAL,
            'class' => static::class,
        ];

        // Tự động load template mapping từ mảng TEMPLATE_MAP
        $map = static::TEMPLATE_MAP;
        if (!empty($map)) {
            foreach ($map as $key => $template_file) {
                if (in_array($key, ['single', 'archive'])) {
                    // Lưu cho Post Type
                    self::$templates[static::SLUG][$key] = $template_file;
                } else {
                    // Mặc định các key khác là Taxonomy Slug
                    self::$templates[static::SLUG][$key] = $template_file;
                }
            }
        }
    }

    /**
     * Quy trình khởi tạo: Luôn ưu tiên Taxonomy trước CPT
     */
    public function boot_post_type(): void
    {
        $this->register_taxonomies();
        $this->register_ctp();
    }

    /**
     * Lớp con sẽ ghi đè để đăng ký các Taxonomy
     */
    protected function register_taxonomies(): void
    {
        // Để trống cho lớp con override
    }

    /**
     * Cho phép lớp con ghi đè các label cần dịch thuật
     */
    protected function get_custom_labels(): array
    {
        return [];
    }

    /**
     * Cho phép lớp con ghi đè args đăng ký post type.
     */
    protected function get_args(): array
    {
        return [];
    }

    /**
     * Đăng ký Custom Post Type chung
     */
    public function register_ctp(): void
    {
        /// Lấy các label tùy chỉnh từ lớp con
        $custom_labels = $this->get_custom_labels();

        $labels = [
            'name' => $custom_labels['name'] ?? static::PLURAL,
            'singular_name' => $custom_labels['singular_name'] ?? static::SINGULAR,
            'menu_name' => $custom_labels['menu_name'] ?? (!empty(static::MENU_NAME) ? static::MENU_NAME : static::PLURAL),
            'all_items' => esc_html__('Tất cả', 'extend-site'),
            'add_new_item' => esc_html__('Thêm mới', 'extend-site'),
            'edit_item' => esc_html__('Chỉnh sửa', 'extend-site'),
            'view_item' => esc_html__('Xem', 'extend-site'),
            'search_items' => esc_html__('Tìm kiếm', 'extend-site'),
            'not_found' => esc_html__('Không tìm thấy kết quả phù hợp', 'extend-site'),
        ];

        $default_args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true, // Quan trọng để hiện ở trang quản lý Menu
            'rewrite' => ['slug' => static::SLUG, 'with_front' => false],
            'menu_icon' => 'dashicons-admin-post',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        ];

        register_post_type(static::SLUG, array_replace_recursive($default_args, $this->get_args(), $this->args));
    }

    /**
     * Đăng ký taxonomy chung
     */
    protected function register_taxonomy(string $tax_slug, string $singular, string $plural, array $args = []): void
    {
        $labels = [
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
        ];

        $defaults = [
            'labels' => $labels,
            'public' => true,
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'rewrite' => ['slug' => $tax_slug, 'with_front' => false],
        ];

        // array_replace_recursive giúp ghi đè labels sâu bên trong $args
        register_taxonomy($tax_slug, static::SLUG, array_replace_recursive($defaults, $args));
    }

    /**
     * Lấy danh sách Post Type đã đăng ký
     */
    public static function get_registered_post_types(): array
    {
        return self::$registry;
    }

    /**
     * Lấy danh sách template đã đăng ký
     */
    public static function get_templates(): array
    {
        return self::$templates;
    }
}
