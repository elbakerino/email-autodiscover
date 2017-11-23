<?php

namespace Bemit\Autodiscover;


class Api {

    /**
     * @var \Bemit\Autodiscover\Content
     */
    protected $content;

    /**
     * @var \Bemit\Autodiscover\Setting
     */
    public $setting;

    protected $response;

    /**
     * @param \Bemit\Autodiscover\Setting $setting
     */
    public function __construct(&$setting) {
        $this->setting = $setting;
        $this->content = new Content($this->setting);
    }

    /**
     * API Access checks, it checks if the API was called from a domain that is allowed to do that OR the user provided a token
     *
     * @return bool
     */
    protected function securityCheck() {
        $is_domain_allowed = false;

        if(in_array($this->setting->getApp()->getActiveHostname(false), $this->content->getApi(['security', 'domain-allowed']))) {
            $is_domain_allowed = true;
        } else if(in_array('*', $this->content->getApi(['security', 'domain-allowed']))) {
            // checks for wildcard entry, the default content
            $is_domain_allowed = true;
        }

        $registered_user = false;
        if(filter_has_var(INPUT_POST, 'token') || filter_has_var(INPUT_GET, 'token')) {
            $token = false;
            if(filter_has_var(INPUT_POST, 'token')) {
                $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
            }
            if(filter_has_var(INPUT_GET, 'token')) {
                $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
            }
            foreach($this->content->getApi(['token']) as $tok => $tok_info) {
                if($token === $tok && true === $tok_info['active']) {
                    $registered_user = true;
                    break;
                }
            }
        }

        if($registered_user && $is_domain_allowed) {
            return true;
        } else {
            // when the api was called not with a valid domain name and not with a valid user token
            // this means it was submitted from form or somehow else, form allows only when recaptcha was set so try that:
            if($this->content->getModule(['google-recaptcha', 'active']) && filter_has_var(INPUT_POST, 'g-recaptcha-response')) {

                $recaptcha = new \ReCaptcha\ReCaptcha($this->content->getModule(['google-recaptcha', 'key', 'secret']));

                if($recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'])) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function determineCall($debug) {
        if($this->securityCheck()) {
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
        } else {
            $this->response = ['success' => false];
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

    public function respond($response = null) {
        if(null === $response) {
            $response = &$this->response;
        }

        if(!empty($response) && false !== $response['success']) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
        }
        echo json_encode($response);
    }
}