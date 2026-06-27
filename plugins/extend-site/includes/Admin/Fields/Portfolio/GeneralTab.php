<?php

namespace ExtendSite\Admin\Fields\Portfolio;

use Carbon_Fields\Field;
use ExtendSite\Admin\Fields\FieldTabIF;

class GeneralTab implements FieldTabIF
{
    // key meta box
    private const KEY = 'es_pf_general_tab_';
    private const NAME = self::KEY . 'client_name';
    private const DATE = self::KEY . 'project_date';

    /**
     * return array fields
     */
    public static function fields(): array
    {
        return [
            Field::make('text', self::NAME, esc_html__('Tên khách hàng', 'extend-site')),
            Field::make('date', self::DATE, esc_html__('Ngày dự án', 'extend-site')),
        ];
    }

    /**
     * get data fields
     */
    public static function get_data(int $post_id): array
    {
        return [
            'name' => carbon_get_post_meta($post_id, self::NAME),
            'date' => carbon_get_post_meta($post_id, self::DATE),
        ];
    }
}