<?php
namespace ExtendSite\ElementorAddon\Traits;

use Elementor\Controls_Manager;
use ExtendSite\ElementorAddon\Base\ControlOptions;

defined('ABSPATH') || exit;

/**
 * Trait to add an image size control to Elementor widgets.
 */
trait HasImageSizeControl
{
    protected function addImageSizeControl(
        $widget,
        string $control_id = 'image_size',
        string $default = 'large',
        array $args = []
    ): void {
        $base_args = [
            'label'       => esc_html__('Độ phân giải ảnh', 'extend-site'),
            'type'        => Controls_Manager::SELECT,
            'default'     => $default,
            'options'     => ControlOptions::image_sizes(),
            'label_block' => true,
        ];

        // $this là chính widget đang dùng trait
        $widget->add_control( $control_id, array_merge( $base_args, $args ) );
    }
}