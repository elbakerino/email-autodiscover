<?php
echo '<?xml version = "1.0" encoding = "utf-8"?>';
/**
 * @var \Bemit\Autodiscover\Setting $setting
 */
?>

<clientConfig version="1.1">
    <emailProvider id="<?= $setting->getInfoDomain('user', $setting->getUser()->getEmail()) ?>">
        <domain><?= $setting->getInfoDomain('user', $setting->getUser()->getEmail()) ?></domain>
        <displayName><?= $setting->getInfoName('user', $setting->getUser()->getEmail()) ?></displayName>
        <displayShortName><?= $setting->getInfoName('user', $setting->getUser()->getEmail()) ?></displayShortName>
        <incomingServer type="imap">
            <hostname><?= $setting->getImapHost('user', $setting->getUser()->getEmail()) ?></hostname>
            <port><?= $setting->getImapPort('user', $setting->getUser()->getEmail()) ?></port>
            <socketType><?= $setting->getImapSocket('user', $setting->getUser()->getEmail()) ?></socketType>
            <authentication>password-cleartext</authentication>
            <username><?= $setting->getUser()->getEmail() ?></username>
        </incomingServer>
        <outgoingServer type="smtp">
            <hostname><?= $setting->getSmtpHost('user', $setting->getUser()->getEmail()) ?></hostname>
            <port><?= $setting->getSmtpPort('user', $setting->getUser()->getEmail()) ?></port>
            <socketType><?= $setting->getSmtpSocket('user', $setting->getUser()->getEmail()) ?></socketType>
            <authentication>password-cleartext</authentication>
            <username><?= $setting->getUser()->getEmail() ?></username>
        </outgoingServer>
        <documentation url="<?= $setting->getInfoUrl('user', $setting->getUser()->getEmail()) ?>">
            <descr lang="de">Allgemeine Beschreibung der Einstellungen</descr>
            <descr lang="en">Generic settings page</descr>
        </documentation>
    </emailProvider>
</clientConfig>
