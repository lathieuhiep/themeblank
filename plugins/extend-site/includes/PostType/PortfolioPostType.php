<?php

namespace ExtendSite\PostType;

use ExtendSite\Admin\Fields\PortfolioFields;
use ExtendSite\Admin\Helpers\TaxFilter;

class PortfolioPostType extends BasePostType
{
    public const SLUG = 'portfolio';
    public const TAX_CATEGORY = 'portfolio_category';
    public const SINGULAR = 'Portfolio';
    public const PLURAL = 'Portfolios';
    public const MENU_NAME = 'Portfolios';

    // Đăng ký mọi template tại đây
    public const TEMPLATE_MAP = [
        'single' => 'portfolio/single-portfolio.php',
        'archive' => 'portfolio/archive-portfolio.php',
        self::TAX_CATEGORY => 'portfolio/taxonomy-portfolio-category.php',
    ];

    public function __construct(array $args = [])
    {
        parent::__construct($args);

        // Đăng ký bộ lọc admin nếu class tồn tại
        TaxFilter::register(self::SLUG, self::TAX_CATEGORY);

        // Đăng ký các field cho post type này
        add_action('carbon_fields_register_fields', [$this, 'register_fields']);
    }

    /**
     * Đăng ký Custom Post Type
     */
    protected function register_taxonomies(): void
    {
        $this->register_taxonomy(
            self::TAX_CATEGORY,
            esc_html__('Danh mục', 'extend-site'),        // singular_name
            esc_html__('Danh mục dự án', 'extend-site'), // name (Hiện ở Nav Menu)
            [
                'labels' => [
                    'menu_name' => esc_html__('Danh mục', 'extend-site'),
                ],
                'rewrite' => ['slug' => 'portfolio-category']
            ]
        );
    }

    /**
     * Ghi đè các label cần dịch thuật
     */
    protected function get_custom_labels(): array
    {
        return [
            'name' => esc_html__('Dự án', 'extend-site'),
            'singular_name' => esc_html__('Dự án', 'extend-site'),
            'menu_name' => esc_html__('Dự án', 'extend-site'),
        ];
    }

    /**
     * Override post type registration args.
     */
    protected function get_args(): array
    {
        return [
            'menu_icon' => 'dashicons-portfolio',
        ];
    }

    /**
     * Đăng ký các field cho post type này
     */
    public function register_fields(): void
    {
        if (class_exists(PortfolioFields::class)) {
            PortfolioFields::register(self::SLUG);
        }
    }
}
