<?php

namespace Bemit\Autodiscover;


class Show {

    /**
     * @var \Bemit\Autodiscover\ShowContent
     */
    protected $show_content;

    /**
     * @var \Bemit\Autodiscover\Setting
     */
    public $setting;

    /**
     * Show constructor.
     *
     * @param \Bemit\Autodiscover\Setting $setting
     */
    public function __construct(&$setting) {
        $this->setting = $setting;
        $this->show_content = new ShowContent($this->setting);
    }

    public function render($debug) {
        $show = $this;
        // $show is used within show.html.php as connector to every other logic
        // $debug is used within show.html.php
        include dirname(__DIR__, 1) . '/tpl/show.html.php';
    }

    /**
     * @return \Bemit\Autodiscover\ShowContent
     */
    public function getContent() {
        return $this->show_content;
    }
}