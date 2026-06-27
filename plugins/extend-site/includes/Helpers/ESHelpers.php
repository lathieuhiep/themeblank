<?php
namespace ExtendSite\Helpers;

defined('ABSPATH') || exit;

class ESHelpers
{
    /**
     * Display the default WordPress pagination markup.
     */
    public static function pagination(): void
    {
        the_posts_pagination(array(
            'type'               => 'list',
            'mid_size'           => 2,
            'prev_text'          => '<i class="es-icon-mask es-icon-mask-angle-left"></i>',
            'next_text'          => '<i class="es-icon-mask es-icon-mask-angle-right"></i>',
            'screen_reader_text' => '&nbsp;',
        ));
    }
}
