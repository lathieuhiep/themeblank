<?php
get_template_part('template-parts/components/inc', 'breadcrumbs');
?>

<div class="container">
    <?php while ( have_posts() ) : the_post(); ?>
        <div class="site-page-content">
            <?php
            the_content();
            themeblank_link_page();
            ?>
        </div>
    <?php
        get_template_part('template-parts/components/inc', 'comment-form');
    endwhile;
    ?>
</div>