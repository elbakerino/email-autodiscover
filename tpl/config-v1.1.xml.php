<?php
echo '<?xml version = "1.0" encoding = "utf-8"?>';
/**
 * @var \Autodiscover\Setting $setting
 */
?>

<clientConfig version="1.1">
    <emailProvider id="<?= $setting->getInfoDomain('user', getUserEmail()) ?>">
        <domain><?= $setting->getInfoDomain('user', getUserEmail()) ?></domain>
        <displayName><?= $setting->getInfoName('user', getUserEmail()) ?></displayName>
        <displayShortName><?= $setting->getInfoName('user', getUserEmail()) ?></displayShortName>
        <incomingServer type="imap">
            <hostname><?= $setting->getImapHost('user', getUserEmail()) ?></hostname>
            <port><?= $setting->getImapPort('user', getUserEmail()) ?></port>
            <socketType><?= $setting->getImapSocket('user', getUserEmail()) ?></socketType>
            <authentication>password-cleartext</authentication>
            <username><?= getUserEmail() ?></username>
        </incomingServer>
        <outgoingServer type="smtp">
            <hostname><?= $setting->getSmtpHost('user', getUserEmail()) ?></hostname>
            <port><?= $setting->getSmtpPort('user', getUserEmail()) ?></port>
            <socketType><?= $setting->getSmtpSocket('user', getUserEmail()) ?></socketType>
            <authentication>password-cleartext</authentication>
            <username><?= getUserEmail() ?></username>
        </outgoingServer>
        <documentation url="<?= $setting->getInfoUrl('user', getUserEmail()) ?>">
            <descr lang="de">Allgemeine Beschreibung der Einstellungen</descr>
            <descr lang="en">Generic settings page</descr>
        </documentation>
    </emailProvider>
</clientConfig>
