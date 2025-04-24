<?php
/**
 * Plugin Name: Elementor Video Popup Addon
 * Description: Adds a video container widget with popup functionality.
 * Version: 2.0.1
 * Author: Sheikh Abdul Fahad
 * Text Domain: elementor-video-popup-addon
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include necessary files
function evpa_include_files() {
    require_once( plugin_dir_path( __FILE__ ) . 'includes/class-elementor-video-popup.php' );
}
add_action( 'elementor/widgets/widgets_registered', 'evpa_include_files' );

// Enqueue Scripts and Styles
function evpa_enqueue_scripts() {
    wp_enqueue_script( 'evpa-popup-js', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', ['jquery'], null, true );
    wp_enqueue_style( 'evpa-popup-css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'evpa_enqueue_scripts' );

// Initialize the widget class
function evpa_register_video_popup_widget( $widgets_manager ) {
    // Check if Elementor is loaded
    if ( defined( 'ELEMENTOR_PATH' ) ) {
        // Register widget class
        require_once( plugin_dir_path( __FILE__ ) . 'includes/class-elementor-video-popup.php' );
        $widgets_manager->register( new \Elementor_Video_Popup_Widget() );
    }
}
add_action( 'elementor/widgets/widgets_registered', 'evpa_register_video_popup_widget' );
