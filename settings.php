<?php
/*
 * File includes & settings
 */

if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// Plugin helpers
require plugin_dir_path( __FILE__ )  . '/lib/helpers/misc.php';
require plugin_dir_path( __FILE__ )  . '/lib/helpers/options-page.php';


function wpspx_login_frontend_scripts()
{
	wp_register_style('wpspx_login_css', WPSPX_LOGIN_PLUGIN_URL . 'lib/assets/css/wpspx-login.css', false, '1.0');
    wp_enqueue_style( 'wpspx_login_css' );

    wp_enqueue_script( 'wpspx_login_model', WPSPX_LOGIN_PLUGIN_URL . 'lib/assets/js/wpspx-login-model-min.js', array('jquery'), null, true );
}

function wpspx_login_enqueue_color_picker() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wpspx_login_js', WPSPX_LOGIN_PLUGIN_URL . 'lib/assets/js/wpspx-login-min.js', array( 'wp-color-picker' ), false, true );
}
