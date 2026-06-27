<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class MyTheme_Contact_Info_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'contact-info-widget',
			'description' => esc_html__( 'Hiển thị thông tin liên hệ', 'basictheme' ),
		);

		parent::__construct( 'contact-info-widget', 'My Theme: Thông tin liên hệ', $widget_ops );
	}

	public function widget( $args, $instance ): void {
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		?>
		<div class="list">
			<?php if ( ! empty( $instance['address'] ) ) : ?>
				<div class="item">
                    <i class="icon-theme-mask icon-theme-mask-location-dot"></i>
					<span class="text"><?php echo esc_html( $instance['address'] ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $instance['email'] ) ) : ?>
				<div class="item">
                    <i class="icon-theme-mask icon-theme-mask-paper-plane"></i>
					<a class="text"
					   href="mailto:<?php echo esc_attr( $instance['email'] ); ?>"><?php echo esc_html( $instance['email'] ); ?></a>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $instance['phone'] ) ) : ?>
				<div class="item">
                    <i class="icon-theme-mask icon-theme-mask-phone"></i>
					<a class="text"
					   href="tel:<?php echo esc_attr( basictheme_preg_replace_ony_number( $instance['phone'] ) ); ?>"><?php echo esc_html( $instance['phone'] ); ?></a>
				</div>
			<?php endif; ?>
		</div>
		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ): void {
		$defaults = [
			'title'   => esc_html__( 'Thông tin liên hệ', 'basictheme' ),
			'address' => '',
			'email'   => '',
			'phone'   => ''
		];

		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = [
			'title'   => esc_html__( 'Tiêu đề:', 'basictheme' ),
			'address' => esc_html__( 'Địa chỉ:', 'basictheme' ),
			'email'   => esc_html__( 'Email:', 'basictheme' ),
			'phone'   => esc_html__( 'Số điện thoại:', 'basictheme' )
		];

		foreach ( $fields as $key => $label ) {
			?>
			<p>
				<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $label; ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $key ); ?>"
				       name="<?php echo $this->get_field_name( $key ); ?>" type="text"
				       value="<?php echo esc_attr( $instance[ $key ] ); ?>">
			</p>
			<?php
		}
	}

	public function update( $new_instance, $old_instance ): array {
		$instance            = [];
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? strip_tags( $new_instance['address'] ) : '';
		$instance['email']   = ( ! empty( $new_instance['email'] ) ) ? sanitize_email( $new_instance['email'] ) : '';
		$instance['phone']   = ( ! empty( $new_instance['phone'] ) ) ? strip_tags( $new_instance['phone'] ) : '';

		return $instance;
	}
}

function basictheme_register_contact_info_widget(): void {
	register_widget( 'MyTheme_Contact_Info_Widget' );
}

add_action( 'widgets_init', 'basictheme_register_contact_info_widget' );