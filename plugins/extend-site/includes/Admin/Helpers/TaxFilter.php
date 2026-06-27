<?php

namespace ExtendSite\Admin\Helpers;

defined('ABSPATH') || exit;

class TaxFilter
{
    /**
     * Lưu trữ danh sách các filter đã đăng ký
     * Cấu trúc: ['post_type' => ['taxonomy1' => 'label1', 'taxonomy2' => 'label2']]
     */
    private static array $registered_filters = [];

    /**
     * Khởi tạo filter.
     */
    public static function register(string $post_type, string $taxonomy, string $all_label = ''): void
    {
        // 1. Lưu thông tin filter vào mảng static
        self::$registered_filters[$post_type][$taxonomy] = $all_label;

        // 2. Chỉ đăng ký action một lần duy nhất
        if (!has_action('restrict_manage_posts', [self::class, 'render_filters'])) {
            add_action('restrict_manage_posts', [self::class, 'render_filters']);
            add_filter('parse_query', [self::class, 'apply_filters']);
        }
    }

    /**
     * Hiển thị các dropdown filter
     */
    public static function render_filters(): void
    {
        $screen = get_current_screen();
        if (!$screen || !isset(self::$registered_filters[$screen->post_type])) {
            return;
        }

        $taxonomies = self::$registered_filters[$screen->post_type];

        foreach ($taxonomies as $taxonomy => $all_label) {
            $terms = get_terms([
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
            ]);

            if (empty($terms) || is_wp_error($terms)) {
                continue;
            }

            $selected = isset($_GET[$taxonomy]) ? sanitize_text_field(wp_unslash($_GET[$taxonomy])) : '';
            $label    = $all_label !== '' ? $all_label : esc_html__('Tất cả danh mục', 'extend-site');
            ?>
            <select name="<?php echo esc_attr($taxonomy); ?>" class="postform">
                <option value=""><?php echo esc_html($label); ?></option>
                <?php foreach ($terms as $term): ?>
                    <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($selected, $term->slug); ?>>
                        <?php echo esc_html($term->name); ?> (<?php echo (int)$term->count; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php
        }
    }

    /**
     * Áp dụng filter vào query
     */
    public static function apply_filters($query): void
    {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $post_type = $query->get('post_type');

        // Kiểm tra xem CPT hiện tại có filter nào được đăng ký không
        if (isset(self::$registered_filters[$post_type])) {
            $taxonomies = self::$registered_filters[$post_type];

            foreach ($taxonomies as $taxonomy => $label) {
                if (!empty($_GET[$taxonomy])) {
                    $term = sanitize_text_field(wp_unslash($_GET[$taxonomy]));

                    $tax_query = (array) $query->get('tax_query');
                    $tax_query[] = [
                        'taxonomy' => $taxonomy,
                        'field'    => 'slug',
                        'terms'    => $term,
                    ];

                    $query->set('tax_query', $tax_query);
                }
            }
        }
    }
}