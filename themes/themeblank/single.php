<?php
use ExtendSite\Admin\Options\Modules\SinglePostOptions;

defined('ABSPATH') || exit;

get_header();

$sidebar = themeblank_opt(SinglePostOptions::class)::get_sidebar_position() ?? THEME_SIDEBAR_POSITION_RIGHT;
$class_col_content = themeblank_col_use_sidebar( $sidebar, 'sidebar-main' );

get_template_part('template-parts/components/inc', 'breadcrumbs');
?>

<div class="site-container single-post-warp">
    <div class="container">
        <div class="row">
            <div class="<?php echo esc_attr( $class_col_content ); ?>">
                <?php
                if ( have_posts() ) : while (have_posts()) : the_post();

                    get_template_part( 'template-parts/post/content','single' );

                    endwhile;
                endif;
                ?>
            </div>

            <?php
            if ( $sidebar !== THEME_SIDEBAR_POSITION_HIDDEN ) :
	            get_sidebar();
            endif;
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>

