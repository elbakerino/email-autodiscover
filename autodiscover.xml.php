<?php

//include 'debug.php';// generates debug with a log of all requests which where made

require_once 'Setting.php';

function isOnOrOff($value) {
    return ($value === true) ? 'on' : 'off';
}

function getEmail() {
    if(filter_has_var(INPUT_GET, 'email')) {
        $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
    } else if(filter_has_var(INPUT_SERVER, 'HTTP_X_USER_IDENTITY')) {
        $email = filter_input(INPUT_SERVER, 'HTTP_X_USER_IDENTITY', FILTER_SANITIZE_EMAIL);
    } else {
        $email = null;
    }
    return $email;
}

try {
    $setting = new \Autodiscover\Setting();
} catch(Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    exit;
}

header('Content-Type:text/xml');

if(filter_has_var(INPUT_GET, 'exchange')) {
    if(filter_input(INPUT_GET, 'exchange', FILTER_SANITIZE_STRING) === 'false') {
        require 'tpl/config-v1.1.xml.php';
    } else {
        require 'tpl/autodiscover.xml.php';
    }
}