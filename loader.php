<?php
/*
Plugin Name: OneLogin SAML SSO
Plugin URI: https://github.com/onelogin/wordpress-saml
Description: Give users secure one-click access to WordPress from OneLogin. This SAML integration eliminates passwords and allows you to authenticate users against your existing Active Directory or LDAP server as well increase security using YubiKeys or VeriSign VIP Access, browser PKI certificates and OneLogin's flexible security policies. OneLogin is pre-integrated with thousands of apps and handles all of your SSO needs in the cloud and behind the firewall.
Author: OneLogin, Inc.
Version: 3.2.1
Author URI: http://www.onelogin.com
Network: true
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

if ( false === defined( 'SAML_LOGIN_COOKIE' ) ) {
    define( 'SAML_LOGIN_COOKIE', 'okta_login' );
}
if ( false === defined( 'SAML_NAMEID_COOKIE' ) ) {
    define( 'SAML_NAMEID_COOKIE', 'okta_nameid' );
}
if ( false === defined( 'SAML_SESSIONINDEX_COOKIE' ) ) {
    define( 'SAML_SESSIONINDEX_COOKIE', 'okta_sessionindex' );
}
if ( false === defined( 'SAML_NAMEID_FORMAT_COOKIE' ) ) {
    define( 'SAML_NAMEID_FORMAT_COOKIE', 'okta_nameid_format' );
}

/**
 * Filters attributes from SAML Auth
 *
 * @param array $attributes
 * @param OneLogin\Saml2\Auth $auth
 *
 * @return array
 */
function isu_saml_get_attributes_filter($attributes,$auth)
{
    $attributes['Netid'] = explode('@',$auth->getNameId(),2);
    return $attributes;
}
add_filter('onelogin_saml_get_attributes', 'isu_saml_get_attributes_filter',10,2);

require_once plugin_dir_path(__FILE__) . "onelogin-saml-sso/onelogin_saml.php";

if (is_multisite()) {
    function isu_saml_network_settings()
    {
        remove_submenu_page('network_saml_settings', 'network_saml_injection');
        remove_submenu_page('network_saml_settings', 'network_saml_enabler');
        remove_submenu_page('network_saml_settings', 'network_saml_global_settings');
    }
    add_action('network_admin_menu', 'isu_saml_network_settings');

    function isu_saml_admin_settings()
    {
        remove_submenu_page('options-general.php', 'onelogin_saml_configuration');
        add_submenu_page( 'options-general.php', 'SSO/SAML Settings', 'SSO/SAML Settings', 'manage_network_options', 'onelogin_saml_configuration', 'onelogin_saml_configuration_render');
    }
    add_action('admin_menu', 'isu_saml_admin_settings');
}
