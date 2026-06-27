<?php

namespace ExtendSite\Constants;

defined('ABSPATH') || exit;
final class Toggle
{
    public const YES = 'yes';
    public const NO = 'no';

    public static function yes_no(): array
    {
        return [
            self::YES => esc_html__('Yes', 'extend-site'),
            self::NO => esc_html__('No', 'extend-site'),
        ];
    }
}
