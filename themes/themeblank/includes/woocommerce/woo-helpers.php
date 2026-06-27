<?php
// setup shop
use ExtendSite\Admin\Options\Modules\WooOptions;
use ExtendSite\Admin\Options\Modules\WooSingleOptions;

function themeblank_shop_setup(): void
{
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'themeblank_shop_setup');

// set col product list
function themeblank_woo_override_product_list_class($html): array|string
{
    if (is_product() && did_action('woocommerce_after_single_product_summary')) {
        $per_row = themeblank_opt(WooSingleOptions::class)::get_product_single_row_columns() ?? 3;
        $per_row_classes = themeblank_get_responsive_row_class($per_row);
    } else {
        $per_row = themeblank_opt(WooOptions::class)::get_product_row_columns() ?? 4;
        $per_row_classes = themeblank_get_responsive_row_class($per_row);
    }

    // remove class columns-x
    $html = preg_replace('/columns-\d+/', '', $html);

    // add class custom
    return str_replace('class="products', 'class="products gap-6 ' . $per_row_classes, $html);
}
add_filter('woocommerce_product_loop_start', 'themeblank_woo_override_product_list_class', 20, 1);

// limit product
function themeblank_show_products_per_page()
{
    return themeblank_opt(WooOptions::class)::get_products_per_page() ?? 12;
}
add_filter('loop_shop_per_page', 'themeblank_show_products_per_page');

// set product related
function themeblank_woo_related_products_args($args) {
    $args['posts_per_page'] = themeblank_opt(WooSingleOptions::class)::get_product_single_related_count() ?? 3;

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'themeblank_woo_related_products_args', 20);

// simple products
function themeblank_woo_quantity_input_args( $args, $product ): array
{
    $args['classes'][] = 'custom-qty-input';

    return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'themeblank_woo_quantity_input_args', 10, 2 );

// variations
function themeblank_woo_available_variation( $args ): array
{
    $args['classes'][] = 'custom-qty-input';

    return $args;
}
add_filter( 'woocommerce_available_variation', 'themeblank_woo_available_variation' );

// add button quantity minus
function themeblank_woo_quantity_minus_button(): void
{
?>
    <button type="button" class="qty-btn qty-minus">
        <i class="icon-theme-mask icon-theme-mask-minus"></i>
    </button>
<?php
}
add_action( 'woocommerce_before_quantity_input_field', 'themeblank_woo_quantity_minus_button' );

// add button quantity plus
function themeblank_woo_quantity_plus_button(): void
{
?>
    <button type="button" class="qty-btn qty-plus">
        <i class="icon-theme-mask icon-theme-mask-plus"></i>
    </button>
<?php
}
add_action( 'woocommerce_after_quantity_input_field', 'themeblank_woo_quantity_plus_button' );