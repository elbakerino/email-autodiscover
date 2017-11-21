<?php
ob_start();

/**
 * @var \Bemit\Autodiscover\Setting $setting
 */
$protocol = filter_input(INPUT_GET, 'Protocol', FILTER_SANITIZE_STRING);

$protocol_supported = [
    'AutodiscoverV1',
];

?>
<?php if($debug) { ?>
    <!DOCTYPE html>
    <html>
    <script>
<?php } ?>
<?php if($debug) { ?> response = <?php } ?>{

<?php
switch($protocol) {
    /*
     * todo
    case 'ActiveSync':
        echo '"Protocol":"ActiveSync","Url":"' . $setting->getActiveSynchUrl() . '"';
    break;
    */
    case 'AutodiscoverV1':
        echo '"Protocol":"AutodiscoverV1","Url":"https://' .  $setting->getUser()->getHostname() . '/autodiscover/autodiscover.xml"';
    break;

    default:
        http_response_code(400);
        echo '"ErrorCode":"InvalidProtocol","ErrorMessage":"The given protocol value \u0027' . $protocol . '\u0027 is invalid. Supported values are \u0027' . implode(',', $protocol_supported) . '\u0027"';
    break;
}
?>

<?php if($debug) { ?>}; <?php
} else {
    echo '}';
} ?>
<?php if($debug) { ?>
    </script>
    </html>
    <?php
    ?>
<?php }
$json_content = ob_get_contents();
ob_end_clean();
if(!$debug) {
    // when called not with a get param debug it makes the output minified-like
    echo str_replace(
        ['  ', ' {', ': '], ['', '{', ':',],
        preg_replace("/\r|\n/", '', $json_content));
} else {
    echo $json_content;
}