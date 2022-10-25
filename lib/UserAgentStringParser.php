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
 * Simple User Agent string parser
 *
 * @author   Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UserAgentStringParser
{
    /**
     * Parses a user agent string.
     *
     * @param string $string The user agent string (Default: `$_SERVER['HTTP_USER_AGENT']`)
     * @return array Returns the user agent information:
     *
     *   - `string`:           The original user agent string
     *   - `browser_name`:     The browser name, e.g. `"chrome"`
     *   - `browser_version`:  The browser version, e.g. `"3.6"`
     *   - `browser_engine`:   The browser engine, e.g. `"webkit"`
     *   - `operating_system`: The operating system, e.g. `"linux"`
     */
    public function parse($string = null)
    {
        // use current user agent string as default
        if ($string === null) {
            $string = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }

        // parse quickly (with medium accuracy)
        $information = $this->doParse($string);

        // run some filters to increase accuracy
        $information = $this->filterBots($information);
        $information = $this->filterBrowserNames($information);
        $information = $this->filterBrowserVersions($information);
        $information = $this->filterBrowserEngines($information);
        $information = $this->filterOperatingSystems($information);

        return $information;
    }

    /**
     * Make user agent string lowercase, and replace browser aliases.
     *
     * @param string $string The dirty user agent string
     * @return string Returns the clean user agent string.
     */
    public function cleanUserAgentString($string)
    {
        // clean up the string
        $string = trim(strtolower($string));

        // replace browser names with their aliases
        $string = strtr($string, $this->getKnownBrowserAliases());

        // replace operating system names with their aliases
        $string = strtr($string, $this->getKnownOperatingSystemAliases());

        // replace engine names with their aliases
        $string = strtr($string, $this->getKnownEngineAliases());

        return $string;
    }

    /**
     * Extracts information from the user agent string.
     *
     * @param string $string The user agent string
     * @return array Returns the user agent information.
     */
    protected function doParse($string)
    {
        $userAgent = array(
            'string' => $this->cleanUserAgentString($string),
            'browser_name' => null,
            'browser_version' => null,
            'browser_engine' => null,
            'operating_system' => null
        );

        if (empty($userAgent['string'])) {
            return $userAgent;
        }

        // Build regex that matches phrases for known browsers (e.g. "Firefox/2.0" or "MSIE 6.0").
        // This only matches the major and minor version numbers (e.g. "2.0.0.6" is parsed as simply "2.0").
        $pattern = '#(' . join('|', $this->getKnownBrowsers()) . ')[/ ]+([0-9]+(?:\.[0-9]+)?)#';

        // Find all phrases (or return empty array if none found)
        if (preg_match_all($pattern, $userAgent['string'], $matches)) {
            // Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase, Opera 7,8 has a MSIE phrase),
            // use the last one found (the right-most one in the UA). That's usually the most correct.
            $i = count($matches[1]) - 1;

            if (isset($matches[1][$i])) {
                $userAgent['browser_name'] = $matches[1][$i];
            }

            if (isset($matches[2][$i])) {
                $userAgent['browser_version'] = $matches[2][$i];
            }
        }

        // Find operating system
        $pattern = '#' . join('|', $this->getKnownOperatingSystems()) . '#';

        if (preg_match($pattern, $userAgent['string'], $match)) {
            if (isset($match[0])) {
                $userAgent['operating_system'] = $match[0];
            }
        }

        // Find browser engine
        $pattern = '#' . join('|', $this->getKnownEngines()) . '#';

        if (preg_match($pattern, $userAgent['string'], $match)) {
            if (isset($match[0])) {
                $userAgent['browser_engine'] = $match[0];
            }
        }

        return $userAgent;
    }

    /**
     * Gets known browsers.
     *
     * @return string[]
     */
    protected function getKnownBrowsers()
    {
        return array(
            'msie',
            'firefox',
            'safari',
            'opera',
            'netscape',
            'konqueror',
            'edge',
            'lynx',
            'chrome',
            'yabrowser',
            'maxthon',
            'googlebot',
            'bingbot',
            'msnbot',
            'yahoobot',
            'yandex\w+',
            'baiduspider\w*',
            'facebookbot'
        );
    }

    /**
     * Gets known browser aliases.
     *
     * @return array
     */
    protected function getKnownBrowserAliases()
    {
        return array(
            'opr' => 'opera',
            'shiretoko' => 'firefox',
            'namoroka' => 'firefox',
            'shredder' => 'firefox',
            'minefield' => 'firefox',
            'granparadiso' => 'firefox',
            'iceweasel' => 'firefox',
            'facebookexternalhit' => 'facebookbot'
        );
    }

    /**
     * Gets known operating systems.
     *
     * @return string[]
     */
    protected function getKnownOperatingSystems()
    {
        return array(
            'Windows 10',
            'Windows 8.1',
            'Windows 8',
            'Windows 7',
            'Windows Vista',
            'Windows Server 2003/XP x64',
            'Windows XP',
            'Windows 2000',
            'Windows ME',
            'Windows 98',
            'Windows 95',
            'Windows 3.11',
            'Mac OS X',
            'Mac OS 9',
            'Macintosh',
            'Ubuntu',
            'iPhone',
            'iPod',
            'iPad',
            'Android',
            'BlackBerry',
            'Mobile',
            'Linux'
        );
    }

    /**
     * Gets known operating system aliases.
     *
     * @return array
     */
    protected function getKnownOperatingSystemAliases()
    {
        return array(
            'windows nt 10.0' => 'Windows 10',
            'windows nt 6.3' => 'Windows 8.1',
            'windows nt 6.2' => 'Windows 8',
            'windows nt 6.1' => 'Windows 7',
            'windows nt 6.0' => 'Windows Vista',
            'windows nt 5.2' => 'Windows Server 2003/XP x64',
            'windows nt 5.1' => 'Windows XP',
            'windows xp' => 'Windows XP',
            'windows nt 5.0' => 'Windows 2000',
            'windows me' => 'Windows ME',
            'win98' => 'Windows 98',
            'win95' => 'Windows 95',
            'win16' => 'Windows 3.11',
            'mac os x' => 'Mac OS X',
            'mac_powerpc' => 'Mac OS 9',
            'ubuntu' => 'Ubuntu',
            'iphone' => 'iPhone',
            'ipod' => 'iPod',
            'ipad' => 'iPad',
            'android' => 'Android',
            'blackberry' => 'BlackBerry',
            'webos' => 'Mobile',
            'linux' => 'Linux'
        );
    }

    /**
     * Gets known browser engines.
     *
     * @return string[]
     */
    protected function getKnownEngines()
    {
        return array(
            'gecko',
            'webkit',
            'trident',
            'presto',
            'khtml'
        );
    }

    /**
     * Gets known browser engine aliases.
     *
     * @return array
     */
    protected function getKnownEngineAliases()
    {
        return array();
    }

    /**
     * Filters bots to increase accuracy.
     *
     * @param array $userAgent The user agent information
     * @return array Returns the updated user agent information.
     */
    protected function filterBots(array $userAgent)
    {
        if ($userAgent['browser_name'] !== null) {
            // Yandex Bot
            if (stripos($userAgent['browser_name'], 'yandex') === 0) {
                $userAgent['browser_name'] = 'yandexbot';
                return $userAgent;
            }

            // Baidu Bot
            if (stripos($userAgent['browser_name'], 'baiduspider') === 0) {
                $userAgent['browser_name'] = 'baidubot';
                return $userAgent;
            }
        } else {
            // Yahoo bot has a special user agent string
            if (stripos($userAgent['string'], 'yahoo! slurp')) {
                $userAgent['browser_name'] = 'yahoobot';
                return $userAgent;
            }
        }

        return $userAgent;
    }

    /**
     * Filters browser names to increase accuracy.
     *
     * @param array $userAgent The user agent information
     * @return array Returns the updated user agent information.
     */
    protected function filterBrowserNames(array $userAgent)
    {
        // Many browsers have a safari like signature
        if ($userAgent['browser_name'] === 'safari') {
            if (strpos($userAgent['string'], 'yabrowser/')) {
                $userAgent['browser_name'] = 'yabrowser';
                $userAgent['browser_version'] = preg_replace('|.+yabrowser/([0-9]+(?:\.[0-9]+)?).+|', '$1', $userAgent['string']);
                return $userAgent;
            } elseif (strpos($userAgent['string'], 'maxthon/')) {
                $userAgent['browser_name'] = 'maxthon';
                $userAgent['browser_version'] = preg_replace('|.+maxthon/([0-9]+(?:\.[0-9]+)?).+|', '$1', $userAgent['string']);
                return $userAgent;
            } elseif (strpos($userAgent['string'], 'chrome/')) {
                $userAgent['browser_name'] = 'chrome';
                $userAgent['browser_version'] = preg_replace('|.+chrome/([0-9]+(?:\.[0-9]+)?).+|', '$1', $userAgent['string']);
                return $userAgent;
            }
        }

        // IE11 hasn't 'MSIE' in its user agent string
        if (empty($userAgent['browser_name']) && $userAgent['browser_engine'] === 'trident' && strpos($userAgent['string'], 'rv:')) {
            $userAgent['browser_name'] = 'msie';
            $userAgent['browser_version'] = preg_replace('|.+rv:([0-9]+(?:\.[0-9]+)+).+|', '$1', $userAgent['string']);
            return $userAgent;
        }

        return $userAgent;
    }

    /**
     * Filters browser versions to increase accuracy.
     *
     * @param array $userAgent The user agent information
     * @return array Returns the updated user agent information.
     */
    protected function filterBrowserVersions(array $userAgent)
    {
        // Safari version is not encoded "normally"
        if ($userAgent['browser_name'] === 'safari' && strpos($userAgent['string'], ' version/')) {
            $userAgent['browser_version'] = preg_replace('|.+\sversion/([0-9]+(?:\.[0-9]+)?).+|', '$1', $userAgent['string']);
            return $userAgent;
        }

        // Opera 10.00 (and higher) version number is located at the end
        if ($userAgent['browser_name'] === 'opera' && strpos($userAgent['string'], ' version/')) {
            $userAgent['browser_version'] = preg_replace('|.+\sversion/([0-9]+\.[0-9]+)\s*.*|', '$1', $userAgent['string']);
            return $userAgent;
        }

        return $userAgent;
    }

    /**
     * Filters browser engines to increase accuracy.
     *
     * @param array $userAgent The user agent information
     * @return array Returns the updated user agent information.
     */
    protected function filterBrowserEngines(array $userAgent)
    {
        // MSIE does not always declare its engine
        if ($userAgent['browser_name'] === 'msie' && empty($userAgent['browser_engine'])) {
            $userAgent['browser_engine'] = 'trident';
            return $userAgent;
        }

        return $userAgent;
    }

    /**
     * Filters operating systems to increase accuracy.
     *
     * @param array $userAgent The user agent information
     * @return array Returns the updated user agent information.
     */
    protected function filterOperatingSystems(array $userAgent)
    {
        // Android instead of Linux
        if (strpos($userAgent['string'], 'Android ')) {
            $userAgent['operating_system'] = preg_replace('|.+(Android [0-9]+(?:\.[0-9]+)+).+|', '$1', $userAgent['string']);
            return $userAgent;
        }

        return $userAgent;
    }
}
