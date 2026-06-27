<?php

namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;

class InsertCodeOptions extends OptionBase implements OptionIF
{
    private const PREFIX = 'es_opt_insert_code_';
    private const HEAD = self::PREFIX . 'head';
    private const AFTER_BODY = self::PREFIX . 'after_body';
    private const FOOTER = self::PREFIX . 'footer';

    /**
     * fields
     */
    public static function fields(): array
    {
        return [
            // Insert into <head>
            Field::make('header_scripts', self::HEAD, esc_html__('Insert into head', 'extend-site'))
                ->set_rows(10),

            // Insert after <body>
            Field::make('textarea', self::AFTER_BODY, esc_html__('Insert after body', 'extend-site'))
                ->set_rows(10),

            // Insert into footer
            Field::make('footer_scripts', self::FOOTER, esc_html__('Insert into footer', 'extend-site'))
                ->set_rows(10),
        ];
    }

    /**
     * get data
     */

    // get insert code head
    public static function get_head_code(): string
    {
        return self::get(self::HEAD, '');
    }

    // get insert code after body
    public static function get_after_body_code(): string
    {
        return self::get(self::AFTER_BODY, '');
    }

    // get insert code footer
    public static function get_footer_code(): string
    {
        return self::get(self::FOOTER, '');
    }

    // get all data
    public static function get_all(): array
    {
        return [
            'head_code' => self::get_head_code(),
            'after_body_code' => self::get_after_body_code(),
            'footer_code' => self::get_footer_code(),
        ];
    }
}
