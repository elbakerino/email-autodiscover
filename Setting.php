<?php

namespace Autodiscover;

class Setting {

    protected $setting;

    public function __construct() {
        $this->loadConfig();
    }

    protected function loadConfig() {
        $this->setting = json_decode(implode('', file(__DIR__ . '/setting.json')), true);
    }

    public function getInfoName() {
        return $this->setting['info']['name'];
    }

    public function getInfoUrl() {
        return $this->setting['info']['url'];
    }

    public function getInfoDomain() {
        return $this->setting['info']['domain'];
    }

    public function getImapHost() {
        return $this->setting['server']['imap']['host'];
    }

    public function getImapPort() {
        return $this->setting['server']['imap']['port'];
    }

    public function getImapSocket() {
        return $this->setting['server']['imap']['socket'];
    }

    public function getSmtpHost() {
        return $this->setting['server']['smtp']['host'];
    }

    public function getSmtpPort() {
        return $this->setting['server']['smtp']['port'];
    }

    public function getSmtpSocket() {
        return $this->setting['server']['smtp']['socket'];
    }

    public function getDomainRequired() {
        return $this->setting['domain_required'];
    }

    public function getTtl() {
        return $this->setting['ttl'];
    }
}