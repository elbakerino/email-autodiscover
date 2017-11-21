<?php

namespace Bemit\Autodiscover;


class Api {

    protected $setting;

    protected $response;

    /**
     * @param \Bemit\Autodiscover\Setting $setting
     */
    public function __construct(&$setting) {
        $this->setting = $setting;
    }

    public function determineCall($debug) {
        $api_command = $this->setting->getApp()->getActivePath();
        unset($api_command[0]);// remove 'api' from string
        $api_command = array_values($api_command);

        switch($api_command[0]) {
            case 'get':
                $this->get($api_command);
                break;

            default:
                break;
        }
    }

    protected function get($ident) {
        unset($ident[0]);// remove 'get' from string
        $ident = array_values($ident);
        if(method_exists($this, 'get' . ucfirst($ident[0]))) {
            $tmp_method_name = 'get' . ucfirst($ident[0]);
            $this->$tmp_method_name($ident[1]);
        }
    }

    protected function getMailbox($selector) {
        switch($selector) {
            case 'information':
                $this->getMailboxInformation();
                break;
        }
    }

    protected function getMailboxInformation() {
        if(null !== $this->setting->getUser()->getEmail() && false !== $this->setting->getUser()->getEmail()) {
            // when email was send through POST, GET but not through show.html.php
            $email = $this->setting->getUser()->getEmail();
            var_dump($this->setting->getUser()->getEmail());
            $this->response = [
                "info"                => [
                    "name"   => $this->setting->getInfoName('user', $email),
                    "url"    => $this->setting->getInfoUrl('user', $email),
                    "domain" => $this->setting->getInfoDomain('user', $email),
                ],
                "server"              => [
                    "imap" => [
                        "host"   => $this->setting->getImapHost('user', $email),
                        "port"   => $this->setting->getImapPort('user', $email),
                        "socket" => $this->setting->getImapSocket('user', $email),
                    ],
                    "smtp" => [
                        "host"   => $this->setting->getSmtpHost('user', $email),
                        "port"   => $this->setting->getSmtpPort('user', $email),
                        "socket" => $this->setting->getSmtpSocket('user', $email),
                    ],
                ],
                "domain_required"     => $this->setting->getDomainRequired('user', $email),
                "login_name_required" => $this->setting->getLoginNameRequired('user', $email),
                "ttl"                 => $this->setting->getTtl('user', $email),
            ];
        }
        //var_dump($_POST);
    }

    public function respond() {
        if(!empty($this->response) && false !== $this->response['success']) {
            $this->response['success'] = true;
        } else {
            $this->response['success'] = false;
        }
        echo json_encode($this->response);
    }
}