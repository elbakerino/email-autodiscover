<?php

namespace Bemit\Autodiscover;


class Content {

    protected $setting;

    /**
     * Under which subdomains the content is accessible, will be removed from the active hostname to be "guess"/determine the active style and content
     *
     * @var array
     */
    protected $content_subdomain = ['autodiscover', 'autoconfig'];

    /**
     * This contains the active hostname that the user wanted, extracted from url (autodiscover.domain.tld) and no other input data, as Show could
     * not know what user visits before user entered the email
     *
     * @var array with indices 'arr' and 'str', where arr contains the hostname as array and
     */
    protected $active_content_host = [];

    /**
     * ShowContent constructor.
     *
     * @param \Bemit\Autodiscover\Setting $setting
     */
    public function __construct(&$setting) {
        $this->setting = $setting;
        foreach($this->content_subdomain as $content_subdomain) {
            if($this->setting->getApp()->getActiveHostname()[0] === $content_subdomain) {
                // when content subdomain is the first subdomain of active host, remove the subdomain and set as active content hostname
                $tmp = $this->setting->getApp()->getActiveHostname();
                unset($tmp[0]);

                $this->active_content_host['arr'] = array_values($tmp);
                $this->active_content_host['str'] = $setting->getApp()->returnHostname($this->active_content_host['arr'], false);
                break;
            }
        }
    }

    public function getHead($key) {
        if(is_array($key)) {
            return $this->get(array_merge(['head'], $key));
        } else {
            return $this->get(['head', $key]);
        }
    }

    public function getCenter($key) {
        if(is_array($key)) {
            return $this->get(array_merge(['center'], $key));
        } else {
            return $this->get(['center', $key]);
        }
    }

    public function getForm($key) {
        if(is_array($key)) {
            return $this->get(array_merge(['form'], $key));
        } else {
            return $this->get(['form', $key]);
        }
    }

    public function getResponse($key) {
        if(is_array($key)) {
            return $this->get(array_merge(['response'], $key));
        } else {
            return $this->get(['response', $key]);
        }
    }

    public function getModule($key) {
        if(is_array($key)) {
            return $this->get(array_merge(['module'], $key));
        } else {
            return $this->get(['module', $key]);
        }
    }

    public function getApi($key) {
        if(is_array($key)) {
            return $this->get(array_merge(['api'], $key));
        } else {
            return $this->get(['api', $key]);
        }
    }

    public function getFooter($key) {
        if(is_array($key)) {
            return $this->get(array_merge(['footer'], $key));
        } else {
            return $this->get(['footer', $key]);
        }
    }

    public function get($key) {
        if(is_array($key)) {
            return $this->setting->getShow($key, 'server', $this->active_content_host['str']);
        } else {
            return $this->setting->getShow($key, 'server', $this->active_content_host['str']);
        }
    }
}