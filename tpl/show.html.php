<?php
/**
 * @var \Bemit\Autodiscover\Show $show
 */
$asset_folder = 'tpl/asset/'
?>
<!DOCTYPE html>
<html lang="de" dir="ltr">
<head>
    <meta charset="UTF-8"/>
    <title><?= $show->getContent()->getHead('title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <?php
    if($debug) {
        ?>
        <link rel="stylesheet" type="text/css" href="https://<?= $show->setting->getApp()
            ->getActiveHostname(false) . '/' . $asset_folder ?>style.css">
        <?php
    } else {
        ?>
        <style><?php echo str_replace(
                ['  ', ' {', ': '],
                ['', '{', ':',],
                preg_replace("/\r|\n/", '', file_get_contents($asset_folder . 'style.min.css'))); ?></style>
        <?php
    }
    if($show->getContent()->getModule(['google-recaptcha', 'active'])) {
        ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <?php
    }
    ?>
</head>
<body>

<section class="container">
    <header class="header">
        <div class="head-logo">
            <img src="https://<?= $show->setting->getApp()->getActiveHostname(false) . '/' .$asset_folder ?>media/logo.png"/>
        </div>
    </header>
    <main class="content">
        <h1><?= $show->getContent()->getCenter('h1') ?></h1>
        <p class="intro"><?= $show->getContent()->getCenter('intro') ?></p>

        <form action="https://<?= $show->setting->getApp()
            ->getActiveHostname(false) ?>/api/get/mailbox/information" class="trigger-submit" method="post" data-success="apply">
            <div class="input-group">
                <div class="label">
                    <label for="form-email-inp"><?= $show->getContent()->getForm('label-input-email') ?></label>
                </div>
                <div class="input">
                    <input type="email" name="email" value="<?= (is_string($show->setting->getUser()->getEmail()) && !empty($show->setting->getUser()
                        ->getEmail()) ? $show->setting->getUser()->getEmail() : '') ?>" required id="form-email-inp"/>
                </div>
            </div>

            <?php if($show->getContent()->getModule(['google-recaptcha', 'active'])) { ?>
                <div class="g-recaptcha-wrapper">
                    <div
                            class="g-recaptcha"
                            data-sitekey="<?= $show->getContent()->getModule(['google-recaptcha', 'key', 'site']) ?>"
                    ></div>
                </div>
            <?php } ?>

            <div class="btn-wrapper">
                <button class="" type="submit"><?= $show->getContent()->getForm('label-btn-check') ?></button>
            </div>
        </form>
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
<div id="response">
    <div class="wrapper">
        <div class="body">
            <div class="close">x</div>
            <h3><?= $show->getContent()->getResponse('headline') ?></h3>
            <div class="row">
                <div><?= $show->getContent()->getResponse('label-url') ?></div>
                <div data-dummy="info-url"></div>
            </div>
            <div class="row">
                <div><?= $show->getContent()->getResponse('label-domain') ?></div>
                <div data-dummy="info-domain"></div>
            </div>

            <div class="group-label"><?= $show->getContent()->getResponse(['imap', 'label']) ?></div>
            <div class="row">
                <div><?= $show->getContent()->getResponse(['imap', 'label-host']) ?></div>
                <div data-dummy="server-imap-host"></div>
            </div>
            <div class="row">
                <div><?= $show->getContent()->getResponse(['imap', 'label-port']) ?></div>
                <div data-dummy="server-imap-port"></div>
            </div>
            <div class="row">
                <div><?= $show->getContent()->getResponse(['imap', 'label-socket']) ?></div>
                <div data-dummy="server-imap-socket"></div>
            </div>

            <div class="group-label"><?= $show->getContent()->getResponse(['smtp', 'label']) ?></div>
            <div class="row">
                <div><?= $show->getContent()->getResponse(['smtp', 'label-host']) ?></div>
                <div data-dummy="server-smtp-host"></div>
            </div>
            <div class="row">
                <div><?= $show->getContent()->getResponse(['smtp', 'label-port']) ?></div>
                <div data-dummy="server-smtp-port"></div>
            </div>
            <div class="row">
                <div><?= $show->getContent()->getResponse(['smtp', 'label-socket']) ?></div>
                <div data-dummy="server-smtp-socket"></div>
            </div>

            <div class="group-label"><?= $show->getContent()->getResponse('label-misc') ?></div>
            <div class="row">
                <div><?= $show->getContent()->getResponse('label-domain-required') ?></div>
                <div data-dummy="domain-required"></div>
            </div>
            <div class="row">
                <div><?= $show->getContent()->getResponse('label-login-name-required') ?></div>
                <div data-dummy="login-name-required"></div>
            </div>
        </div>
    </div>
</div>
<?php
if($debug) {
    ?>
    <script src="https://<?= $show->setting->getApp()->getActiveHostname(false) . '/' .$asset_folder ?>js.js"></script>
<?php
}else{
?>
    <script src="https://<?= $show->setting->getApp()->getActiveHostname(false) . '/' .$asset_folder ?>js.min.js"></script>
    <?php
}
?>
</body>
</html>
