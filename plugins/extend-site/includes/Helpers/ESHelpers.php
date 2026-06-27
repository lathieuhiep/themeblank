<?php
namespace ExtendSite\Helpers;

defined('ABSPATH') || exit;

class ESHelpers
{
    /**
     * Kiểm tra xem Elementor có đang ở chế độ chỉnh sửa (edit mode) hay không.
     *
     * @return string|bool Meta value, hoặc false nếu không có.
     */
    public static function check_elementor_builder(): bool|string
    {
        return get_post_meta(get_the_ID(), '_elementor_edit_mode', true);
    }

    /**
     * Lấy danh sách các term (danh mục) của một taxonomy.
     *
     * @param string $taxonomy Tên của taxonomy (ví dụ: 'category', 'product_cat').
     * @return array ID => Tên của các term.
     */
    public static function get_tax_list(string $taxonomy): array
    {
        $options = [];

        if (!taxonomy_exists($taxonomy)) {
            return $options;
        }

        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);

        if (is_wp_error($terms) || empty($terms)) {
            return $options;
        }

        foreach ($terms as $t) {
            $options[$t->term_id] = $t->name;
        }

        return $options;
    }

    /**
     * Lấy danh sách các Contact Form 7.
     *
     * @return array ID => Tên của các form.
     */
    public static function get_form_cf7(): array
    {
        $options = array();

        if (function_exists('wpcf7')) {
            $wpcf7_form_list = get_posts(array(
                'post_type' => 'wpcf7_contact_form',
                'numberposts' => -1,
            ));

            $options[0] = esc_html__('Select a Contact Form', 'extend-site');

            if (!empty($wpcf7_form_list) && !is_wp_error($wpcf7_form_list)) :
                foreach ($wpcf7_form_list as $item) :
                    $options[$item->ID] = $item->post_title;
                endforeach;
            else :
                $options[0] = esc_html__('Create a Form First', 'extend-site');
            endif;
        }

        return $options;
    }

    /**
     * Hiển thị phân trang tiêu chuẩn của WordPress.
     *
     * @return void
     */
    public static function pagination(): void
    {
        the_posts_pagination(array(
            'type' => 'list',
            'mid_size' => 2,
            'prev_text' => '<i class="es-icon-mask es-icon-mask-angle-left"></i>',
            'next_text' => '<i class="es-icon-mask es-icon-mask-angle-right"></i>',
            'screen_reader_text' => '&nbsp;',
        ));
    }
}