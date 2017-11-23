<?php

$debug = false;
//include 'debug.php';// generates file 'debug' with a log of all requests which where made
if(filter_has_var(INPUT_GET, 'debug')) {
    $debug = true;
}

require_once 'function.php';
require_once 'src/autoload.php';
require_once 'vendor/autoload.php';

use Bemit\Autodiscover\Api;
use Bemit\Autodiscover\App;
use Bemit\Autodiscover\Setting;
use Bemit\Autodiscover\Show;
use Bemit\Autodiscover\User;

try {
    $setting = new Setting();
    $user = new User();
    $app = new App();
    $app->setActiveUrl();

    $setting->setUser($user);
    $setting->setApp($app);
} catch(Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    exit;
}


if(filter_has_var(INPUT_GET, 'mail_client')) {
    // when the app is called from a mailclient or someone trying to access the xml files
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

} else if(filter_has_var(INPUT_GET, 'frontend')) {
    // when the app is called from a user or from within the users frontend
    switch(filter_input(INPUT_GET, 'frontend', FILTER_SANITIZE_STRING)) {
        case 'show':
            $show = new Show($setting);
            $show->render($debug);
            break;

        case 'api':
            $api = new Api($setting);
            $api->determineCall($debug);

            // remove everything else from ob that is possible not json
            if(!$debug) {
                ob_end_clean();
                ob_start();
            }
            $api->respond();
            if(!$debug) {
                $output = ob_get_contents();
                ob_end_clean();
                ob_start();
                echo $output;
            }

            break;
    }
}