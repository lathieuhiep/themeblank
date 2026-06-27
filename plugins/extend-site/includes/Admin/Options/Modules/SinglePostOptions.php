<?php

namespace ExtendSite\Admin\Options\Modules;

use Carbon_Fields\Field;
use ExtendSite\Admin\Options\OptionBase;
use ExtendSite\Admin\Options\OptionIF;
use ExtendSite\Constants\Layout;
use ExtendSite\Constants\Toggle;

defined('ABSPATH') || exit;

class SinglePostOptions extends OptionBase implements OptionIF
{
    // key options
    private const PREFIX = 'es_opt_single_post_';

    private const SIDEBAR_POSITION = self::PREFIX . 'sidebar_position';
    private const SHOW_RELATED = self::PREFIX . 'show_related_posts';
    private const RELATED_COUNT = self::PREFIX . 'related_count';

    /**
     * fields
     */
    public static function fields(): array
    {
        return [
            Field::make('separator', self::PREFIX . 'heading', esc_html__('Single Post', 'extend-site')),

            // Sidebar position
            Field::make('select', self::SIDEBAR_POSITION, esc_html__('Sidebar Position', 'extend-site'))
                ->set_options(Layout::sidebar_options())
                ->set_default_value(Layout::SIDEBAR_RIGHT),

            // Related Posts
            Field::make( 'html', self::PREFIX . 'desc_related' )
                ->set_html( '<h4>'. esc_html__( 'Related Posts', 'extend-site' ) .'</h4>' ),

            Field::make('radio', self::SHOW_RELATED, esc_html__('Show Related Posts', 'extend-site'))
                ->set_options(Toggle::yes_no())
                ->set_default_value(Toggle::YES),

            Field::make('text', self::RELATED_COUNT, esc_html__('Related Posts Count', 'extend-site'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1)
                ->set_attribute('max', 20)
                ->set_attribute('step', 1)
                ->set_default_value(3)
                ->set_width(30),
        ];
    }

    /**
     * get data
     */

    // Read: sidebar
    public static function get_sidebar_position(string $default = Layout::SIDEBAR_RIGHT): string
    {
        $value = self::get(self::SIDEBAR_POSITION, $default);
        return !empty($value) ? $value : $default;
    }

    // get show related posts
    public static function get_show_related_posts()
    {
        $value = self::get(self::SHOW_RELATED);

        return !empty($value) ? $value : '';
    }

    // get related count
    public static function get_related_count(): int|string
    {
        $value = (int) self::get(self::RELATED_COUNT);

        return !empty($value) ? $value : '';
    }

    // get all data
    public static function get_all(): array
    {
        return [
            'sidebar_position' => self::get_sidebar_position(),
            'show_related_posts' => self::get_show_related_posts(),
            'related_count' => self::get_related_count(),
        ];
    }
}