<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php bloginfo('description'); ?>" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="main-warp">
    <?php
    if ( !is_404() ) :
        get_template_part('template-parts/header/inc', 'layout');
    endif;
    ?>
    <!-- open .sticky-footer -->
    <main class="sticky-footer">
