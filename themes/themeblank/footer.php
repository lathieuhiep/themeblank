    </main><!-- close .sticky-footer -->

    <?php
    if ( !is_404() ) :
        get_template_part('template-parts/footer/inc', 'layout');
     endif;
     ?>
</div><!-- .main-warp -->

<?php
get_template_part('template-parts/components/inc', 'loading');
get_template_part('template-parts/components/inc', 'back-top');

wp_footer();
?>

</body>
</html>
