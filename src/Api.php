<?php

namespace Bemit\Autodiscover;


class Api {

    protected $setting;

    /**
     * @param \Bemit\Autodiscover\Setting $setting
     */
    public function __construct(&$setting) {
        $this->setting = $setting;
    }

    public function determineCall($debug) {

    }

    protected function getInput($key, $input, $filter = FILTER_SANITIZE_STRING) {

    }

    public function respond() {

    }
}