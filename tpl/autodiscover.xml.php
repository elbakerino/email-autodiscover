<?php
echo '<?xml version = "1.0" encoding = "utf-8"?>';
/**
 * @var \Bemit\Autodiscover\Setting $setting
 */
?>
<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
    <?php
    /*
     * Response: Required
     * This tag serves as an indication that the retrieved XML is an Autodiscovery Response
     */
    ?>
    <Response xmlns="http://schemas.microsoft.com/exchange/autodiscover/outlook/responseschema/2006a">
        <?php
        /*
         * User: Optional
         * This tag gives user-specific information.  Autodiscover must be UTF-8 encoded.
         */
        ?>
        <User>
            <?php
            /*
             * DisplayName: Optional
             * The server may have a good formal display name.  The client can decide to accept it or change it.  This will save the user time in the default case.
             */
            ?>
            <DisplayName><?= $setting->getInfoName('user', $setting->getUser()->getEmail()) ?></DisplayName>
        </User>

        <?php
        /*
         * Account: Required
         * This tag specifies the type of account, such as Email vs Newsgroups, vs SIP server, etc.
         */
        ?>
        <Account>
            <?php
            /*
             * AccountType: Required
             * This value indicates the type of the account.
             * VALUES:
             * email: The values under this Account tag indicate configuration settings for an email server.
             * nntp: The values under this Account tag indicate configuration settings for a NNTP server. (not used by Outlook 2007)
             */
            ?>
            <AccountType>email</AccountType>

            <?php
            /*
             * Action: Required
             * This value indicates if the goal of this account results is to provide the settings or redirect to another web server that can provide results.
             * VALUES:
             * redirectUrl: If this value is specified, then the URL tag will specify the http: or https: URL containing the Autodiscover results to be used.  In order to prevent the server from being able to send the client into an infinite loop, the client should stop redirecting after 10 redirects.
             * redirectAddr: If this value is specified, then the XML tag will specify the e-mail address that Outlook should use to execute Autodiscover again.  In other words, the server is telling the client that the e-tpl address the client should really be using for Autodiscover is not the one that was posted, but the one specified in this tag.
             * settings: If this value is specified, then the XML will contain the settings needed to configure the account.  The settings will primarily be under the PROTOCOL tag.
             */
            ?>
            <Action>settings</Action>

            <?php
            /*
             * RedirectUrl: Required if ACTION tag has value of 'redirectUrl'. Otherwise this tag must not exist.
             * The value will be a https: URL that the client should use to obtain the Autodiscover settings or a http: URL that the client should use for further redirection.
             */

            // <RedirectUrl>redirect.URL</RedirectUrl>
            ?>


            <?php
            /*
             * RedirectAddr: Required if ACTION tag has value of 'redirectAddr'. Otherwise this tag must not exist.
             * The value will be an email address that the client should use to rediscover settings using the Autodiscover protocol.
             */

            // <RedirectAddr>email@address</RedirectAddr>
            ?>
            <?php
            /*
             * Image: Optional
             * This is a JPG picture to brand the ISP configuration experience with. The client can choose whether or not they download this picture to display. (not used by Outlook 2007)
             */

            // <Image>http://path.to.image.com/image.jpg</Image>
            ?>

            <?php
            /*
             * ServiceHome: Optional
             * This is a link to the ISP’s Home Page. The client can choose whether or not they expose this link to the user. (not used by Outlook 2007)
             */
            ?>
            <ServiceHome><?= $setting->getInfoUrl('user', $setting->getUser()->getEmail()) ?></ServiceHome>

            <?php
            /*
             * Protocol: Required if ACTION tag has value of 'settings'. Otherwise, this tag must not exist.
             * The tag encloses the specifications for a single account type.  The list of Protocol tags are in order of preference of the server.  The client may over ride the preference.
             */
            ?>
            <Protocol>
                <?php
                /*
                 * TYPE: Required.
                 * The value here specifies what kind of tpl account is being configured.
                 * POP3: The protocol to connect to this server is POP3. Only applicable for AccountType=email.
                 * SMTP: The protocol to connect to this server is SMTP. Only applicable for AccountType=email.
                 * IMAP: The protocol to connect to this server is IMAP. Only applicable for AccountType=email.
                 * DAV: The protocol to connect to this server is DAV. Only applicable for AccountType=email.
                 * WEB: Email is accessed from a web browser using an URL from the SERVER tag. Only applicable for AccountType=email. (not used by Outlook 2007)
                 * NNTP: The protocol to connect to this server is NNTP. Only applicable for AccountType=nntp. (not used by Outlook 2007)
                 */
                ?>
                <Type>IMAP</Type>

                <?php
                /*
                 * ExpirationDate: Optional.
                 * The value here specifies the last date which these settings should be used. After that date, the settings should be rediscovered via Autodiscover again. If no value is specified, the default will be no expiration.
                 */

                // <ExpirationDate>YYYYMMDD</ExpirationDate>
                ?>

                <?php
                /*
                 * TTL: Optional.
                 * The value here specifies the time to live in hours that these settings are valid for. After that time has elapsed (from the time the settings were retrieved), the settings should be rediscovered via Autodiscovery again. A value of 0 indicates that no rediscovery will be required. If no value is specified, the default will be a TTL of 1 hour.
                 */
                ?>
                <TTL><?= $setting->getTtl('user', $setting->getUser()->getEmail()) ?></TTL>

                <?php
                /*
                 * Server: Required.
                 * The value here specifies the name of the tpl server corresponding to the server type specified above.
                 * For protocols such as POP3, SMTP, IMAP, or NNTP, this value will be either a hostname or an IP address.
                 * For protocols such as DAV or WEB, this will be an URL.
                 */
                ?>
                <Server><?= $setting->getImapHost('user', $setting->getUser()->getEmail()) ?></Server>

                <?php
                /*
                 * Port: Optional.
                 * The value specifies the Port number to use.  If no value is specified, the default settings will be used depending on the tpl server type.  This value is not used if the SERVER tag contains an URL.
                 */
                ?>
                <Port><?= $setting->getImapPort('user', $setting->getUser()->getEmail()) ?></Port>

                <?php
                /*
                 * LoginName: Optional.
                 * This value specifies the user's login.  If no value is specified, the default will be set to the string preceding the '@' in the email address.  If the Login name contains a domain, the format should be <Username>@<Domain>.  Such as JoeUser@SalesDomain.
                 */
                // todo: add config to control building of loginname (only name[before @], email adress, \domain\user etc)
                // strrev(substr(strrev(getEmail()), strpos(strrev(getEmail()), '.')+1))
                if($setting->getLoginNameRequired('user', $setting->getUser()->getEmail()) && null !== $setting->getUser()->getEmail()) {
                    echo '<LoginName>' . $setting->getUser()->getEmail() . '</LoginName>';
                }
                ?>

                <?php
                /*
                 * DomainRequired: Optional.  Default is off.
                 * If this value is true, then a domain is required during authentication.  If the domain is not specified in the LOGINNAME tag, or the LOGINNAME tag was not specified, the user will need to enter the domain before authentication will succeed.
                 */

                ?>
                <DomainRequired><?= isOnOrOff($setting->getDomainRequired('user', $setting->getUser()->getEmail())) ?></DomainRequired>

                <?php
                /*
                 * DomainName: Optional.
                 * This value specifies the user's domain. If no value is specified, the default authentication will be to use the e-tpl address as a UPN format <Username>@<Domain>. Such as JoeUser@SalesDomain.
                 */
                if(null !== $setting->getUser()->getEmail()) {
                    echo '<DomainName>' . $setting->getUser()->getHostname() . '</DomainName>';
                }
                ?>

                <?php
                /*
                 * SPA: (Secure Password Authentication) Optional.
                 * This value specifies whether or not secure password authentication is needed.
                 * If unspecified, the default is set to on.
                 */
                ?>
                <SPA>off</SPA>

                <?php
                /*
                 * SSL: Optional.
                 * This value specifies whether secure login is needed.
                 * If unspecified, the default is set to on.
                 * */ ?>
                <SSL><?= isOnOrOff('SSL' === $setting->getImapSocket('user', $setting->getUser()->getEmail())) ?></SSL>

                <?php
                /*
                 * AuthRequired: Optional.
                 * This value specifies whether authentication is needed (password).
                 * If unspecified, the default is set to on.
                 */
                ?>
                <AuthRequired>on</AuthRequired>

                <?php
                /*
                 * UsePOPAuth: Optional.
                 * This value can only be used for SMTP types.
                 * If specified, then the authentication information provided for the POP3 type account will also be used for SMTP.
                 */
                ?>
                <UsePOPAuth>on</UsePOPAuth>

                <?php
                /*
                 * SMTPLast: Optional.  Default is off.
                 * If this value is true, then the SMTP server requires that email be downloaded before sending email via the SMTP server.  This is often required because the SMTP server verifies that the authentication succeeded when downloading email.
                 */
                ?>
                <SMTPLast>off</SMTPLast>
            </Protocol>

            <Protocol>
                <Type>SMTP</Type>
                <TTL><?= $setting->getTtl('user', $setting->getUser()->getEmail()) ?></TTL>
                <Server><?= $setting->getSmtpHost('user', $setting->getUser()->getEmail()) ?></Server>
                <Port><?= $setting->getSmtpPort('user', $setting->getUser()->getEmail()) ?></Port>
                <DomainRequired><?= isOnOrOff($setting->getDomainRequired('user', $setting->getUser()->getEmail())) ?></DomainRequired>
                <SPA>off</SPA>
                <SSL><?= isOnOrOff('SSL' === $setting->getSmtpSocket('user', $setting->getUser()->getEmail())) ?></SSL>
                <AuthRequired>on</AuthRequired>
                <UsePOPAuth>on</UsePOPAuth>
                <SMTPLast>off</SMTPLast>
            </Protocol>
        </Account>
    </Response>
</Autodiscover>
