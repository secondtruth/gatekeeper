<?php
/**
 * FlameCore Gatekeeper
 * Copyright (C) 2015 IceFlame.net
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * @package  FlameCore\Gatekeeper
 * @version  0.1-dev
 * @link     http://www.flamecore.org
 * @license  http://opensource.org/licenses/ISC ISC License
 */

namespace FlameCore\Gatekeeper;

/**
 * The UserAgent class
 *
 * @author   Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UserAgent
{
    /**
     * @var string
     */
    protected $string;

    /**
     * @var string
     */
    protected $browserName;

    /**
     * @var string
     */
    protected $browserVersion;

    /**
     * @var string
     */
    protected $browserEngine;

    /**
     * @var string
     */
    protected $operatingSystem;

    /**
     * Creates a UserAgent object.
     *
     * @param string $string The user agent string
     * @param \FlameCore\Gatekeeper\UserAgentStringParser $parser The parser used to parse the string
     */
    public function __construct($string = null, UserAgentStringParser $parser = null)
    {
        $this->configureFromUserAgentString($string, $parser);
    }

    /**
     * Returns the string representation of the object.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * Gets the user agent string.
     *
     * @return string
     */
    public function getUserAgentString()
    {
        return $this->string;
    }

    /**
     * Sets the user agent string.
     *
     * @param string $string The user agent string
     */
    public function setUserAgentString($string)
    {
        $this->string = $string;
    }

    /**
     * Gets the browser name.
     *
     * @return string
     */
    public function getBrowserName()
    {
        return $this->browserName;
    }

    /**
     * Sets the browser name.
     *
     * @param string $name The browser name
     */
    public function setBrowserName($name)
    {
        $this->browserName = $name;
    }

    /**
     * Gets the browser version.
     *
     * @return string
     */
    public function getBrowserVersion()
    {
        return $this->browserVersion;
    }

    /**
     * Sets the browser version.
     *
     * @param string $version The browser version
     */
    public function setBrowserVersion($version)
    {
        $this->browserVersion = $version;
    }

    /**
     * Gets the full name of the browser. This combines browser name and version.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getBrowserName().' '.$this->getBrowserVersion();
    }

    /**
     * Gets the browser engine name.
     *
     * @return string
     */
    public function getBrowserEngine()
    {
        return $this->browserEngine;
    }

    /**
     * Sets the browser engine name.
     *
     * @param string $browserEngine The browser engine name
     */
    public function setBrowserEngine($browserEngine)
    {
        $this->browserEngine = $browserEngine;
    }

    /**
     * Gets the operating system name.
     *
     * @return string
     */
    public function getOperatingSystem()
    {
        return $this->operatingSystem;
    }

    /**
     * Sets the operating system name.
     *
     * @param string $operatingSystem The operating system name
     */
    public function setOperatingSystem($operatingSystem)
    {
        $this->operatingSystem = $operatingSystem;
    }

    /**
     * Tells whether this user agent is unknown.
     *
     * @return bool Returns TRUE if this user agent is unknown, FALSE otherwise.
     */
    public function isUnknown()
    {
        return empty($this->browserName);
    }

    /**
     * Tells whether this user agent is a known browser.
     *
     * @return bool
     */
    public function isBrowser()
    {
        return in_array($this->getBrowserName(), $this->getKnownBrowsers());
    }

    /**
     * Tells whether this user agent is a known bot/crawler.
     *
     * @return bool Returns TRUE if this user agent is a bot, FALSE otherwise.
     */
    public function isBot()
    {
        return in_array($this->getBrowserName(), $this->getKnownBots());
    }

    /**
     * Configures the user agent information from a user agent string.
     *
     * @param string $string The user agent string
     * @param \FlameCore\Gatekeeper\UserAgentStringParser $parser The parser used to parse the string
     */
    public function configureFromUserAgentString($string, UserAgentStringParser $parser = null)
    {
        if (!$parser) {
            $parser = new UserAgentStringParser();
        }

        $this->setUserAgentString($string);
        $this->fromArray($parser->parse($string));
    }

    /**
     * Converts the user agent information to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'browser_name' => $this->getBrowserName(),
            'browser_version' => $this->getBrowserVersion(),
            'browser_engine' => $this->getBrowserEngine(),
            'operating_system' => $this->getOperatingSystem()
        );
    }

    /**
     * Configures the user agent information from an array.
     *
     * @param array $data The data array
     */
    public function fromArray(array $data)
    {
        $this->setBrowserName($data['browser_name']);
        $this->setBrowserVersion($data['browser_version']);
        $this->setBrowserEngine($data['browser_engine']);
        $this->setOperatingSystem($data['operating_system']);
    }

    protected function getKnownBrowsers()
    {
        return [
            'msie',
            'firefox',
            'chrome',
            'safari',
            'opera',
            'konqueror',
            'edge',
            'lynx'
        ];
    }

    /**
     * Returns an array of strings identifying known bots.
     *
     * @return array
     */
    protected function getKnownBots()
    {
        return array(
            'googlebot',
            'bingbot',
            'msnbot',
            'yahoobot',
            'yandexbot',
            'baidubot',
            'facebookbot'
        );
    }
}
