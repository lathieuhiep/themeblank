<?php
namespace ExtendSite\Core;

use ExtendSite\Constants\Config;
use ExtendSite\Helpers\ESHelpers;
use ExtendSite\PostType\PortfolioPostType;

defined('ABSPATH') || exit;

class Enqueue
{
    /**
     * Boot the Enqueue class.
     */
    public static function boot(): void
    {
        add_action('login_enqueue_scripts', [self::class, 'enqueue_scripts_login']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue_scripts_backend']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_scripts_frontend']);
    }

    /**
     * Enqueue scripts login
     */
    public static function enqueue_scripts_login(): void
    {
        wp_enqueue_style(
            'es-login',
            Config::$url . 'assets/css/backend/custom-login.min.css',
            [],
            Config::VERSION
        );
    }

    /**
     * Enqueue scripts backend
     */
    public static function enqueue_scripts_backend()
    {}

    /**
     * Enqueue scripts frontend
     */
    public static function enqueue_scripts_frontend(): void
    {
        // Check if Elementor is used to build the current page
        $page_builder = ESHelpers::check_elementor_builder();

        if ( $page_builder ) {
            // load frontend style
            wp_enqueue_style('es-addons-elementor',
                Config::$url . 'assets/css/frontend/addons-elementor.min.css',
                [],
                Config::VERSION
            );

            // load frontend script
            wp_register_script( 'es-addons-elementor',
                Config::$url . 'assets/js/frontend/addons-elementor.min.js',
                array( 'jquery', 'swiper' ),
                Config::VERSION,
                true
            );
        }

        if ( is_singular(PortfolioPostType::SLUG) ) {
            // load portfolio style
            wp_enqueue_style('es-single-portfolio',
                Config::$url . 'assets/css/frontend/post-type/portfolio/single-portfolio.min.css',
                [],
                Config::VERSION
            );
        }
    }
}