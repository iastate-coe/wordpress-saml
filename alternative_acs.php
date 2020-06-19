<?php
require_once dirname(__FILE__, 4) . '/wp-load.php';
#require_once dirname(__FILE__,4) . '/public/wp-load.php';
require_once plugin_dir_path(__FILE__)."onelogin-saml-sso/php/functions.php";
require_once plugin_dir_path(__FILE__)."onelogin-saml-sso/php/configuration.php";

$blog = get_site_by_path('www.scotchbox.local',$_REQUEST['RelayState']);
if($blog instanceof WP_Site){
    switch_to_blog($blog->blog_id);
}
$is_active = is_plugin_active( 'okta-privacy/okta-privacy.php');
if ($is_active) {
    include_once plugin_dir_path(dirname(__FILE__))."isu-okta-privacy/isu-okta-privacy.php";
}

saml_acs();