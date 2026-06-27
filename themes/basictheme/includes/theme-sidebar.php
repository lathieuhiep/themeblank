<?php
/* Better way to add multiple widgets areas */
function basictheme_register_sidebar( $name, $id, $description = '' ): void {
	register_sidebar( array(
		'name'          => $name,
		'id'            => $id,
		'description'   => $description,
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title h4">',
		'after_title'   => '</h2>',
	) );
}

const PREFIX_SIDEBAR_FOOTER_COLUMN = 'sidebar-footer-column-';
function basictheme_multiple_widget_init(): void {
    // sidebar main
	basictheme_register_sidebar(
        esc_html__( 'Sidebar chính', 'basictheme' ),
        'sidebar-main',
        esc_html__('Dùng ở các trang bài viết', 'basictheme' )
    );

    // sidebar woo
    if ( class_exists( 'Woocommerce' ) ) :
        basictheme_register_sidebar(
            esc_html__( 'Sidebar shop', 'basictheme' ),
            'sidebar-wc',
            esc_html__( 'Dùng ở trang danh mục sản phẩm.', 'basictheme' )
        );

        basictheme_register_sidebar(
            esc_html__( 'Sidebar sản phẩm', 'basictheme' ),
            'sidebar-wc-product',
            esc_html__( 'Dùng cho trang chi tiết sản phẩm', 'basictheme' )
        );
    endif;

	// sidebar footer
	$opt_number_columns = basictheme_get_footer_sidebar_columns_count();

	for ( $i = 1; $i <= $opt_number_columns; $i ++ ) {
		basictheme_register_sidebar(
            sprintf( esc_html__( 'Sidebar chân trang cột %d', 'basictheme' ), $i ),
            PREFIX_SIDEBAR_FOOTER_COLUMN . $i,
			esc_html__( 'Dùng ở chân trang', 'basictheme' )
        );
	}
}
add_action( 'widgets_init', 'basictheme_multiple_widget_init' );