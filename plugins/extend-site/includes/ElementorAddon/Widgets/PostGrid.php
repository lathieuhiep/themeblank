<?php
namespace ExtendSite\ElementorAddon\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use ExtendSite\Constants\Config;
use ExtendSite\ElementorAddon\Traits\HasImageSizeControl;
use ExtendSite\ElementorAddon\Traits\HasQueryControls;

defined( 'ABSPATH' ) || exit;

class PostGrid extends Widget_Base {
    use HasImageSizeControl;
    use HasQueryControls;

	// widget name
	public function get_name(): string {
		return 'es-post-grid';
	}

	// widget title
	public function get_title(): string {
		return esc_html__( 'Bài viết dạng lưới', 'extend-site' );
	}

	// widget icon
	public function get_icon(): string {
		return 'eicon-gallery-grid';
	}

	// widget categories
	public function get_categories(): array {
		return array( 'es-addons' );
	}

	// widget keywords
	public function get_keywords(): array
	{
        return ['post', 'grid', 'extend site'];
	}

	// widget controls
	protected function register_controls(): void {
		// Content query
        $this->addQueryControls($this, '', 6, 'category', [], function($widget) {
            $this->addImageSizeControl($widget);
        });

		// Content layout
		$this->start_controls_section(
			'content_layout',
			[
				'label' => esc_html__( 'Thiết lập giao diện', 'extend-site' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'column_number',
			[
				'label' => esc_html__( 'Số cột', 'extend-site' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
				'selectors' => [
					'{{WRAPPER}} .es-grid-layout' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => esc_html__( 'Khoảng cách cột', 'extend-site' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'default' => [
					'size' => 2.4,
					'unit' => 'rem',
				],
				'selectors' => [
					'{{WRAPPER}} .es-grid-layout' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__( 'Khoảng cách hàng', 'extend-site' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'default' => [
					'size' => 2.4,
					'unit' => 'rem',
				],
				'selectors' => [
					'{{WRAPPER}} .es-grid-layout' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style title
		$this->start_controls_section(
			'style_title',
			[
				'label' => esc_html__( 'Tiêu đề', 'extend-site' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Màu', 'extend-site' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .item .title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => esc_html__( 'Màu thay đổi', 'extend-site' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .item .title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .item .title',
			]
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label'     => esc_html__( 'Căn chỉnh', 'extend-site' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => esc_html__( 'Trái', 'extend-site' ),
						'icon'  => 'eicon-text-align-left',
					],

					'center' => [
						'title' => esc_html__( 'Giữa', 'extend-site' ),
						'icon'  => 'eicon-text-align-center',
					],

					'right' => [
						'title' => esc_html__( 'Phải', 'extend-site' ),
						'icon'  => 'eicon-text-align-right',
					],

					'justify' => [
						'title' => esc_html__( 'Căn đều hai lề', 'extend-site' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .item .title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style excerpt
		$this->start_controls_section(
			'style_excerpt',
			[
				'label'     => esc_html__( 'Nôi dung tóm tắt', 'extend-site' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_excerpt' => 'show',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Màu', 'extend-site' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .item .content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .item .content',
			]
		);

		$this->add_responsive_control(
			'excerpt_align',
			[
				'label'     => esc_html__( 'Căn chỉnh', 'extend-site' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => esc_html__( 'Trái', 'extend-site' ),
						'icon'  => 'eicon-text-align-left',
					],

					'center' => [
						'title' => esc_html__( 'Giữa', 'extend-site' ),
						'icon'  => 'eicon-text-align-center',
					],

					'right' => [
						'title' => esc_html__( 'Phải', 'extend-site' ),
						'icon'  => 'eicon-text-align-right',
					],

					'justify' => [
						'title' => esc_html__( 'Căn đều hai lề', 'extend-site' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .item .content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	// widget output on the frontend
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		// Query
		$query = $this->buildPostQuery( $settings );

		if ( $query->have_posts() ) :
        ?>
            <div class="es-addon-post-grid es-grid-layout">
                <?php while ( $query->have_posts() ): $query->the_post(); ?>
                    <div class="item">
                        <div class="thumbnail">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <?php
                                if ( has_post_thumbnail() ) :
                                    the_post_thumbnail( $settings['image_size'] );
                                else:
                                    ?>
                                    <img src="<?php echo esc_url( Config::$url . 'assets/images/no-image.png' ); ?>" alt="<?php the_title(); ?>"/>
                                <?php endif; ?>
                            </a>
                        </div>

                        <h2 class="title">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <?php if ( $settings['show_excerpt'] == 'show' ) : ?>
                            <div class="content">
                                <p>
                                    <?php
                                    if ( has_excerpt() ) :
                                        echo esc_html( wp_trim_words( get_the_excerpt(), $settings['excerpt_length'], '...' ) );
                                    else:
                                        echo esc_html( wp_trim_words( get_the_content(), $settings['excerpt_length'], '...' ) );
                                    endif;
                                    ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
		<?php
		endif;
	}
}