<?php

use ExtendSite\Admin\Options\Modules\FooterOptions;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// get responsive row class
function basictheme_get_responsive_row_class($per_row): string
{
    if ( empty( $per_row ) || ! is_array( $per_row ) ) {
        $per_row = [
            'sm' => 2,
            'md' => 2,
            'lg' => 3,
            'xl' => 3
        ];
    }

    return sprintf(
        'theme-row-cols-1 theme-row-cols-sm-%s theme-row-cols-md-%s theme-row-cols-lg-%s theme-row-cols-xl-%s',
        $per_row['sm'],
        $per_row['md'],
        $per_row['lg'],
        $per_row['xl']
    );
}

// get footer sidebar columns count
function basictheme_get_footer_sidebar_columns_count (): int
{
    return basictheme_opt(FooterOptions::class)::get_footer_sidebar_columns_count() ?? 4;
}