<?php

namespace ExtendSite\Constants;

defined('ABSPATH') || exit;

final class Breakpoints
{
    public const SM = 576;
    public const MD = 768;
    public const LG = 992;
    public const XL = 1200;

    // Map of breakpoints
    public static function map(): array
    {
        return [
            'sm' => self::SM,
            'md' => self::MD,
            'lg' => self::LG,
            'xl' => self::XL
        ];
    }

    // Helper: default columns
    public static function default_col(string $key): int
    {
        return ($key === 'lg' || $key === 'xl') ? 3 : 2;
    }

    // Helper: default sidebar columns
    public static function default_sidebar_col(string $key): int
    {
        $columns = 12;

        if ( $key === 'xl' ) {
            $column = 3;
        } elseif ( $key === 'lg' ) {
            $column = 3;
        } elseif ( $key === 'md' ) {
            $column = 6;
        } else {
            $column = $columns;
        }

        return $column;
    }
}