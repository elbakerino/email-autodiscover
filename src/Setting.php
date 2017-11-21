<?php

namespace Bemit\Autodiscover;

class Setting {

    protected $setting;

    /**
     * @var \Bemit\Autodiscover\User
     */
    protected $user;

    /**
     * @var \Bemit\Autodiscover\App
     */
    protected $app;

    public function __construct() {
        $this->loadConfig();
    }

    protected function loadConfig() {
        $dir = dirname(__DIR__, 1.) . '/setting/';
        if(is_file($dir . 'general.json')) {
            $this->setting['general'] = json_decode(implode('', file($dir . 'general.json')), true);
        }
        if(!is_file($dir . 'general.json') || false === $this->setting['general']) {
            $this->setting['general'] = json_decode(implode('', file($dir . 'general.default.json')), true);
        }

        if(is_file($dir . 'mailbox.json')) {
            $this->setting['mailbox'] = json_decode(implode('', file($dir . 'mailbox.json')), true);
        }
        if(!is_file($dir . 'mailbox.json') || false === $this->setting['mailbox']) {
            $this->setting['mailbox'] = json_decode(implode('', file($dir . 'mailbox.default.json')), true);
        }

        if(is_file($dir . 'show.json')) {
            $this->setting['show'] = json_decode(implode('', file($dir . 'show.json')), true);
        }
        if(!is_file($dir . 'show.json') || false === $this->setting['show']) {
            $this->setting['show'] = json_decode(implode('', file($dir . 'show.default.json')), true);
        }
    }

    /**
     * Gets the setting with overriding logic:
     * scope 'general' is default
     * when wanted scope exists it returns this
     * when not and the scope is a user try checking the servert-scope setting
     * when that not exists return it with general
     *
     * @param string|array $key
     * @param string       $scope possible: user, server or general
     * @param string|null  $mailbox_id
     *
     * @return mixed
     */
    public function get($key, $scope = 'general', $mailbox_id = null) {
        if(is_array($key)) {
            return $this->getByArray($key, $scope, $mailbox_id);
        } else {
            return $this->getByString($key, $scope, $mailbox_id);
        }
    }

    /**
     * @param string      $key
     * @param string      $scope
     * @param string|null $mailbox_id
     *
     * @return mixed decision flow normal usage: scope=user, mailbox=email; thous begin logic is return 2, when not found goes return 3 and calls
     *               itself with scope=server, this will begin also in 2, but when failes will jump to return default and then finally when nothing
     *               else was exist it returns setting of scope=general
     */
    private function getByString($key, $scope = 'general', $mailbox_id = null) {
        // as show is everytime a array, it doesn't appear here
        if('general' === $scope) {
            // return 1
            return $this->setting[$scope][$key];

        } else if(isset($this->setting['mailbox'][$scope][$mailbox_id][$key])) {
            // return 2
            // when scope is not general, check if the wanted scope with the now needed mailbox id, exists and return it
            return $this->setting['mailbox'][$scope][$mailbox_id][$key];

        } else if('user' === $scope) {
            // return 3
            // when wanted scope is user, e.g. email address, force for server-scope setting, as server scope checking will jump over this, and to default,
            return $this->getByString($key, 'server', $this->getUser()->getHostname($mailbox_id));

        } else {
            // return default
            return $this->getByString($key, 'general');
        }
    }

