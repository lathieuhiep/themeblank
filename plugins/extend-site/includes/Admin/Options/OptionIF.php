<?php
namespace ExtendSite\Admin\Options;

interface OptionIF
{
    /**
     * Bắt buộc phải có để ThemeOptions.php có thể gọi và đăng ký Tab
     */
    public static function fields(): array;

    /**
     * Bắt buộc phải có để lấy tất cả dữ liệu trong module
     */
    public static function get_all(): array;
}