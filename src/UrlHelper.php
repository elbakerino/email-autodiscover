<?php

namespace Bemit\Autodiscover;

/**
 * Some helper functions for the manipulation of uri strings
 *
 * @category
 * @package    \Bemit\MiniRoute
 * @author     Original Author mb@project.bemit.eu
 * @link
 * @copyright  2017 Michael Becker
 * @since      Version 0.0.1
 * @version    0.0.2
 */

class UrlHelper {

    /**
     * @var self
     */
    protected static $i = null;

    protected function __construct() {
    }

    /**
     * @return self
     */
    public static function i() {
        if(null === static::$i) {
            static::$i = new self();
        }

        return static::$i;
    }

    /**
     * Checks if given path has a slash at the beginning or true
     *
     * @param string $path
     *
     * @return bool false when nowhere a slash, true when at begin or end
     */
    public function hasSlash($path) {
        $return_val = false;
        if($this->hasSlashBeginning($path) || $this->hasSlashTrailing($path)) {
            $return_val = true;
        }

        return $return_val;
    }

    /**
     * Checks if given path has a slash at the beginning or true
     *
     * @param string $path
     *
     * @return bool false when nowhere a slash, true when at begin or end
     */
    public function hasSlashBeginning($path) {
        $return_val = false;
        $path = trim($path);
        if('/' === substr($path, 0, 1)) {
            $return_val = true;
        }

        return $return_val;
    }

    /**
     * Checks if given path has a slash at the beginning or true
     *
     * @param string $path
     *
     * @return bool false when nowhere a slash, true when at begin or end
     */
    public function hasSlashTrailing($path) {
        $return_val = false;
        $path = trim($path);
        if('/' === substr($path, strlen($path) - 1)) {
            $return_val = true;
        }

        return $return_val;
    }

    /**
     * Strips beginning and end slashes from a string, trims string before removing /
     *
     * @param $string
     *
     * @return string trimmed and / and \ removed from begin and end
     */
    public function stripSlash($string) {
        return $this->stripSlashBeginning($this->stripSlashTrailing($string));
    }

    /**
     * Strips beginning slash from a string, trims string before removing /
     *
     * @param $string
     *
     * @return string trimmed and / and \ removed from begin
     */
    public function stripSlashBeginning($string) {
        $string = trim($string);
        if('\\' === substr($string, 0, 1) || '/' === substr($string, 0, 1)) {
            $string = substr($string, 1);
        }

        return $string;
    }

    /**
     * Strips end slash from a string, trims string before removing /
     *
     * @param $string
     *
     * @return string trimmed and / and \ removed from end
     */
    public function stripSlashTrailing($string) {
        $string = trim($string);
        if('\\' === substr($string, strlen($string) - 1) || '/' === substr($string, strlen($string) - 1)) {
            $string = substr($string, 0, strlen($string) - 1);
        }

        return $string;
    }

    /**
     * Adds a trailing slash to the end of the given path (string) or removes it when not wanted
     *
     * @param string $path
     * @param bool   $trailing_slash true adds a / when not already at the end, false removes a trailing slash if in $uri
     *
     * @return string $uri with slash at end, or not
     */
    public function slashTrailing($path, $trailing_slash = true) {
        $return_val = $path;
        if(true === $trailing_slash) {
            if(!$this->hasSlashTrailing($path)) {
                // When the last char in the uri is not a / but wanted, add it
                $return_val .= '/';
            }
        } else if(false === $trailing_slash) {
            if($this->hasSlashTrailing($path)) {
                // When the last char in the uri is a / but not wanted, removes it
                $return_val = $this->stripSlashTrailing($path);
            }
        }

        return $return_val;
    }
}