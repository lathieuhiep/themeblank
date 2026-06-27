<?php

use ExtendSite\Admin\Options\Modules\WooOptions;
use ExtendSite\Admin\Options\Modules\WooSingleOptions;

/**
 * General functions used to integrate this theme with WooCommerce.
 */

/* Start get cart */
function basictheme_get_cart(): void
{
    $cart_count = WC()->cart->get_cart_contents_count();
    ?>
    <div class="cart-box">
        <i class="icon-theme-mask icon-theme-mask-cart-shopping"></i>

        <span class="number-cart-product"><?php echo esc_html($cart_count > 9 ? '9+' : $cart_count); ?></span>
    </div>
    <?php
}

// custom mini cart
function basictheme_custom_mini_cart(): void {
    $cart_items = WC()->cart->get_cart();
    ?>
    <div class="mini-cart-dropdown">
        <?php if ( ! empty( $cart_items ) ) : ?>
            <ul class="mini-cart-items">
                <?php foreach ( $cart_items as $cart_item_key => $cart_item ) :
                    $_product   = $cart_item['data'];
                    $product_id = $cart_item['product_id'];

                    if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
                        continue;
                    }

                    $product_name = $_product->is_type('variation') ? get_the_title($_product->get_parent_id()) : $_product->get_name();
                    $thumbnail = $_product->get_image('woocommerce_thumbnail');
                    $product_price = WC()->cart->get_product_price($_product);
                    $product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';

                    $variation_output = '';
                    if ( ! empty( $cart_item['variation'] ) && is_array( $cart_item['variation'] ) ) {
                        foreach ( $cart_item['variation'] as $attr_key => $attr_value ) {
                            $taxonomy = str_replace( 'attribute_', '', $attr_key );
                            $label = wc_attribute_label( $taxonomy, $_product );

                            if ( taxonomy_exists( $taxonomy ) ) {
                                $term = get_term_by( 'slug', $attr_value, $taxonomy );
                                $value = $term && ! is_wp_error( $term ) ? $term->name : $attr_value;
                            } else {
                                $value = $attr_value;
                            }

                            $variation_output .= '<span class="variation-attr d-block">' . esc_html( $label . ': ' . $value ) . '</span> ';
                        }
                    }

                    ?>
                    <li class="item">
                        <div class="thumb">
                            <?php if ( ! empty( $product_permalink ) ) : ?>
                                <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $thumbnail; ?></a>
                            <?php else : ?>
                                <?php echo $thumbnail; ?>
                            <?php endif; ?>
                        </div>

                        <div class="info">
                            <?php if ( ! empty( $product_permalink ) ) : ?>
                                <a href="<?php echo esc_url( $product_permalink ); ?>" class="product-name"><?php echo esc_html( $product_name ); ?></a>
                            <?php else : ?>
                                <span class="product-name"><?php echo esc_html( $product_name ); ?></span>
                            <?php endif; ?>

                            <?php if ( $variation_output ) : ?>
                                <div class="product-variation">
                                    <?php echo wp_kses_post( $variation_output ); ?>
                                </div>
                            <?php endif; ?>

                            <?php echo wc_get_formatted_cart_item_data( $cart_item ); // Biến thể (size, màu...) ?>

                            <div class="quantity">
                                <span><?php echo esc_html( $cart_item['quantity'] . ' × ' ); ?></span>
                                <?php echo wp_kses_post( $product_price ); ?>
                            </div>
                        </div>

                        <div class="action">
                            <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
                               class="remove remove_from_cart_button remove-custom-mini-cart"
                               aria-label="<?php esc_attr_e( 'Xóa sản phẩm', 'basictheme' ); ?>"
                               data-product_id="<?php echo esc_attr( $_product->get_id() ); ?>"
                               data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>"
                               data-product_sku="<?php echo esc_attr( $_product->get_sku() ); ?>">
                                <i class="icon-theme-mask icon-theme-mask-xmark"></i>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="mini-cart-footer mt-3">
                <div class="subtotal d-flex justify-content-between">
                    <span><?php esc_html_e( 'Tổng:', 'basictheme' ); ?></span>
                    <strong><?php echo WC()->cart->get_cart_subtotal(); ?></strong>
                </div>

                <div class="action mt-3 d-flex gap-2 flex-column">
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="button view-cart text-center">
                        <?php esc_html_e( 'Xem giỏ hàng', 'basictheme' ); ?>
                    </a>

                    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="button checkout text-center">
                        <?php esc_html_e( 'Thanh toán', 'basictheme' ); ?>
                    </a>
                </div>
            </div>
        <?php else : ?>
            <p class="empty theme-fs-xs text-center"><?php esc_html_e( 'Không có sản phẩm trong giỏ hàng.', 'basictheme' ); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

