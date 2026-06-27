<?php

namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;
use ExtendSite\Constants\Breakpoints;
use ExtendSite\Constants\Layout;

defined('ABSPATH') || exit;

class PostArchiveOptions extends OptionBase implements OptionIF
{
    // Key prefix
    private const PREFIX = 'es_opt_post_archive_';
    private const SIDEBAR_POSITION = self::PREFIX . 'sidebar_position';

    /**
     * fields
     */
    public static function fields(): array
    {
        $fields = [];

        // Heading
        $fields[] = Field::make('separator', self::PREFIX . 'heading', esc_html__('Post Archive', 'extend-site'));

        $fields[] = Field::make('html', self::PREFIX . 'desc')
            ->set_html('<p class="cf-subtext">' . esc_html__(
                    'Applied to category archives, general archives, index pages, and search results.',
                    'extend-site'
                ) . '</p>');

        // Sidebar
        $fields[] = Field::make('select', self::SIDEBAR_POSITION, esc_html__('Sidebar Layout', 'extend-site'))
            ->set_options(Layout::sidebar_options())
            ->set_default_value(Layout::SIDEBAR_RIGHT);

        // Breakpoint Heading
        $fields[] = Field::make('html', self::PREFIX . 'breakpoint_heading')
            ->set_html('<h4>' . esc_html__('Archive Grid Columns per Breakpoint', 'extend-site') . '</h4>');

        // Columns per breakpoint
        foreach (Breakpoints::map() as $key => $minWidth) {
            $fields[] = Field::make(
                'text',
                self::PREFIX . 'col_' . $key,
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

    /**
     * get data
     */

    // Read: sidebar
    public static function get_sidebar_layout_archive(string $default = Layout::SIDEBAR_RIGHT): string
    {
        $value = self::get(self::SIDEBAR_POSITION, $default);
        return !empty($value) ? $value : $default;
    }

    // Read: archive row columns
    public static function get_archive_row_columns(): array
    {
        $columns = [];

        foreach (Breakpoints::map() as $key => $minWidth) {
            $columns[$key] = (int)self::get(self::PREFIX . 'col_' . $key, Breakpoints::default_col($key));
        }

        return $columns;
    }

    // get all data
    public static function get_all(): array
    {
        return [
            'sidebar_layout' => self::get_sidebar_layout_archive(),
            'archive_row_columns' => self::get_archive_row_columns(),
        ];
    }
}