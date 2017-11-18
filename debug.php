<?php
if(is_file('debug')) {
    $variable = file_get_contents('debug');
} else {
    $variable = '';
}
ob_start();

echo '$_REQUEST';
var_dump($_REQUEST);

echo "\r\n\r\n";

echo '$_GET';
var_dump($_GET);

echo "\r\n\r\n";

echo '$_POST';
var_dump($_POST);

echo "\r\n\r\n";

echo '$_SERVER[\'QUERY_STRING\']';
var_dump($_SERVER['QUERY_STRING']);

echo "\r\n\r\n";

echo '$_SERVER[\'REMOTE_USER\']';
var_dump($_SERVER['REMOTE_USER']);

echo "\r\n\r\n";

echo '$_SERVER';
var_dump($_SERVER);

$variable2 = ob_get_contents();
ob_end_clean();

file_put_contents('debug', $variable . "\r\n" . $variable2);