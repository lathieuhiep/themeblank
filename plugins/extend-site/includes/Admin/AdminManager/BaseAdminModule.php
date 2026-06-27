<?php

namespace ExtendSite\Admin\AdminManager;

use ExtendSite\Constants\Config;

defined('ABSPATH') || exit;

/**
 * Base class for all admin modules of Extend Site plugin
 */
abstract class BaseAdminModule
{
    protected static array $option_keys = [];

    /**
     * Unique module key
     */
    abstract public function get_key(): string;

    /**
     * Title shown in admin menu
     */
    abstract public function get_title(): string;

    /**
     * Capability required to access this module
     */
    public function get_capability(): string
    {
        return AdminConstants::CAPABILITY_MANAGE;
    }

    /**
     * Admin page slug (used in URL)
     * Example: extend-site-admin-module
     */
    public function get_page_slug(): string
    {
        return AdminConstants::PAGE_PREFIX . $this->get_key();
    }

    /**
     * Option key used to store settings
     * Example: extend_site_admin_module
     */
    public function get_option_key(): string
    {
        return AdminConstants::OPTION_PREFIX . $this->get_key();
    }

    /**
     * Default option values
     */
    public function get_default_options(): array
    {
        return [];
    }

    /**
     * Entry point for admin module lifecycle
     */
    final public function boot(): void
    {
        $this->register_menu();
        $this->handle_request();
    }

    /**
     * Register submenu page under Extend Site menu
     */
    protected function register_menu(): void
    {
        add_submenu_page(
            AdminConstants::MENU_PARENT,
            $this->get_title(),
            $this->get_title(),
            $this->get_capability(),
            $this->get_page_slug(),
            [$this, 'render']
        );
    }

    /**
     * View name without extension
     * Example: module-view
     */
    abstract protected function get_view_name(): string;

    /**
     * Resolve absolute view path
     */
    final protected function resolve_view_path(): string
    {
        return Config::$path . AdminConstants::PATH_VIEWS . $this->get_view_name() . '.php';
    }

    /**
     * Get merged options (saved + default)
     */
    final public function get_options(): array
    {
        return wp_parse_args(
            get_option($this->get_option_key(), []),
            $this->get_default_options()
        );
    }

    /**
     * Get single option value by key
     */
    public function get_option(string $key, mixed $default = null): mixed
    {
        $options = $this->get_options();
        return $options[$key] ?? $default;
    }

    /**
     * Render admin page
     */
    final public function render(): void
    {
        // 1. Lấy dữ liệu hiện tại (Values)
        $options = $this->get_options();

        // 2. Tạo mảng Map tên field (Names)
        $fields = [];
        foreach (static::$option_keys as $key) {
            // Tự động nối module_key với option_key
            $fields[$key] = $this->get_key() . '_' . $key;
        }

        // 3. Chuẩn bị biến cho View
        $view_data = array_merge($options, [
            'fields' => $fields,
            'title' => $this->get_title(),
            'nonce_field' => wp_nonce_field($this->get_nonce_action(), '_wpnonce', true, false),
        ]);

        // Trích xuất mảng thành các biến độc lập
        extract($view_data);

        error_log( print_r( $options, true ) );

        $view = $this->resolve_view_path();

        if (is_readable($view)) {
            require $view;
        }
    }

    /**
     * Handle POST / save logic
     * Override in child module if needed
     */
    protected function handle_request(): void
    {
        // 1. Kiểm tra bảo mật (Nonce & Capability)
        if (empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], $this->get_nonce_action())) {
            return;
        }

        if (!current_user_can($this->get_capability())) {
            return;
        }

        $module_key = $this->get_key();
        $new_options = [];

        // 2. Tự động lặp qua các key đã khai báo ở lớp con
        foreach (static::$option_keys as $key) {
            $input_name = $module_key . '_' . $key;

            if (isset($_POST[$input_name])) {
                // Làm sạch dữ liệu (Có thể mở rộng thêm filter tại đây)
                $new_options[$key] = sanitize_text_field($_POST[$input_name]);
            } else {
                // Xử lý cho checkbox khi không được tích
                $new_options[$key] = false;
            }
        }

        // 3. Lưu vào Database
        update_option($this->get_option_key(), $new_options);

        // 4. Thông báo thành công (Tùy chọn)
        add_settings_error('extend_site_messages', 'settings_updated', esc_html__('Settings Saved', 'extend-site'), 'updated');
    }

    /**
     * Build nonce action name for this module
     */
    protected function get_nonce_action(): string
    {
        return AdminConstants::NONCE_PREFIX . $this->get_key();
    }
}
