<?php
/**
 * Alternative login endpoint.
 */

require_once dirname( __FILE__, 4 ) . '/wp-load.php';
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
if ( is_plugin_active( $okta_privacy_plugin) ) {
	include_once plugin_dir_path( dirname( __FILE__ ) ) . $okta_privacy_plugin;
	if ( function_exists( 'run_wordpress_saml_private_blog' ) ) {
		run_wordpress_saml_private_blog();
	}
}

$okta_privacy_plugin = 'okta-privacy/okta-privacy.php';
if ( is_plugin_active( $okta_privacy_plugin)) {
	include_once plugin_dir_path( dirname( __FILE__ ) ) . $okta_privacy_plugin;
	if ( class_exists( 'OktaPrivateISU' ) ) {
		new OktaPrivateISU();
	}
}
/*
 * ------------------------------------------------------------------------------------------------------
 * Run WordPress SAML acs endpoint.
 */
saml_acs();
