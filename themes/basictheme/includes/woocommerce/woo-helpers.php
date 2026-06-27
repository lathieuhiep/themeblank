<?php
// setup shop
use ExtendSite\Admin\Options\Modules\WooOptions;
use ExtendSite\Admin\Options\Modules\WooSingleOptions;

function basictheme_shop_setup(): void
{
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'basictheme_shop_setup');

// set col product list
function basictheme_woo_override_product_list_class($html): array|string
{
    if (is_product() && did_action('woocommerce_after_single_product_summary')) {
        $per_row = basictheme_opt(WooSingleOptions::class)::get_product_single_row_columns() ?? 3;
        $per_row_classes = basictheme_get_responsive_row_class($per_row);
    } else {
        $per_row = basictheme_opt(WooOptions::class)::get_product_row_columns() ?? 4;
        $per_row_classes = basictheme_get_responsive_row_class($per_row);
    }

    // remove class columns-x
    $html = preg_replace('/columns-\d+/', '', $html);

    // add class custom
    return str_replace('class="products', 'class="products gap-6 ' . $per_row_classes, $html);
}
add_filter('woocommerce_product_loop_start', 'basictheme_woo_override_product_list_class', 20, 1);

// limit product
function basictheme_show_products_per_page()
{
    return basictheme_opt(WooOptions::class)::get_products_per_page() ?? 12;
}
add_filter('loop_shop_per_page', 'basictheme_show_products_per_page');

// set product related
function basictheme_woo_related_products_args($args) {
    $args['posts_per_page'] = basictheme_opt(WooSingleOptions::class)::get_product_single_related_count() ?? 3;

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'basictheme_woo_related_products_args', 20);

// simple products
function basictheme_woo_quantity_input_args( $args, $product ): array
{
    $args['classes'][] = 'custom-qty-input';

    return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'basictheme_woo_quantity_input_args', 10, 2 );

// variations
function basictheme_woo_available_variation( $args ): array
{
    $args['classes'][] = 'custom-qty-input';

    return $args;
}
add_filter( 'woocommerce_available_variation', 'basictheme_woo_available_variation' );

// add button quantity minus
function basictheme_woo_quantity_minus_button(): void
{
?>
    <button type="button" class="qty-btn qty-minus">
        <i class="icon-theme-mask icon-theme-mask-minus"></i>
    </button>
<?php
}
add_action( 'woocommerce_before_quantity_input_field', 'basictheme_woo_quantity_minus_button' );

// add button quantity plus
function basictheme_woo_quantity_plus_button(): void
{
?>
    <button type="button" class="qty-btn qty-plus">
        <i class="icon-theme-mask icon-theme-mask-plus"></i>
    </button>
<?php
}
add_action( 'woocommerce_after_quantity_input_field', 'basictheme_woo_quantity_plus_button' );