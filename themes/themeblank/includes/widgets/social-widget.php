<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MyTheme_Social_Widget extends WP_Widget {
	/* Widget setup */
	public function __construct() {
		$themeblank_social_widget_ops = array(
			'classname'     =>  'social-widget',
			'description'   =>  esc_html__( 'Hiển thị mạng xã hội', 'themeblank' ),
		);

		parent::__construct( 'social-widget', 'My Theme: Mạng xã hội', $themeblank_social_widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ): void {
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		?>
        <div class="warp d-flex gap-3">
			<?php themeblank_get_social_url(); ?>
        </div>
		<?php

		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	function form( $instance ): void {
		$defaults = array(
			'title' => esc_html__('Mạng xã hội', 'themeblank')
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php esc_html_e( 'Tiêu đề:', 'themeblank' ); ?>
            </label>

            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
			<?php esc_html_e( 'Chú ý: Thiệt lập liên kết mạng xã hội trong mục "Cài đặt theme -> Mạng xã hội"', 'themeblank' ); ?>
        </p>
		<?php

	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ): array {
		$instance = array();

		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}
}

// Register social widget
function themeblank_register_social_widget(): void {
	register_widget( 'MyTheme_Social_Widget' );
}

add_action( 'widgets_init', 'themeblank_register_social_widget' );