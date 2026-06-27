<?php
namespace ExtendSite\PostType;

class PostTypeManager
{
    // Đánh dấu đã khởi tạo hay chưa
    private static bool $booted = false;

    /**
     * Danh sách các Class Post Type cần khởi tạo
     */
    protected static array $post_types = [
        // PortfolioPostType::class
    ];

    /**
     * Duyệt qua danh sách và kích hoạt từng cái
     */
    public static function load(): void {
        // 1. Chạy loader giao diện (chỉ cần chạy 1 lần duy nhất)
        if (!self::$booted) {
            TemplateLoader::boot();
            self::$booted = true;

            // Vẫn nên giữ do_action để các bên khác có thể hook vào nếu cần
            do_action('extend_site_template_loader_booted');
        }

        // 2. Khởi tạo các Post Type
        foreach (self::$post_types as $pt) {
            if (class_exists($pt)) {
                new $pt();
            }
        }
    }
}