    /**
     * @param array       $key
     * @param string      $scope
     * @param string|null $mailbox_id
     *
     * @return mixed
     */
    private function getByArray($key, $scope = 'general', $mailbox_id = null) {
        $setting_scope = [];

        if('general' === $scope && 'show' !== $key[0]) {
            // fetch default setting for autodiscover (general.json)
            $setting_scope[0] = &$this->setting[$scope];
        } else if('show' === $key[0] && 'general' !== $scope) {
            // fetch server show data, $mailbox_id must be hostname NOT email
            $setting_scope[0] = &$this->setting['show'][$scope][$mailbox_id];
        } else if('show' === $key[0] && 'general' === $scope) {
            // fetch general show data
            $setting_scope[0] = &$this->setting['show'][$scope];
        } else {
            $setting_scope[0] = &$this->setting['mailbox'][$scope][$mailbox_id];
        }

        if(is_array($setting_scope)) {
            // as the key needs to be a little different for 'show'
            $tmp_key = $key;
            if('show' === $key[0]) {
                unset($tmp_key[0]);
                $tmp_key = array_values($tmp_key);
            }

            for($i = 1; $i <= count($tmp_key); $i++) {
                $setting_scope[$i] = &$setting_scope[$i - 1][$tmp_key[$i - 1]];
            }

            $result = &$setting_scope[count($setting_scope) - 1];
        } else {
            $result = null;
        }

        if('user' === $scope) {
            // when wanted some 'show' data, it never goes through this as show checks from 'server' down
            $tmp_setting_server_fallback[0] = &$this->setting['mailbox']['server'][$this->getUser()->getHostname($mailbox_id)];

            if(is_array($tmp_setting_server_fallback)) {
                for($i = 1; $i <= count($key); $i++) {
                    $tmp_setting_server_fallback[$i] = &$tmp_setting_server_fallback[$i - 1][$key[$i - 1]];
                }

                $result_server = &$tmp_setting_server_fallback[count($tmp_setting_server_fallback) - 1];
            } else {
                $result_server = null;
            }
        }

        if('general' === $scope) {
            // return 1
            return $result;

        } else if(isset($result)) {
            // return 2
            return $result;

        } else if('user' === $scope && isset($result_server)) {
            // return 3
            // when wanted scope is user, e.g. email address, check for server-scope setting
            return $result_server;

        } else {
            // return default
            return $this->getByArray($key, 'general');
        }
    }

    /**
     * Gets the setting with overriding logic:
     * scope 'general' is default
     * when wanted scope exists it returns this
     * when not and the scope is a user try checking the servert-scope setting
     * when that not exists return it with general
     *
     * @param string      $scope
     * @param string|null $mailbox_id
     *
     * @return mixed
     */
    public function getInfoName($scope = 'general', $mailbox_id = null) {

        return $this->get(['info', 'name'], $scope, $mailbox_id);
    }

    public function getInfoUrl($scope = 'general', $mailbox_id = null) {

        return $this->get(['info', 'url'], $scope, $mailbox_id);
    }

    public function getInfoDomain($scope = 'general', $mailbox_id = null) {

        return $this->get(['info', 'domain'], $scope, $mailbox_id);
    }

    public function getImapHost($scope = 'general', $mailbox_id = null) {

        return $this->get(['server', 'imap', 'host'], $scope, $mailbox_id);
    }

    public function getImapPort($scope = 'general', $mailbox_id = null) {

        return $this->get(['server', 'imap', 'port'], $scope, $mailbox_id);
    }

    public function getImapSocket($scope = 'general', $mailbox_id = null) {

        return $this->get(['server', 'imap', 'socket'], $scope, $mailbox_id);
    }

    public function getSmtpHost($scope = 'general', $mailbox_id = null) {

        return $this->get(['server', 'smtp', 'host'], $scope, $mailbox_id);
    }

    public function getSmtpPort($scope = 'general', $mailbox_id = null) {

        return $this->get(['server', 'smtp', 'port'], $scope, $mailbox_id);
    }

    public function getSmtpSocket($scope = 'general', $mailbox_id = null) {

        return $this->get(['server', 'smtp', 'socket'], $scope, $mailbox_id);
    }

    public function getActiveSynchUrl($scope = 'general', $mailbox_id = null) {

        return $this->get(['server', 'activesync', 'url'], $scope, $mailbox_id);
    }

    public function getDomainRequired($scope = 'general', $mailbox_id = null) {

        return $this->get(['domain_required'], $scope, $mailbox_id);
    }

    public function getLoginNameRequired($scope = 'general', $mailbox_id = null) {

        return $this->get(['login_name_required'], $scope, $mailbox_id);
    }

    public function getTtl($scope = 'general', $mailbox_id = null) {

        return $this->get(['ttl'], $scope, $mailbox_id);
    }

    public function getShow($key, $scope = 'general', $mailbox_id = null) {
        if(is_array($key)) {
            return $this->get(array_merge(['show'], $key), $scope, $mailbox_id);
        } else {
            return $this->get(['show', $key], $scope, $mailbox_id);
        }
    }

    /**
     * @return \Bemit\Autodiscover\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param \Bemit\Autodiscover\User $user
     */
    public function setUser(&$user) {
        $this->user = $user;
    }

    /**
     * @return \Bemit\Autodiscover\App
     */
    public function getApp() {
        return $this->app;
    }

    /**
     * @param \Bemit\Autodiscover\App $app
     */
    public function setApp(&$app) {
        $this->app = $app;
    }
}