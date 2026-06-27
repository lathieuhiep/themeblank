<?php
/**
 * General Options
 *
 * @package ExtendSite
 */

namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;

defined('ABSPATH') || exit;

class GeneralOptions extends OptionBase implements OptionIF
{

    // key options
    private const PREFIX = 'es_opt_general_';
    private const LOGO = self::PREFIX . 'logo';
    private const ENABLE_LOADING = self::PREFIX . 'enable_loading';
    private const LOADING_IMAGE = self::PREFIX . 'loading_image';
    private const BACK_TO_TOP = self::PREFIX . 'back_to_top';

    /**
     * fields
     */
    public static function fields(): array
    {

        return [
            // Logo & Branding
            Field::make('image', self::LOGO, esc_html__('Logo', 'extend-site'))
                ->set_value_type('id')
                ->set_help_text('Select your logo'),

            // -----------------------------
            // Loading Page
            // -----------------------------
            Field::make('checkbox', self::ENABLE_LOADING, esc_html__('Enable Loading Page', 'extend-site'))
                ->set_option_value('yes'),

            Field::make('image', self::LOADING_IMAGE, esc_html__('Loading Image', 'extend-site'))
                ->set_help_text(__('Upload GIF/PNG for loading animation', 'extend-site'))
                ->set_conditional_logic([
                    [
                        'field' => self::ENABLE_LOADING,
                        'value' => true,
                    ]
                ]),

            // Display back to top
            Field::make('checkbox', self::BACK_TO_TOP, esc_html__('Enable back to Top', 'extend-site'))
                ->set_option_value('yes')
                ->set_default_value('yes'),
        ];
    }

    /**
     * get data
     */

    // get logo
    public static function get_logo_id($default = null)
    {
        $id = self::get(self::LOGO);

        return $id ?: $default;
    }

    // get display loading enabled
    public static function get_loading_enabled(): bool
    {
        return (bool)self::get(self::ENABLE_LOADING, false);
    }

    // get image loading
    public static function get_loading_image_id($default = null)
    {
        $id = self::get(self::LOADING_IMAGE);

        return $id ?: $default;
    }

    // get display back to top
    public static function get_back_to_top_enabled(): bool
    {
        return (bool)self::get(self::BACK_TO_TOP, true);
    }

    // get all options
    public static function get_all(): array
    {
        return [
            self::LOGO => self::get_logo_id(),
            self::ENABLE_LOADING => self::get_loading_enabled(),
            self::LOADING_IMAGE => self::get_loading_image_id(),
            self::BACK_TO_TOP => self::get_back_to_top_enabled(),
        ];
    }
}
