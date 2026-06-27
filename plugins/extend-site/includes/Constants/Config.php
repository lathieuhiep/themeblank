<?php

namespace ExtendSite\Constants;

defined('ABSPATH') || exit;

final class Config
{
    public const ACTIVE = true;
    public const VERSION = '1.0.0';

    // Định nghĩa các hằng số động
    public static string $file;
    public static string $path;
    public static string $url;
    public static string $basename;

    /**
     * Khởi tạo các hằng số động
     */
    public static function init(string $main_file): void
    {
        self::$file = $main_file;
        self::$path = plugin_dir_path($main_file);
        self::$url = plugin_dir_url($main_file);
        self::$basename = plugin_basename($main_file);
    }
}
