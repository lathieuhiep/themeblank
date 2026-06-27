<?php
namespace ExtendSite\ElementorAddon\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use ExtendSite\Constants\Config;
use ExtendSite\ElementorAddon\Traits\HasImageSizeControl;
use ExtendSite\ElementorAddon\Traits\HasQueryControls;
use ExtendSite\ElementorAddon\Traits\HasSliderControls;

defined( 'ABSPATH' ) || exit;

class PostCarousel extends Widget_Base {
    use HasImageSizeControl;
    use HasQueryControls;
    use HasSliderControls;

	// widget name
	public function get_name(): string {
		return 'es-post-carousel';
	}

	// widget title
	public function get_title(): string {
		return esc_html__( 'Slider bài viết', 'extend-site' );
	}

	// widget icon
	public function get_icon(): string {
		return 'eicon-slider-push';
	}

	// widget categories
	public function get_categories(): array {
		return array( 'es-addons' );
	}

	// widget style dependencies
	public function get_style_depends(): array {
		return [ 'swiper' ];
	}

	// widget scripts dependencies
	public function get_script_depends(): array {
		return [ 'swiper', 'es-addons-elementor' ];
	}

	// widget keywords
	public function get_keywords(): array
	{
        return ['post', 'carousel', 'slider', 'extend site'];
	}

	// widget controls
	protected function register_controls(): void {

		// Content section
        $this->addQueryControls($this, '', 6, 'category', [], function($widget) {
            $this->addImageSizeControl($widget);
        });

		// additional options
        $this->addAdditionalOptionsSection( $this, true );

		// Breakpoints options
        $this->addBreakpointsControlsGrouped($this);

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
				'label'     => esc_html__( 'Màu khi di chuột', 'extend-site' ),
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
				]
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
					'{{WRAPPER}} .item .desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .item .desc',
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
					'{{WRAPPER}} .item .desc' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section();

	}

	// widget output on the frontend
	protected function render(): void {
		$settings = $this->get_settings_for_display();

        // Add classes for the slider wrapper
		$classes = ['es-addon-post-carousel swiper es-custom-swiper-slider'];

		if ( $settings['equal_height'] === 'yes' ) {
			$classes[] = 'es-equal-height';
		}

		$this->add_render_attribute( 'classes', 'class', $classes );

		// set settings for swiper
		$swiperOptions = $this->generateSlideConfig( $settings );

        // query settings
		$query = $this->buildPostQuery( $settings );

		if ( $query->have_posts() ) :
        ?>
            <div <?php echo $this->get_render_attribute_string( 'classes' ); ?> data-settings-swiper='<?php echo esc_attr( $swiperOptions ); ?>'>
                <div class="swiper-wrapper">
					<?php while ( $query->have_posts() ): $query->the_post(); ?>
                        <div class="item swiper-slide">
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

                            <div class="body">
                                <h2 class="title">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
										<?php the_title(); ?>
                                    </a>
                                </h2>

								<?php if ( $settings['show_excerpt'] == 'show' ) : ?>
                                    <div class="desc">
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
                        </div>
					<?php endwhile; wp_reset_postdata(); ?>
                </div>

	            <?php if ( $settings['navigation'] == 'both' || $settings['navigation'] == 'dots' ) : ?>
                    <div class="swiper-pagination"></div>
	            <?php endif; ?>

	            <?php if ( $settings['navigation'] == 'both' || $settings['navigation'] == 'arrows' ) : ?>
                    <div class="swiper-button-prev">
                        <i class="es-icon-mask es-icon-mask-angle-left"></i>
                    </div>

                    <div class="swiper-button-next">
                        <i class="es-icon-mask es-icon-mask-angle-right"></i>
                    </div>
	            <?php endif; ?>
            </div>
		<?php
		endif;
	}
}