<?php

$debug = false;
//include 'debug.php';// generates file 'debug' with a log of all requests which where made
if(filter_has_var(INPUT_GET, 'debug')) {
    $debug = true;
}

require_once 'function.php';
require_once 'Setting.php';

try {
    $setting = new \Autodiscover\Setting();
} catch(Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    exit;
}


if(filter_has_var(INPUT_GET, 'mail_client')) {

    switch(filter_input(INPUT_GET, 'mail_client', FILTER_SANITIZE_STRING)) {
        case 'thunderbird':
            if(!$debug) {
                header('Content-Type:text/xml');
            }
            require 'tpl/config-v1.1.xml.php';
        break;

        case 'outlook_json':
            if(!$debug) {
                header("Content-type:application/json");
            }
            require 'tpl/autodiscover.json.php';
        break;

        default:
        case 'outlook_xml':
            if(!$debug) {
                header('Content-Type:text/xml');
            }
            require 'tpl/autodiscover.xml.php';
        break;
    }
}