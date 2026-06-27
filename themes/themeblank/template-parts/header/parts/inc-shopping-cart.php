<?php if ( class_exists( 'Woocommerce' ) && ! is_cart() && ! is_checkout() ) : ?>
    <div class="widget-cart-warp d-flex align-items-center">
        <?php
        do_action( 'themeblank_woo_shopping_cart' );

        themeblank_custom_mini_cart();
        ?>
    </div>
<?php endif; ?>