/* To ajaxify your cart viewer */
add_filter('woocommerce_add_to_cart_fragments', 'basictheme_add_to_cart_fragment');

if (!function_exists('basictheme_add_to_cart_fragment')) :
    function basictheme_add_to_cart_fragment($fragment): array
    {
        ob_start();
        do_action( 'basictheme_woo_shopping_cart' );
        $fragments['.cart-box'] = ob_get_clean();

        ob_start();
        basictheme_custom_mini_cart();
        $fragments['.mini-cart-dropdown'] = ob_get_clean();

        return $fragments;
    }
endif;
/* End get cart */

// get sidebar active
function basictheme_woo_get_sidebar_active(): array
{
    $sidebar = [];

    if (is_product()) :
        $sidebar['active'] = 'sidebar-wc-product';
        $sidebar['position'] = basictheme_opt(WooSingleOptions::class)::get_product_single_sidebar_position() ?? THEME_SIDEBAR_POSITION_RIGHT;
    else:
        $sidebar['active'] = 'sidebar-wc';
        $sidebar['position'] = basictheme_opt(WooOptions::class)::get_products_sidebar_position() ?? THEME_SIDEBAR_POSITION_RIGHT;
    endif;

    return $sidebar;
}

/* Start Sidebar Shop */
if (!function_exists('basictheme_woo_get_sidebar')) :
    function basictheme_woo_get_sidebar(): void
    {
        $sidebar = basictheme_woo_get_sidebar_active();

        if (!empty($sidebar) && $sidebar['position'] != THEME_SIDEBAR_POSITION_HIDDEN && is_active_sidebar($sidebar['active'])):
            if ($sidebar['position'] == THEME_SIDEBAR_POSITION_LEFT) :
                $class_order = 'order-md-1';
            else:
                $class_order = 'order-md-2';
            endif;
            ?>
            <aside class="col-12 col-md-4 col-lg-3 order-2 <?php echo esc_attr($class_order); ?>">
                <?php dynamic_sidebar($sidebar['active']); ?>
            </aside>
        <?php
        endif;
    }
endif;
/* End Sidebar Shop */

/*
* Lay Out Shop
*/

if (!function_exists('basictheme_woo_before_main_content')) :
    /**
     * Before Content
     * Wraps all WooCommerce content in wrappers which match the theme markup
     */
    function basictheme_woo_before_main_content(): void
    {
        $sidebar = basictheme_woo_get_sidebar_active();
        ?>
        <div class="site-shop">
        <div class="container">
        <div class="row">

        <?php
        /**
         * woocommerce_sidebar hook.
         *
         * @hooked basictheme_woo_sidebar - 10
         */
        do_action('basictheme_woo_sidebar');
        ?>
        <div class="<?php echo !empty($sidebar) && is_active_sidebar($sidebar['active']) && $sidebar['position'] != THEME_SIDEBAR_POSITION_HIDDEN ? 'col-12 col-md-8 col-lg-9 order-1 has-sidebar' : 'col-md-12'; ?>">

        <?php

    }

endif;

if (!function_exists('basictheme_woo_after_main_content')) :
    /**
     * After Content
     * Closes the wrapping divs
     */
    function basictheme_woo_after_main_content(): void
    {
        ?>
        </div><!-- .col-md-9 -->
        </div><!-- .row -->
        </div><!-- .container -->
        </div><!-- .site-shop -->
        <?php
    }

endif;

if (!function_exists('basictheme_woo_product_thumbnail_open')) :
    /**
     * Hook: woocommerce_before_shop_loop_item_title.
     *
     * @hooked basictheme_woo_product_thumbnail_open - 5
     */

    function basictheme_woo_product_thumbnail_open(): void
    {

        ?>
        <div class="item__image">
        <?php

    }

endif;

if (!function_exists('basictheme_woo_product_thumbnail_close')) :
    /**
     * Hook: woocommerce_before_shop_loop_item_title.
     *
     * @hooked basictheme_woo_product_thumbnail_close - 15
     */

    function basictheme_woo_product_thumbnail_close(): void
    {
        do_action('basictheme_woo_button_quick_view');
        ?>
        </div><!-- .item__image -->

        <div class="item__content">
        <?php
    }
endif;

if (!function_exists('basictheme_woo_get_product_title')) :
    /**
     * Hook: woocommerce_shop_loop_item_title.
     *
     * @hooked basictheme_woo_get_product_title - 10
     */

    function basictheme_woo_get_product_title(): void
    {
        ?>
        <h2 class="woocommerce-loop-product__title">
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                <?php the_title(); ?>
            </a>
        </h2>
        <?php
    }
endif;

if (!function_exists('basictheme_woo_after_shop_loop_item_title')) :
    /**
     * Hook: woocommerce_after_shop_loop_item_title.
     *
     * @hooked basictheme_woo_after_shop_loop_item_title - 15
     */
    function basictheme_woo_after_shop_loop_item_title(): void
    {
        ?>
        </div><!-- .item__content -->
        <?php
    }
