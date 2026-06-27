<?php
namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;
use ExtendSite\Constants\Breakpoints;
use ExtendSite\Constants\Layout;

defined('ABSPATH') || exit;

class WooOptions extends OptionBase implements OptionIF
{
    // Key prefix
    private const PREFIX = 'es_woo_products_';
    private const SIDEBAR_POSITION = self::PREFIX . 'sidebar_position';
    private const PER_PAGE = self::PREFIX . 'per_page';
    private const COLUMNS_PREFIX = self::PREFIX . 'col_';

    /**
     * fields
     */
    public static function fields(): array
    {
        $fields = [];

        // Product sidebar position
        $fields[] = Field::make('select', self::SIDEBAR_POSITION, esc_html__('Sidebar Layout', 'extend-site'))
            ->set_options(Layout::sidebar_options())
            ->set_default_value(Layout::SIDEBAR_RIGHT);

        // Products per page
        $fields[] = Field::make('text', self::PER_PAGE, esc_html__('Products Per Page', 'extend-site'))
            ->set_attribute('type', 'number')
            ->set_attribute('min', 1)
            ->set_attribute('max', 20)
            ->set_attribute('step', 1)
            ->set_default_value(12)
            ->set_width(30);

        // Breakpoint Heading
        $fields[] = Field::make('html', self::PREFIX . 'breakpoint_heading')
            ->set_html('<h4>' . esc_html__('Product Grid Columns per Breakpoint', 'extend-site') . '</h4>');

        // Columns per breakpoint
        foreach (Breakpoints::map() as $key => $minWidth) {
            $fields[] = Field::make(
                'text',
                self::COLUMNS_PREFIX . $key,
                esc_html__(strtoupper($key) . ': ≥' . $minWidth . 'px', 'extend-site')
            )
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1)
                ->set_attribute('max', 12)
                ->set_attribute('step', 1)
                ->set_default_value( Breakpoints::default_col($key) )
                ->set_width(25);
        }

        return $fields;
    }

    /**
     * get data
     */

    // get sidebar position
    public static function get_products_sidebar_position(string $default = Layout::SIDEBAR_RIGHT): string
    {
        $value = self::get(self::SIDEBAR_POSITION, $default);
        return !empty($value) ? $value : $default;
    }

    // get per page
    public static function get_products_per_page(int $default = 12): int
    {
        $value = (int)self::get(self::PER_PAGE, $default);
        return $value > 0 ? $value : $default;
    }

    // get row columns
    public static function get_product_row_columns(): array
    {
        $columns = [];

        foreach (Breakpoints::map() as $key => $minWidth) {
            $columns[$key] = (int) self::get(self::COLUMNS_PREFIX . $key, Breakpoints::default_col($key));
        }

        return $columns;
    }

    // get all options
    public static function get_all(): array
    {
        return [
            'products_sidebar_position' => self::get_products_sidebar_position(),
            'products_per_page' => self::get_products_per_page(),
            'product_row_columns' => self::get_product_row_columns()
        ];
    }
}
