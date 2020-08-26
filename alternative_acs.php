<?php
require_once dirname(__FILE__, 4) . '/wp-load.php';
#require_once dirname(__FILE__, 4) . '/public/wp-load.php';
require_once plugin_dir_path(__FILE__) . "onelogin-saml-sso/php/functions.php";
require_once plugin_dir_path(__FILE__) . "onelogin-saml-sso/php/configuration.php";

$serverHost = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING);
$blog = get_site_by_path($serverHost, $_REQUEST['RelayState']);
if ($blog instanceof WP_Site) {
    switch_to_blog($blog->blog_id);
}
if (function_exists('is_plugin_active')) {
    $is_active = is_plugin_active('okta-privacy/okta-privacy.php');
    if ($is_active) {
        include_once plugin_dir_path(dirname(__FILE__)) . "okta-privacy/okta-privacy.php";
    }
}

saml_acs();