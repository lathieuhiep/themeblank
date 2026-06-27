<?php

namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;
use ExtendSite\Constants\Breakpoints;
use ExtendSite\Constants\Layout;

defined('ABSPATH') || exit;

class WooSingleOptions extends OptionBase implements OptionIF
{
    // Key prefix
    private const PREFIX = 'es_woo_single_';
    private const SIDEBAR_POSITION = self::PREFIX . 'sidebar_position';
    private const COLUMNS_PREFIX = self::PREFIX . 'col_';
    private const RELATED_COUNT = self::PREFIX . 'related_count';

    /**
     * fields
     */
    public static function fields(): array
    {
        $fields = [];

        // sidebar position
        $fields[] = Field::make('select', self::SIDEBAR_POSITION, esc_html__('Sidebar Layout', 'extend-site'))
            ->set_options(Layout::sidebar_options())
            ->set_default_value(Layout::SIDEBAR_RIGHT);

        // related products
        $fields[] = Field::make('html', self::PREFIX . 'desc_related')
            ->set_html('<h4>' . esc_html__('Related Products', 'extend-site') . '</h4>');

        // related products count
        $fields[] = Field::make('text', self::RELATED_COUNT, esc_html__('Related Products Count', 'extend-site'))
            ->set_attribute('type', 'number')
            ->set_attribute('min', 1)
            ->set_attribute('max', 20)
            ->set_attribute('step', 1)
            ->set_default_value(3)
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
                ->set_default_value(Breakpoints::default_col($key))
                ->set_width(25);
        }

        return $fields;
    }

    // get sidebar position
    public static function get_product_single_sidebar_position(string $default = Layout::SIDEBAR_RIGHT): string
    {
        $value = self::get(self::SIDEBAR_POSITION, $default);
        return !empty($value) ? $value : $default;
    }

    /**
     * get data
     */

    // get per page
    public static function get_product_single_related_count(int $default = 3): int
    {
        $value = (int)self::get(self::RELATED_COUNT, $default);
        return $value > 0 ? $value : $default;
    }

    // get row columns
    public static function get_product_single_row_columns(): array
    {
        $columns = [];

        foreach (Breakpoints::map() as $key => $minWidth) {
            $columns[$key] = (int)self::get(self::COLUMNS_PREFIX . $key, Breakpoints::default_col($key));
        }

        return $columns;
    }

    // get all options
    public static function get_all(): array
    {
        return [
            'sidebar_position' => self::get_product_single_sidebar_position(),
            'related_count' => self::get_product_single_related_count(),
            'row_columns' => self::get_product_single_row_columns(),
        ];
    }
}
