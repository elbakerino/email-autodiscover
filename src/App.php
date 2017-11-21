<?php

namespace Bemit\Autodiscover;

class App {

    /**
     * Contains the information of the active runtime ['hostname' => [], 'param' => [], 'path' => [], 'info' => ['trailing_slash' => bool, 'ssl' =>
     * bool, 'www' => bool]]
     *
     * @var array
     *
     * @todo: rewrite with UriSchema
     */
    protected $active = [];

    /**
     * @param array $active when not set, it automatically sets the information
     */
    public function setActiveUrl($active = []) {
        if(isset($active['hostname'])) {
            $this->setActiveHostname($active['hostname']);
        } else {
            $this->setActiveHostname(filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL));
        }

        if(isset($active['path-query'])) {
            $request_part = $active['path-query'];
        } else {
            $request_part = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        }

        if(false === strpos($request_part, '?')) {
            //When there is no param (values) part in string
            $this->setActivePath(UrlHelper::i()->stripSlash($request_part));
            // need to be checked not parsed path value
            if(UrlHelper::i()->hasSlashTrailing($request_part)) {
                $this->active['info']['trailing_slash'] = true;
            } else {
                $this->active['info']['trailing_slash'] = false;
            }
        } else {
            //When there is an param (values) part in string
            $bothparts_tmp = explode('?', $request_part);// split path part from query part

            $this->setActivePath(UrlHelper::i()->stripSlash($bothparts_tmp[0]));
            // need to be checked not parsed path value
            if(UrlHelper::i()->hasSlashTrailing($bothparts_tmp[0])) {
                $this->active['info']['trailing_slash'] = true;
            } else {
                $this->active['info']['trailing_slash'] = false;
            }

            $tmp_query_arr = explode('&', $bothparts_tmp[1]);
            foreach($tmp_query_arr as $q_item) {
                // Go through all get value strings with key and value and make the key and the value pairs
                $this->active['param'][substr($q_item, 0, strpos($q_item, '='))] = substr($q_item, strpos($q_item, '=') + 1);
            }
        }

        // contains no string when ssl if off in apache/nginx
        // contains 'on' or 'off' on IIS
        if(!isset($_SERVER['HTTPS']) || 'on' !== $_SERVER['HTTPS']) {
            $this->setActiveSsl(false);
        } else {
            $this->setActiveSsl(true);
        }

        if('www' === substr($this->getActiveHostname(false), 0, 3)) {
            // remove www from hostname and reset
            $this->setActiveHostname(substr($this->getActiveHostname(false), 4));
            $this->active['info']['www'] = true;
        } else {
            $this->active['info']['www'] = false;
        }
    }

    /**
     * @param string $hostname
     */
    public function setActiveHostname($hostname) {
        $this->active['hostname'] = explode('.', $hostname);
    }

    /**
     * Only used internally for setting the active path array
     *
     * @param string $path
     */
    protected function setActivePath($path) {
        $this->active['path'] = explode('/', UrlHelper::i()->stripSlash($path));
    }

    /**
     * SSL Status is detemined with $_SERVER, when not working for your implementation you can set if AFTER setActive manually with this
     *
     * @param $ssl
     */
    public function setActiveSsl($ssl) {
        $this->active['info']['ssl'] = $ssl;
    }

    /**
     * @param bool $as_array
     *
     * @return array|string
     */
    public function getActiveHostname($as_array = true) {
        return $this->returnHostname($this->active['hostname'], $as_array);
    }

    /**
     * @param array  $hostname
     * @param bool   $as_array
     * @param string $seperator
     *
     * @return string|array
     */
    public function returnHostname($hostname, $as_array, $seperator = '.') {
        return $this->valueConcating($hostname, $as_array, $seperator);
    }

    /**
     * @param bool $as_array
     *
     * @return array|string
     */
    public function getActivePath($as_array = true) {
        return $this->returnPath($this->active['path'], $as_array);
    }

    /**
     * @param array  $path
     * @param bool   $as_array
     * @param string $seperator
     *
     * @return string|array
     */
    public function returnPath($path, $as_array, $seperator = '/') {
        return $this->valueConcating($path, $as_array, $seperator);
    }

    /**
     * @param array  $value
     * @param bool   $as_array
     * @param string $seperator
     *
     * @return string|array
     */
    protected function valueConcating($value, $as_array, $seperator) {
        if(true === $as_array) {
            return $value;
        } else {
            return implode($seperator, $value);
        }
    }

    /**
     *
     * @todo implement
     *
     * @param bool $as_array
     *
     * @return array|string
     */
    public function getActiveParam($as_array = true) {
        return '';
    }
}