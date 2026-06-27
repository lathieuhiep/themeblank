<?php
namespace ExtendSite\Admin\Fields;

interface FieldTabIF
{
    /**
     * Trả về mảng định nghĩa các field cho Carbon Fields (Admin)
     */
    public static function fields(): array;

    /**
     * Trả về mảng dữ liệu đã nạp và xử lý (Frontend)
     */
    public static function get_data(int $post_id): array;
}