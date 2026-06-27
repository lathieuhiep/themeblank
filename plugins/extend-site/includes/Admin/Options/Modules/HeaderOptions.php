<?php

namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;

defined('ABSPATH') || exit;

class HeaderOptions extends OptionBase implements OptionIF
{

    // key options
    private const PREFIX = 'es_opt_header_';
    private const POSITION_FIXED_MENU = self::PREFIX . 'position_fixed_menu';

    /**
     * fields
     */
    public static function fields(): array
    {
        return [
            // Display back to top
            Field::make('checkbox', self::POSITION_FIXED_MENU, esc_html__('Enable Position Fixed Menu', 'extend-site'))
                ->set_option_value('yes')
                ->set_default_value('yes'),
        ];
    }

    /**
     * get data
     */

    // get position fixed menu
    public static function get_position_fixed_menu(): bool
    {
        return (bool)self::get(self::POSITION_FIXED_MENU, true);
    }

    // get all data
    public static function get_all(): array
    {
        return [
            'position_fixed_menu' => self::get_position_fixed_menu(),
        ];
    }
}
