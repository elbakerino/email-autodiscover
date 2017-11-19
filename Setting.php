<?php

namespace Autodiscover;

class Setting {

    protected $setting;

    public function __construct() {
        $this->loadConfig();
    }

    protected function loadConfig() {
        $this->setting['general'] = json_decode(implode('', file(__DIR__ . '/setting/general.json')), true);
        $this->setting['mailbox'] = json_decode(implode('', file(__DIR__ . '/setting/mailbox.json')), true);
    }

    /**
     * Gets the setting with overriding logic:
     * scope 'general' is default
     * when wanted scope exists it returns this
     * when not and the scope is a user try checking the servert-scope setting
     * when that not exists return it with general
     *
     * @param string|array $key
     * @param string       $scope
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
     * @return mixed decision flow normal usage: scope=user, mailbox=email; thous begin logic is return 2, when not found goes return 3 and calls itself with scope=server, this will begin also in 2, but when failes will jump to return default and then finally when nothing else was exist it returns setting of scope=general
     */
    private function getByString($key, $scope = 'general', $mailbox_id = null) {
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
            return $this->getByString($key, 'server', getUserHostname($mailbox_id));

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

        if('general' === $scope) {
            $setting_scope[0] = &$this->setting[$scope];
        } else {
            $setting_scope[0] = &$this->setting['mailbox'][$scope][$mailbox_id];
        }

        if(is_array($setting_scope)) {
            for($i = 1; $i <= count($key); $i++) {
                $setting_scope[$i] = &$setting_scope[$i - 1][$key[$i - 1]];
            }

            $result = &$setting_scope[count($setting_scope) - 1];
        } else {
            $result = null;
        }

        if('user' === $scope) {
            $tmp_setting_server_fallback[0] = &$this->setting['mailbox']['server'][getUserHostname($mailbox_id)];

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

    public function getTtl($scope = 'general', $mailbox_id = null) {

        return $this->get(['ttl'], $scope, $mailbox_id);
    }
}