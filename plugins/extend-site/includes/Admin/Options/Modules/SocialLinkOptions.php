<?php
namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;
use ExtendSite\Constants\Social;

defined('ABSPATH') || exit;

class SocialLinkOptions extends OptionBase implements OptionIF
{
    // key options
    private const KEY = 'es_opt_social_';
    private const SOCIAL_LINKS = self::KEY . 'links';

    /**
     * fields
     */
    public static function fields(): array
    {
        $max = count(Social::list());

        return [
            // Social Links
            Field::make('complex', self::SOCIAL_LINKS, esc_html__('Social Links', 'extend-site'))
                ->set_layout('tabbed-vertical')
                ->set_max($max)
                ->add_fields([
                    Field::make('select', 'network', esc_html__('Network', 'extend-site'))
                        ->set_options(Social::list()),
                    Field::make('text', 'url', esc_html__('URL', 'extend-site')),
                ])
        ];
    }

    /**
     * get data
     */

    // get social list
    public static function get_social_list()
    {
        $value = self::get(self::SOCIAL_LINKS);

        return !empty($value) ? $value : [];
    }

    // get all options
    public static function get_all(): array
    {
        return [
            'social_links' => self::get_social_list()
        ];
    }
}