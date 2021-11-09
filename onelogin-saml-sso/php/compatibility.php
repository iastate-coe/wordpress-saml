<?php

if (!function_exists('wp_roles'))
{
    function wp_roles() {
        global $wp_roles;

        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles(); // Override ok.
        }
        return $wp_roles;
    }
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
