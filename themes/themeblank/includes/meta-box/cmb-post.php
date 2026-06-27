<?php
add_action('cmb2_admin_init', 'themeblank_post_meta_boxes');
function themeblank_post_meta_boxes(): void {
    $cmb = new_cmb2_box(array(
        'id' => 'themeblank_cmb_post',
        'title' => esc_html__('Tùy chọn metabox', 'themeblank'),
        'object_types' => array('post'),
        'context' => 'normal',
        'priority' => 'low',
        'show_names' => true,
    ));

    $cmb->add_field( array(
        'id'   => 'themeblank_cmb_post_title',
        'name' => esc_html__( 'Tiêu đề', 'themeblank' ),
        'type' => 'title',
        'desc' => esc_html__( 'Đây là mô tả tiêu đề', 'themeblank' ),
    ) );
}