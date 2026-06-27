<?php

namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;

defined('ABSPATH') || exit;

class ContactOptions extends OptionBase implements OptionIF
{
    // Key prefix
    private const KEY = 'es_otp_contact_';
    private const HOTLINE = self::KEY . 'hotline';
    private const EMAIL = self::KEY . 'email';
    private const ADDRESS = self::KEY . 'address';

    /**
     * fields
     */
    public static function fields(): array
    {
        return [
            // Contact
            Field::make('text', self::HOTLINE, esc_html__('Hotline', 'extend-site')),
            Field::make('text', self::EMAIL, esc_html__('Email', 'extend-site')),
            Field::make('textarea', self::ADDRESS, esc_html__('Address', 'extend-site')),
        ];
    }

    /**
     * get data
     */

    // get hotline
    public static function get_hotline(): string
    {
        return (string)self::get(self::HOTLINE);
    }

    // get email
    public static function get_email(): string
    {
        return (string)self::get(self::EMAIL);
    }

    // get address
    public static function get_address(): string
    {
        return (string)self::get(self::ADDRESS);
    }

    // get all options
    public static function get_all(): array
    {
        return [
            'hotline' => self::get_hotline(),
            'email' => self::get_email(),
            'address' => self::get_address(),
        ];
    }
}