<?php
echo '<?xml version = "1.0" encoding = "utf-8"?>';
/**
 * @var \Autodiscover\Setting $setting
 */
?>

<clientConfig version="1.1">
    <emailProvider id="<?= $setting->getInfoDomain() ?>">
        <domain><?= $setting->getInfoDomain() ?></domain>
        <displayName><?= $setting->getInfoName() ?></displayName>
        <displayShortName><?= $setting->getInfoName() ?></displayShortName>
        <incomingServer type="imap">
            <hostname><?= $setting->getImapHost() ?></hostname>
            <port><?= $setting->getImapPort() ?></port>
            <socketType><?= $setting->getImapSocket() ?></socketType>
            <authentication>password-cleartext</authentication>
            <username><?= getEmail() ?></username>
        </incomingServer>
        <outgoingServer type="smtp">
            <hostname><?= $setting->getSmtpHost() ?></hostname>
            <port><?= $setting->getSmtpPort() ?></port>
            <socketType><?= $setting->getSmtpSocket() ?></socketType>
            <authentication>password-cleartext</authentication>
            <username><?= getEmail() ?></username>
        </outgoingServer>
        <documentation url="<?= $setting->getInfoUrl() ?>">
            <descr lang="de">Allgemeine Beschreibung der Einstellungen</descr>
            <descr lang="en">Generic settings page</descr>
        </documentation>
    </emailProvider>
</clientConfig>
