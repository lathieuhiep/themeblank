<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MyTheme_Recent_Post_Widget extends WP_Widget {
	/* Widget setup */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'recent-post-widget',
			'description' => esc_html__( 'Hiển thị bài viết mới nhất', 'themeblank' ),
		);

		parent::__construct( 'recent-post-widget', 'My Theme: Bài viết mới nhất', $widget_ops );
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

		$limit   = $instance['number'] ?? 5;
		$cat_ids = ! empty( $instance['select_cat'] ) ? $instance['select_cat'] : array( '0' );

		$post_arg = array(
			'post_type'           => 'post',
			'posts_per_page'      => $limit,
			'orderby'             => $instance['order_by'],
			'order'               => $instance['order'],
			'ignore_sticky_posts' => 1,
		);

		if ( is_singular('post') && get_the_ID() ) {
			$post_arg['post__not_in'] = array(get_the_ID());
		}

		if ( ! in_array( 0, $cat_ids ) ) {
			$post_arg['cat'] = $cat_ids;
		}

		$post_query = new WP_Query( $post_arg );

		if ( $post_query->have_posts() ) :
        ?>
            <div class="post-list theme-row-cols-1 gap-2">
				<?php while ( $post_query->have_posts() ) : $post_query->the_post(); ?>
                    <div class="item">
                        <div class="image">
							<?php
							if ( has_post_thumbnail() ):
								the_post_thumbnail( 'thumbnail' );
							else:
								?>
                                <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/no-image.png' ) ); ?>"
                                     width="100"
                                     height="70"
                                     alt="post">
							<?php endif; ?>
                        </div>

                        <div class="content">
                            <h4 class="title">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title() ?>">
									<?php the_title(); ?>
                                </a>
                            </h4>

                            <p class="meta d-flex gap-1">
                                <i class="icon-theme-mask icon-theme-mask-calendar-days"></i>
								<span><?php echo get_the_date(); ?></span>
                            </p>
                        </div>
                    </div>
				<?php endwhile; wp_reset_postdata(); ?>
            </div>
		<?php
		endif;

		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	function form( $instance ): void {
		$defaults = array(
			'title' => esc_html__('Bài viết mới nhất', 'themeblank'),
			'order' => 'DESC'
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$number     = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$select_cat = $instance['select_cat'] ?? array( '0' );
		$order      = $instance['order'];
		$order_by   = $instance['order_by'] ?? 'ID';

		$terms = get_terms( array(
			'taxonomy' => 'category',
			'orderby'  => 'id'
		) );

		?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Tiêu đề:', 'themeblank' ); ?>
            </label>

            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"/>
        </p>

        <!-- Start Select Event Cat -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'select_cat' ) ); ?>">
				<?php esc_attr_e( 'Chọn danh mục:', 'themeblank' ); ?>
            </label>

            <select id="<?php echo esc_attr( $this->get_field_id( 'select_cat' ) ); ?>"
                    name="<?php echo esc_attr( $this->get_field_name( 'select_cat' ) ) . '[]'; ?>" class="widefat"
                    size="10" multiple>

                <option value="0" <?php echo( in_array( 0, $select_cat ) ? 'selected="selected"' : '' ); ?>>
					<?php esc_html_e( 'Tất cả', 'themeblank' ); ?>
                </option>

				<?php
				if ( ! empty( $terms ) ) :

					foreach ( $terms as $term_item ) :
						?>
                        <option value="<?php echo $term_item->term_id; ?>" <?php echo( in_array( $term_item->term_id, $select_cat ) ? 'selected="selected"' : '' ); ?>>
							<?php echo esc_html( $term_item->name ) . ' (' . esc_html( $term_item->count ) . ')'; ?>
                        </option>
					<?php
					endforeach;

				endif;
				?>

            </select>
        </p>

        <!-- Start Order -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>">
				<?php esc_html_e( 'Sắp xếp:', 'themeblank' ); ?>
            </label>

            <select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
                    name="<?php echo $this->get_field_name( 'order' ) ?>" class="widefat">
                <option value="ASC" <?php echo ( $order == 'ASC' ) ? 'selected' : ''; ?>>
					<?php esc_html_e( 'Tăng dần', 'themeblank' ); ?>
                </option>

                <option value="DESC" <?php echo ( $order == 'DESC' ) ? 'selected' : ''; ?>>
					<?php esc_html_e( 'Giảm dần', 'themeblank' ); ?>
                </option>
            </select>
        </p>

        <!-- Start OrderBy -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order_by' ) ); ?>">
				<?php esc_html_e( 'Sắp xếp theo:', 'themeblank' ); ?>
            </label>

            <select id="<?php echo esc_attr( $this->get_field_id( 'order_by' ) ); ?>"
                    name="<?php echo $this->get_field_name( 'order_by' ) ?>" class="widefat">
                <option value="ID" <?php echo ( $order_by == 'ID' ) ? 'selected' : ''; ?>>
					<?php esc_html_e( 'ID', 'themeblank' ); ?>
                </option>

                <option value="date" <?php echo ( $order_by == 'date' ) ? 'selected' : ''; ?>>
					<?php esc_html_e( 'Ngày', 'themeblank' ); ?>
                </option>

                <option value="title" <?php echo ( $order_by == 'title' ) ? 'selected' : ''; ?>>
					<?php esc_html_e( 'Tiêu đề', 'themeblank' ); ?>
                </option>

                <option value="rand" <?php echo ( $order_by == 'rand' ) ? 'selected' : ''; ?>>
					<?php esc_html_e( 'Ngẫu nhiên', 'themeblank' ); ?>
                </option>
            </select>
        </p>

        <!-- Start Number Post Show -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>">
				<?php esc_html_e( 'Số lượng bài viết hiển thị:', 'themeblank' ); ?>
            </label>

            <input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" class="tiny-text"
                   name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1"
                   value="<?php echo esc_attr( $number ); ?>" size="3"/>
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

		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['select_cat'] = $new_instance['select_cat'];
		$instance['order']      = $new_instance['order'];
		$instance['order_by']   = $new_instance['order_by'];
		$instance['number']     = (int) $new_instance['number'];

		return $instance;
	}
}

// Register widget
function themeblank_register_recent_post_widget(): void {
	register_widget( 'MyTheme_Recent_Post_Widget' );
}

add_action( 'widgets_init', 'themeblank_register_recent_post_widget' );