<?php
use ExtendSite\Admin\Options\Modules\GeneralOptions;

defined('ABSPATH') || exit;

$logo = themeblank_opt(GeneralOptions::class)::get_logo_id() ?? '';
?>
<div class="logo">
    <a href="<?php echo esc_url( get_home_url( '/' ) ); ?>" title="<?php bloginfo( 'name' ); ?>">
        <?php
        if ( ! empty( $logo ) ) :
            echo wp_get_attachment_image( $logo, 'medium' );
        else :
        ?>
            <img class="logo-default"
                 src="<?php echo esc_url( get_theme_file_uri( '/assets/images/logo.png' ) ) ?>"
                 alt="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>" width="64" height="64"/>

        <?php endif; ?>
    </a>

    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#primary-menu"
            aria-controls="site-menu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="icon-theme-mask icon-theme-mask-bars"></i>
    </button>
</div>