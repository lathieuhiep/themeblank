<?php
namespace ExtendSite\PostType;

use ExtendSite\Constants\Config;

defined('ABSPATH') || exit;

class TemplateLoader
{
    /**
     * Initialize the template loader by hooking into the 'template_include' filter.
     */
    public static function boot(): void {
        add_filter('template_include', [__CLASS__, 'pick'], 99);
    }

    /**
     * Generate a list of template candidates based on the basename.
     *
     * @param string $basename The base name of the template file.
     * @return array An array of candidate template paths.
     */
    private static function candidates(string $basename): array {
        return [
            'extend-site/' . $basename,
            $basename,
        ];
    }

    /**
     * Locate the template in the active theme or child theme.
     *
     * @param string $basename The base name of the template file.
     * @return string The path to the located template file, or an empty string if not found.
     */
    private static function locate_in_theme(string $basename): string {
        $t = locate_template(self::candidates($basename));
        return $t ?: '';
    }

    /**
     * Get the path to the plugin template file.
     *
     * @param string $basename The base name of the template file.
     * @return string The full path to the plugin template file.
     */
    private static function plugin_template(string $basename): string {
        return Config::$path . 'templates/' . $basename;
    }

    /**
     * Pick the appropriate template based on the current context.
     *
     * @param string $template The default template to use if no specific template is found.
     * @return string The path to the selected template file.
     */
    public static function pick(string $template): string {
        $registry = BasePostType::get_templates();

        foreach ($registry as $post_type => $template_files) {

            // 1. Kiểm tra Single (Trang chi tiết của Post Type)
            if (is_singular($post_type) && isset($template_files['single'])) {
                return self::resolve($template_files['single'], $template);
            }

            // 2. Kiểm tra Archive (Trang danh sách của Post Type)
            if (is_post_type_archive($post_type) && isset($template_files['archive'])) {
                return self::resolve($template_files['archive'], $template);
            }

            // 3. Kiểm tra Taxonomy linh hoạt
            // Chúng ta duyệt qua toàn bộ mảng template_files
            foreach ($template_files as $key => $file_name) {
                // Nếu key không phải single/archive, WordPress sẽ kiểm tra nó có phải là Taxonomy đang hiển thị không
                if (!in_array($key, ['single', 'archive']) && is_tax($key)) {
                    return self::resolve($file_name, $template);
                }
            }
        }

        return $template;
    }

    /**
     * Hàm bổ trợ để tách biệt logic tìm file
     */
    private static function resolve(string $file_name, string $default): string {
        $located = self::locate_in_theme($file_name);

        if ($located) {
            return $located;
        }

        $plugin_file = self::plugin_template($file_name);
        return file_exists($plugin_file) ? $plugin_file : $default;
    }
}