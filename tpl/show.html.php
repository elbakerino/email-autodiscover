<?php
/**
 * @var \Bemit\Autodiscover\Show $show
 */
$asset_folder = '/tpl/asset/'
?>
<!DOCTYPE html>
<html lang="de" dir="ltr">
<head>
    <meta charset="UTF-8"/>
    <title><?= $show->getContent()->getHead('title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <?php
    if($debug){
        ?>
        <link rel="stylesheet" type="text/css" href="https://<?= $show->setting->getApp()->getActiveHostname(false) . $asset_folder ?>style.css">
        <?php
    }else{
        ?>
        <link rel="stylesheet" type="text/css" href="https://<?= $show->setting->getApp()->getActiveHostname(false) . $asset_folder ?>style.min.css">
        <?php
    }
    ?>
</head>
<body>

<section class="container">
    <header class="header">
        <div class="head-logo row">
            <a class="col-24 col-sm-2 gutter-thinner" href="https://automat-sh.bemit.eu" target="_blank"><img src="https://<?= $show->setting->getApp()
                    ->getActiveHostname(false) . $asset_folder ?>media/logo.png"/></a>
        </div>
    </header>
    <main class="content">
        <div class="col-24">
            <h1><?= $show->getContent()->getCenter('h1') ?></h1>
            <p class="intro"><?= $show->getContent()->getCenter('intro') ?></p>

            <form action="https://<?= $show->setting->getApp()->getActiveHostname(false)?>/api/get/mailbox" class="trigger-submit" method="post">
                <div class="input-group">
                    <div class="label">
                        <label for="form-email-inp"><?= $show->getContent()->getForm('label-input-email') ?></label>
                    </div>
                    <div class="input">
                        <input type="email" name="email" value="" required id="form-email-inp"/>
                    </div>
                </div>
                <div class="btn-wrapper">
                    <button class="" type="submit"><?= $show->getContent()->getForm('label-btn-check') ?></button>
                </div>
            </form>
        </div>
    </main>
</section>
<footer class="footer">
    <div class="row copyright">
        <?= $show->getContent()->getFooter('copyright') ?>
    </div>
    <div class="row powered-by">
        <?= $show->getContent()->getFooter('powered-by') ?>
    </div>
</footer>
<div id="response-tpl">
    <div>Response</div>
    <div>
        <div>URL</div>
        <div data-dummy="info-url">URL</div>
    </div>
    <div>
        <div>domain</div>
        <div data-dummy="info-domain">domain</div>
    </div>

    <div>server-imap</div>
    <div>
        <div>server-imap-host</div>
        <div data-dummy="server-imap-host">server-imap-host</div>
    </div>
    <div>
        <div>server-imap-port</div>
        <div data-dummy="server-imap-port">server-imap-port</div>
    </div>
    <div>
        <div>server-imap-socket</div>
        <div data-dummy="server-imap-socket">server-imap-socket</div>
    </div>

    <div>server-smtp</div>
    <div>
        <div>server-smtp-host</div>
        <div data-dummy="server-smtp-host">server-smtp-host</div>
    </div>
    <div>
        <div>server-smtp-port</div>
        <div data-dummy="server-smtp-port">server-smtp-port</div>
    </div>
    <div>
        <div>server-smtp-socket</div>
        <div data-dummy="server-smtp-socket">server-smtp-socket</div>
    </div>

    <div>misc</div>
    <div>
        <div>domain-required</div>
        <div data-dummy="domain-required">domain-required</div>
    </div>
    <div>
        <div>login_name_required</div>
        <div data-dummy="login_name_required">domain-required</div>
    </div>
</div>
<?php
if($debug){
    ?>
    <script src="https://<?= $show->setting->getApp()->getActiveHostname(false) . $asset_folder ?>js.js"></script>
    <?php
}else{
    ?>
    <script src="https://<?= $show->setting->getApp()->getActiveHostname(false) . $asset_folder ?>js.min.js"></script>
    <?php
}
?>
</body>
</html>
