<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper;

/**
 * The UserAgent class
 *
 * @author Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @author Christian Neff <christian.neff@gmail.com>
 */
class UserAgent
{
    protected UserAgentString $string;

    protected ?string $browserName = null;

    protected ?string $browserVersion = null;

    protected ?string $browserEngine = null;

    protected ?string $operatingSystem = null;

    /**
     * Creates a UserAgent object.
     *
     * @param string|null                $string $string The user agent string
     * @param UserAgentStringParser|null $parser The parser used to parse the string
     */
    public function __construct(?string $string = null, ?UserAgentStringParser $parser = null)
    {
        $this->configureFromUserAgentString($string, $parser);
    }

    /**
     * Returns the string representation of the object.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->string;
    }

    /**
     * Gets the user agent string.
     *
     * @return UserAgentString
     */
    public function getUserAgentString(): UserAgentString
    {
        return $this->string;
    }

    /**
     * Sets the user agent string.
     *
     * @param string $string The user agent string
     */
    public function setUserAgentString(string $string): void
    {
        $this->string = new UserAgentString($string);
    }

    /**
     * Gets the browser name.
     *
     * @return string|null Returns the browser name or `NULL` if it could not be determined.
     */
    public function getBrowserName(): ?string
    {
        return $this->browserName;
    }

    /**
     * Sets the browser name.
     *
     * @param string $name The browser name
     */
    public function setBrowserName(string $name): void
    {
        $this->browserName = $name;
    }

    /**
     * Gets the browser version.
     *
     * @return string|null Returns the browser version or `NULL` if it could not be determined.
     */
    public function getBrowserVersion(): ?string
    {
        return $this->browserVersion;
    }

    /**
     * Sets the browser version.
     *
     * @param string $version The browser version
     */
    public function setBrowserVersion(string $version): void
    {
        $this->browserVersion = $version;
    }

    /**
     * Gets the full name of the browser. This combines browser name and version.
     *
     * @return string|null Returns the full name of the browser or `NULL` if it could not be determined.
     */
    public function getFullName(): ?string
    {
        $name = $this->getBrowserName();

        if ($name === null) {
            return null;
        }

        $version = $this->getBrowserVersion();

        return $name . ($version ? ' ' . $version : '');
    }

    /**
     * Gets the browser engine name.
     *
     * @return string|null Returns the browser engine name or `NULL` if it could not be determined.
     */
    public function getBrowserEngine(): ?string
    {
        return $this->browserEngine;
    }

    /**
     * Sets the browser engine name.
     *
     * @param string $browserEngine The browser engine name
     */
    public function setBrowserEngine(string $browserEngine): void
    {
        $this->browserEngine = $browserEngine;
    }

    /**
     * Gets the operating system name.
     *
     * @return string|null Returns the operating system name or `NULL` if it could not be determined.
     */
    public function getOperatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    /**
     * Sets the operating system name.
     *
     * @param string $operatingSystem The operating system name
     */
    public function setOperatingSystem(string $operatingSystem): void
    {
        $this->operatingSystem = $operatingSystem;
    }

    /**
     * Tells whether this user agent is unknown.
     *
     * @return bool Returns TRUE if this user agent is unknown, FALSE otherwise.
     */
    public function isUnknown(): bool
    {
        return empty($this->browserName);
    }

    /**
     * Tells whether this user agent is a known browser.
     *
     * @return bool
     */
    public function isBrowser(): bool
    {
        return in_array($this->getBrowserName(), $this->getKnownBrowsers());
    }

    /**
     * Tells whether this user agent is a known bot/crawler.
     *
     * @return bool Returns TRUE if this user agent is a bot, FALSE otherwise.
     */
    public function isBot(): bool
    {
        return in_array($this->getBrowserName(), $this->getKnownBots());
    }

    /**
     * Configures the user agent information from a user agent string.
     *
     * @param string                     $string The user agent string
     * @param UserAgentStringParser|null $parser The parser used to parse the string
     */
    public function configureFromUserAgentString(string $string, ?UserAgentStringParser $parser = null): void
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
    public function toArray(): array
    {
        return [
            'browser_name' => $this->getBrowserName(),
            'browser_version' => $this->getBrowserVersion(),
            'browser_engine' => $this->getBrowserEngine(),
            'operating_system' => $this->getOperatingSystem()
        ];
    }

    /**
     * Configures the user agent information from an array.
     *
     * @param array $data The data array
     */
    public function fromArray(array $data): void
    {
        if (isset($data['browser_name'])) {
            $this->setBrowserName($data['browser_name']);
        }

        if (isset($data['browser_version'])) {
            $this->setBrowserVersion($data['browser_version']);
        }

        if (isset($data['browser_engine'])) {
            $this->setBrowserEngine($data['browser_engine']);
        }

        if (isset($data['operating_system'])) {
            $this->setOperatingSystem($data['operating_system']);
        }
    }

    protected function getKnownBrowsers(): array
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
    protected function getKnownBots(): array
    {
        return [
            'googlebot',
            'bingbot',
            'msnbot',
            'yahoobot',
            'yandexbot',
            'baidubot',
            'facebookbot'
        ];
    }
}
