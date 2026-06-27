<?php

use ExtendSite\Admin\Options\Modules\GeneralOptions;

defined('ABSPATH') || exit;

$show_loading = basictheme_opt(GeneralOptions::class)::get_loading_enabled() ?? true;

if(  $show_loading ) :
    $opt_image_loading  = basictheme_opt(GeneralOptions::class)::get_loading_image_id();
?>
    <div id="site-loading" class="d-flex align-items-center justify-content-center">
        <?php if ( !empty( $opt_image_loading ) ): ?>
            <img class="loading_img"
                 src="<?php echo esc_url( wp_get_attachment_image_url( $opt_image_loading ) ); ?>"
                 alt="<?php esc_attr_e('Đang tải...','basictheme') ?>">
        <?php else: ?>
            <img class="loading_img"
                 src="<?php echo esc_url(get_theme_file_uri( '/assets/images/gif/loading.gif' )); ?>"
                 alt="<?php esc_attr_e('Đang tải...','basictheme') ?>">
        <?php endif; ?>
    </div>
<?php endif; ?>