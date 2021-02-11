<?php
/**
 * Alternative login endpoint.
 */

$down_directory = dirname( __FILE__, 4 );
if ( file_exists( $down_directory . '/wp-load.php' ) ) {
	require_once $down_directory . '/wp-load.php';
} else {
	// specific dev instance.
	require_once $down_directory . '/public/wp-load.php';
}
require_once plugin_dir_path( __FILE__ ) . 'onelogin-saml-sso/php/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'onelogin-saml-sso/php/configuration.php';

/*
 * ------------------------------------------------------------------------------------------------------
 * Switch to active blog from the RelayState Okta sends back.
 * Especially important for multisite and logging in on a sub-site.
 */
$relay_state = filter_input( INPUT_POST, 'RelayState', FILTER_SANITIZE_URL );
$parsed_url  = wp_parse_url( $relay_state );
$blog        = ! empty( $parsed_url ) ? get_site_by_path( $parsed_url['host'], $parsed_url['path'] ) : null;

if ( $blog instanceof WP_Site ) {
	switch_to_blog( $blog->blog_id );
}

/*
 * ------------------------------------------------------------------------------------------------------
 * load optional plugin setup if active.
 * @todo Find a better way to load plugins using alternate access point
 */
$okta_privacy_plugin = 'wordpress-saml-private-blog/wordpress-saml-private-blog.php';
$is_active           = in_array( $okta_privacy_plugin, (array) get_option( 'active_plugins', array() ), true );

if ( $is_active ) {
	include_once plugin_dir_path( dirname( __FILE__ ) ) . $okta_privacy_plugin;
	run_wordpress_saml_private_blog();
}

$okta_privacy_plugin = 'okta-privacy/okta-privacy.php';
$is_active           = in_array( $okta_privacy_plugin, (array) get_option( 'active_plugins', array() ), true );

if ( $is_active ) {
	include_once plugin_dir_path( dirname( __FILE__ ) ) . $okta_privacy_plugin;
	new OktaPrivateISU();
}
/*
 * ------------------------------------------------------------------------------------------------------
 * Run WordPress SAML acs endpoint.
 */
saml_acs();
