<?php
namespace ExtendSite\ElementorAddon\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use ExtendSite\ElementorAddon\Base\ControlOptions;
use ExtendSite\ElementorAddon\Traits\HasImageSizeControl;

defined( 'ABSPATH' ) || exit;

class InfoBox extends Widget_Base {
    use HasImageSizeControl;

	// widget name
	public function get_name(): string {
		return 'es-info-box';
	}

	// widget title
	public function get_title(): string {
		return esc_html__( 'Hộp thông tin', 'extend-site' );
	}

	// widget icon
	public function get_icon(): string {
		return 'eicon-icon-box';
	}

	// widget categories
	public function get_categories(): array {
		return array( 'es-addons' );
	}

	// widget keywords
	public function get_keywords(): array
	{
		return ['info', 'box', 'info box', 'extend site'];
	}

	// widget controls
	protected function register_controls(): void {
		// image section
		$this->start_controls_section(
			'image_section',
			[
				'label' => esc_html__( 'Ảnh', 'extend-site' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control('type',
            [
            'label'   => esc_html__('Kiểu', 'extend-site'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'img-on-top',
            'options' => [
                'img-on-top'  => esc_html__('Ảnh/Icon ở trên', 'extend-site'),
                'img-on-left' => esc_html__('Ảnh/Icon bên trái', 'extend-site'),
                'img-on-right' => esc_html__('Ảnh/Icon bên phải', 'extend-site'),
            ]
        ]);

        $this->add_control('img_or_icon',
            [
                'label'   => esc_html__('Ảnh hoặc Icon', 'extend-site'),
                'type'    => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'none'  => [ 'title' => esc_html__('Không dùng', 'extend-site'),  'icon' => 'fa fa-ban' ],
                    'number' => [ 'title' => esc_html__('Số', 'extend-site'),  'icon' => 'eicon-number-field' ],
                    'icon'  => [ 'title' => esc_html__('Icon', 'extend-site'),  'icon' => 'fa fa-info-circle' ],
                    'image' => [ 'title' => esc_html__('Image', 'extend-site'), 'icon' => 'fa fa-image' ],
                ],
                'default' => 'icon',
        ]);

        // Condition: 'img_or_icon' => 'number'
        $this->add_control(
            'number',
            [
                'name'    => 'number',
                'label'   => esc_html__( 'Số', 'extend-site' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'condition' => [
                    'img_or_icon' => 'number',
                ],
            ],
        );

        // Condition: 'img_or_icon' => 'icon'
        $this->add_control(
            'icon',
            [
                'name'    => 'icon',
                'label'   => esc_html__( 'Icon', 'extend-site' ),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'img_or_icon' => 'icon',
                ],
            ],
        );

        // Condition: 'img_or_icon' => 'image'
        $this->add_control(
            'image',
            [
                'label' => esc_html__('Ảnh', 'extend-site'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'img_or_icon' => 'image',
                ],
            ]
        );

        $this->addImageSizeControl($this, 'image_size', 'large', [
            'condition' => [
                'img_or_icon' => 'image',
            ],
        ]);

		$this->end_controls_section();

        // content section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Nội dung', 'extend-site' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'heading',
            [
                'label'       => esc_html__( 'Tiêu đề', 'extend-site' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Tiêu đề', 'extend-site' ),
                'label_block' => true
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label'   => esc_html__( 'Thẻ HTML tiêu đề', 'extend-site' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => ControlOptions::text_wrappers(),
            ]
        );

        $this->add_control(
            'content',
            [
                'name' => 'content',
                'label' => esc_html__( 'Văn bản', 'extend-site' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__( 'List Content' , 'extend-site' ),
                'show_label' => false,
            ],
        );

        $this->end_controls_section();

        // style box
		$this->start_controls_section(
			'style_box',
			[
				'label' => esc_html__( 'Hộp', 'extend-site' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label' => esc_html__( 'Khoảng cách các Icon', 'extend-site' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'rem',
					'size' => 2.4,
				],
				'selectors' => [
					'{{WRAPPER}} .es-addon-ic-txt' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_spacer',
			[
				'label' => esc_html__( 'Khoảng cách nội dung', 'extend-site' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'rem',
					'size' => 1.2,
				],
				'selectors' => [
					'{{WRAPPER}} .text-box' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        // style icon
		$this->start_controls_section(
			'style_icon',
			[
				'label' => esc_html__( 'Icon', 'extend-site' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'Độ rộng', 'extend-site' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'rem',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .es-icon' => '--es-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Màu', 'extend-site' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .es-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Style heading
		$this->start_controls_section(
			'style_heading',
			[
				'label' => esc_html__( 'Tiêu đề', 'extend-site' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Màu', 'extend-site' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .text-box .heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'label'    => esc_html__( 'Kiểu chữ', 'extend-site' ),
				'selector' => '{{WRAPPER}} .text-box .heading',
			]
		);

		$this->end_controls_section();

		// Style desc
		$this->start_controls_section(
			'style_description',
			[
				'label' => esc_html__( 'Văn bản', 'extend-site' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'extend-site' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .text-box .content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'label'    => esc_html__( 'Kiểu chữ', 'extend-site' ),
				'selector' => '{{WRAPPER}} .text-box .content',
			]
		);

		$this->end_controls_section();
	}

	// widget output on the frontend
	protected function render(): void {
		$settings = $this->get_settings_for_display();
        $type = $settings['type'];

        // controls
		$heading_tag = $settings['heading_tag'];

        // Add class img_or_icon
        $img_or_icon = $settings['img_or_icon'];

        $class_img_or_icon = ['thumbnail-box__type'];
        $class_img_or_icon[] = 'type-' . $img_or_icon;

        $this->add_render_attribute( 'class_img_or_icon', 'class', $class_img_or_icon );
	?>
		<div class="es-addon-info-box es-flex">
            <?php if ( $img_or_icon != 'none' ): ?>
                <div class="thumbnail-box">
                    <div <?php echo $this->get_render_attribute_string( 'class_img_or_icon' ); ?>>
                        <?php
                        if ( $img_or_icon == 'number' ):
                            echo esc_html( $settings['number'] );
                        endif;

                        if ( $img_or_icon == 'icon' ) :
                            Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        endif;

                        if ( $img_or_icon == 'image' ):
                            echo wp_get_attachment_image( $settings['image']['id'], $settings['image_size'] );
                        endif;
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="text-box es-flex es-flex-column es-flex-grow-1">
                <?php
                printf(
                    '<%1$s class="heading es-m-0">%2$s</%1$s>',
                    esc_attr( $heading_tag ),
                    esc_html( $settings['heading'] )
                );
                ?>

                <div class="content">
                    <?php echo wpautop( $settings['content'] ); ?>
                </div>
            </div>
		</div>
	<?php
	}

	protected function content_template() {}
}