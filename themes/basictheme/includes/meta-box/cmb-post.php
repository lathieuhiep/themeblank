<?php
add_action('cmb2_admin_init', 'basictheme_post_meta_boxes');
function basictheme_post_meta_boxes(): void {
    $cmb = new_cmb2_box(array(
        'id' => 'basictheme_cmb_post',
        'title' => esc_html__('Tùy chọn metabox', 'basictheme'),
        'object_types' => array('post'),
        'context' => 'normal',
        'priority' => 'low',
        'show_names' => true,
    ));

    $cmb->add_field( array(
        'id'   => 'basictheme_cmb_post_title',
        'name' => esc_html__( 'Tiêu đề', 'basictheme' ),
        'type' => 'title',
        'desc' => esc_html__( 'Đây là mô tả tiêu đề', 'basictheme' ),
    ) );
}