<?php
namespace ExtendSite\Admin\Fields;

use Carbon_Fields\Container;
use ExtendSite\Admin\Fields\Portfolio\GeneralTab;

defined('ABSPATH') || exit;

class PortfolioFields {
    public static function register(string $post_type): void {
        Container::make('post_meta', esc_html__('Cấu hình Portfolio', 'extend-site'))
            ->where('post_type', '=', $post_type)
            ->set_priority( 'default' )
            ->add_tab(__('Thông tin chung', 'extend-site'), GeneralTab::fields());
    }
}