endif;

if (!function_exists('basictheme_woo_loop_add_to_cart_open')) :
    /**
     * Hook: woocommerce_after_shop_loop_item.
     *
     * @hooked basictheme_woo_loop_add_to_cart_open - 4
     */

    function basictheme_woo_loop_add_to_cart_open(): void
    {
        ?>
        <div class="item__action">
        <?php
    }

endif;

if (!function_exists('basictheme_woo_loop_add_to_cart_close')) :
    /**
     * Hook: woocommerce_after_shop_loop_item.
     *
     * @hooked basictheme_woo_loop_add_to_cart_close - 12
     */

    function basictheme_woo_loop_add_to_cart_close(): void
    {
        ?>
        </div><!-- .item__action -->
        <?php
    }

endif;

if (!function_exists('basictheme_woo_before_shop_loop_item')) :
    /**
     * Hook: woocommerce_before_shop_loop_item.
     *
     * @hooked basictheme_woo_before_shop_loop_item - 5
     */
    function basictheme_woo_before_shop_loop_item(): void
    {
        ?>

        <div class="item">

        <?php
    }
endif;

if (!function_exists('basictheme_woo_after_shop_loop_item')) :
    /**
     * Hook: woocommerce_after_shop_loop_item.
     *
     * @hooked basictheme_woo_after_shop_loop_item - 15
     */
    function basictheme_woo_after_shop_loop_item(): void
    {
        ?>

        </div><!-- .item -->

        <?php
    }
endif;

if (!function_exists('basictheme_woo_before_shop_loop_open')) :
    /**
     * Before Shop Loop
     * woocommerce_before_shop_loop hook.
     *
     * @hooked basictheme_woo_before_shop_loop_open - 5
     */
    function basictheme_woo_before_shop_loop_open(): void
    {

        ?>
        <div class="site-shop__result-count-ordering d-flex align-items-center justify-content-between">
        <?php

    }
endif;

if (!function_exists('basictheme_woo_before_shop_loop_close')) :
    /**
     * Before Shop Loop
     * woocommerce_before_shop_loop hook.
     *
     * @hooked basictheme_woo_before_shop_loop_close - 35
     */
    function basictheme_woo_before_shop_loop_close(): void
    {

        ?>
        </div><!-- .site-shop__result-count-ordering -->
        <?php
    }

endif;

/*
* Single Shop
*/

if (!function_exists('basictheme_woo_before_single_product')) :

    /**
     * Before Content Single  product
     *
     * woocommerce_before_single_product hook.
     *
     * @hooked basictheme_woo_before_single_product - 5
     */

    function basictheme_woo_before_single_product(): void
    {

        ?>
        <div class="site-shop-single">
        <?php

    }

endif;

if (!function_exists('basictheme_woo_after_single_product')) :
    /**
     * After Content Single  product
     *
     * woocommerce_after_single_product hook.
     *
     * @hooked basictheme_woo_after_single_product - 30
     */

    function basictheme_woo_after_single_product(): void
    {

        ?>
        </div><!-- .site-shop-single -->
        <?php

    }

endif;

if (!function_exists('basictheme_woo_before_single_product_summary_open_warp')) :

    /**
     * Before single product summary
     * woocommerce_before_single_product_summary hook.
     *
     * @hooked basictheme_woo_before_single_product_summary_open_warp - 1
     */

    function basictheme_woo_before_single_product_summary_open_warp(): void
    {

        ?>
        <div class="site-shop-single__warp">
        <?php

    }

endif;

if (!function_exists('basictheme_woo_after_single_product_summary_close_warp')) :

    /**
     * After single product summary
     * woocommerce_after_single_product_summary hook.
     *
     * @hooked basictheme_woo_after_single_product_summary_close_warp - 5
     */

    function basictheme_woo_after_single_product_summary_close_warp(): void
    {

        ?>
        </div><!-- .site-shop-single__warp -->
        <?php

    }

endif;

if (!function_exists('basictheme_woo_before_single_product_summary_open')) :
    /**
     * woocommerce_before_single_product_summary hook.
     *
     * @hooked basictheme_woo_before_single_product_summary_open - 5
     */

    function basictheme_woo_before_single_product_summary_open(): void
    {

        ?>
        <div class="site-shop-single__gallery-box">
        <?php

    }
endif;

if (!function_exists('basictheme_woo_before_single_product_summary_close')) :

    /**
     * woocommerce_before_single_product_summary hook.
     *
     * @hooked basictheme_woo_before_single_product_summary_close - 30
     */

    function basictheme_woo_before_single_product_summary_close(): void
    {

        ?>
        </div><!-- .site-shop-single__gallery-box -->
        <?php

    }
endif;