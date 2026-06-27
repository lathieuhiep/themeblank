<?php

namespace ExtendSite\Constants;

defined('ABSPATH') || exit;

final class Layout
{
    public const SIDEBAR_RIGHT = 'right';
    public const SIDEBAR_LEFT = 'left';
    public const SIDEBAR_HIDDEN = 'hidden';

    /**
     * Sidebar options for selects.
     *
     * @return array<string, string>
     */
    public static function sidebar_options(): array
    {
        return [
            self::SIDEBAR_RIGHT => esc_html__('Right Sidebar', 'extend-site'),
            self::SIDEBAR_LEFT => esc_html__('Left Sidebar', 'extend-site'),
            self::SIDEBAR_HIDDEN => esc_html__('No Sidebar', 'extend-site'),
        ];
    }
}