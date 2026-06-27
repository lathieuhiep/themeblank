<?php

namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;

defined('ABSPATH') || exit;

class CopyrightOptions extends OptionBase implements OptionIF
{
    // Key prefix
    private const PREFIX = 'es_opt_copyright_';
    private const SHOW = self::PREFIX . 'show';
    private const CONTENT = self::PREFIX . 'content';

    /**
     * fields
     */
    public static function fields(): array
    {
        return [
            // Show copyright
            Field::make('checkbox', self::SHOW, esc_html__('Show Copyright', 'extend-site'))
                ->set_option_value('yes')
                ->set_default_value(true),

            // Content editor
            Field::make('rich_text', self::CONTENT, esc_html__('Content', 'extend-site'))
                ->set_default_value('Copyright &copy; DiepLK')
                ->set_conditional_logic([
                    [
                        'field' => self::SHOW,
                        'value' => true,
                        'compare' => '=',
                    ]
                ]),
        ];
    }

    /**
     * get data
     */

    // get show copyright
    public static function get_show_copyright(): bool
    {
        return self::get(self::SHOW, 'yes');
    }

    // get content copyright
    public static function get_content_copyright(): string
    {
        return (string)self::get(self::CONTENT);
    }

    // get all options
    public static function get_all(): array
    {
        return [
            'show_copyright' => self::get(self::SHOW, 'yes'),
            'content_copyright' => self::get(self::CONTENT),
        ];
    }
}