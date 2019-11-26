<?php
require_once dirname(__FILE__, 4) . '/wp-load.php';
#require_once dirname(__FILE__,4) . '/public/wp-load.php';
require_once plugin_dir_path(__FILE__)."onelogin-saml-sso/php/functions.php";
require_once plugin_dir_path(__FILE__)."onelogin-saml-sso/php/configuration.php";

/*
function acs_shutdown_handle(){
    wp_die('','SAML Error', array(
        //'link_url' => '#',
        //'link_text' => 'link',
        //'back_link' => true,
        'exit' => false,
    ));
}
register_shutdown_function('acs_shutdown_handle');
*/

saml_acs();