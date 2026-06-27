<?php

namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;
use ExtendSite\Constants\Breakpoints;

defined('ABSPATH') || exit;

class FooterOptions extends OptionBase implements OptionIF
{

    // Key prefix
    private const PREFIX = 'es_opt_footer_';
    private const SIDEBAR_COLUMNS = self::PREFIX . 'columns';
    private const BREAKPOINT_HEADING = self::PREFIX . 'breakpoint_heading_sidebar_';
    private const COLUMNS_PREFIX = self::PREFIX . 'sidebar_column_';

    /**
     * fields
     */
    public static function fields(): array
    {
        $fields = [];

        // sidebar columns count
        $fields[] = Field::make('select', self::SIDEBAR_COLUMNS, esc_html__('Sidebar Columns Count', 'extend-site'))
            ->set_options([
                '0' => esc_html__('Hide all sidebars', 'extend-site'),
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
            ])->set_default_value('4');

        // breakpoint heading for sidebar 1
        $fields[] = Field::make('html', self::BREAKPOINT_HEADING . '1')
            ->set_html('<h4>' . esc_html__('Sidebar Column 1 Settings', 'extend-site') . '</h4>')
            ->set_conditional_logic([
                [
                    'field' => self::SIDEBAR_COLUMNS,
                    'value' => ['1', '2', '3', '4'],
                    'compare' => 'IN',
                ]
            ]);

        foreach (Breakpoints::map() as $key => $minWidth) {
            $fields[] = Field::make(
                'text',
                self::COLUMNS_PREFIX . '1_' . $key,
                esc_html__(strtoupper($key) . ': ≥' . $minWidth . 'px', 'extend-site')
            )
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1)
                ->set_attribute('max', 12)
                ->set_attribute('step', 1)
                ->set_default_value(Breakpoints::default_sidebar_col($key))
                ->set_width(25)
                ->set_conditional_logic([
                    [
                        'field' => self::SIDEBAR_COLUMNS,
                        'value' => ['1', '2', '3', '4'],
                        'compare' => 'IN',
                    ]
                ]);
        }

        // breakpoint heading for sidebar 2
        $fields[] = Field::make('html', self::BREAKPOINT_HEADING . '2')
            ->set_html('<h4>' . esc_html__('Sidebar Column 2 Settings', 'extend-site') . '</h4>')
            ->set_conditional_logic([
                [
                    'field' => self::SIDEBAR_COLUMNS,
                    'value' => ['2', '3', '4'],
                    'compare' => 'IN',
                ]
            ]);

        foreach (Breakpoints::map() as $key => $minWidth) {
            $fields[] = Field::make(
                'text',
                self::COLUMNS_PREFIX . '2_' . $key,
                esc_html__(strtoupper($key) . ': ≥' . $minWidth . 'px', 'extend-site')
            )
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1)
                ->set_attribute('max', 12)
                ->set_attribute('step', 1)
                ->set_default_value(Breakpoints::default_sidebar_col($key))
                ->set_width(25)
                ->set_conditional_logic([
                    [
                        'field' => self::SIDEBAR_COLUMNS,
                        'value' => ['2', '3', '4'],
                        'compare' => 'IN',
                    ]
                ]);
        }

        // breakpoint heading for sidebar 3
        $fields[] = Field::make('html', self::BREAKPOINT_HEADING . '3')
            ->set_html('<h4>' . esc_html__('Sidebar Column 3 Settings', 'extend-site') . '</h4>')
            ->set_conditional_logic([
                [
                    'field' => self::SIDEBAR_COLUMNS,
                    'value' => ['3', '4'],
                    'compare' => 'IN',
                ]
            ]);

        foreach (Breakpoints::map() as $key => $minWidth) {
            $fields[] = Field::make(
                'text',
                self::COLUMNS_PREFIX . '3_' . $key,
                esc_html__(strtoupper($key) . ': ≥' . $minWidth . 'px', 'extend-site')
            )
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1)
                ->set_attribute('max', 12)
                ->set_attribute('step', 1)
                ->set_default_value(Breakpoints::default_sidebar_col($key))
                ->set_width(25)
                ->set_conditional_logic([
                    [
                        'field' => self::SIDEBAR_COLUMNS,
                        'value' => ['3', '4'],
                        'compare' => 'IN',
                    ]
                ]);
        }

        // breakpoint heading for sidebar 4
        $fields[] = Field::make('html', self::BREAKPOINT_HEADING . '4')
            ->set_html('<h4>' . esc_html__('Sidebar Column 4 Settings', 'extend-site') . '</h4>')
            ->set_conditional_logic([
                [
                    'field' => self::SIDEBAR_COLUMNS,
                    'value' => ['4'],
                    'compare' => 'IN',
                ]
            ]);

        foreach (Breakpoints::map() as $key => $minWidth) {
            $fields[] = Field::make(
                'text',
                self::COLUMNS_PREFIX . '4_' . $key,
                esc_html__(strtoupper($key) . ': ≥' . $minWidth . 'px', 'extend-site')
            )
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1)
                ->set_attribute('max', 12)
                ->set_attribute('step', 1)
                ->set_default_value(Breakpoints::default_sidebar_col($key))
                ->set_width(25)
                ->set_conditional_logic([
                    [
                        'field' => self::SIDEBAR_COLUMNS,
                        'value' => ['4'],
                        'compare' => 'IN',
                    ]
                ]);
        }

        return $fields;
    }

    /**
     * get data
     */

    // get footer sidebar columns count
    public static function get_footer_sidebar_columns_count(int $default = 4): int
    {
        $value = (int)self::get(self::SIDEBAR_COLUMNS, $default);

        return $value > 0 ? $value : $default;
    }

    // get footer sidebar settings
    public static function get_footer_sidebar_settings(int $column): array
    {
        $columns = [];

        foreach (Breakpoints::map() as $key => $minWidth) {
            $columns[$key] = (int)self::get(self::COLUMNS_PREFIX . $column . '_' . $key, Breakpoints::default_sidebar_col($key));
        }

        return $columns;
    }

    // get all options
    public static function get_all(): array
    {
        return [
            'footer_sidebar_columns_count' => self::get_footer_sidebar_columns_count(),
            'footer_sidebar_settings' => [
                1 => self::get_footer_sidebar_settings(1),
                2 => self::get_footer_sidebar_settings(2),
                3 => self::get_footer_sidebar_settings(3),
                4 => self::get_footer_sidebar_settings(4),
            ],
        ];
    }
}
