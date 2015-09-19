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
 * Generic string matching list
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Listing
{
    /**
     * List of strings to match equally
     *
     * @var string[]
     */
    protected $equal = array();

    /**
     * List of strings to match at the beginning
     *
     * @var string[]
     */
    protected $beginsWith = array();

    /**
     * List of strings to match at the end
     *
     * @var string[]
     */
    protected $endsWith = array();

    /**
     * List of strings to match anywhere
     *
     * @var string[]
     */
    protected $contains = array();

    /**
     * List of regular expressions to match
     *
     * @var string[]
     */
    protected $regexes = array();

    /**
     * Checks if the given string matches the list.
     *
     * @param string $string The string to test
     * @return bool Returns TRUE if the string matches the list, FALSE otherwise.
     */
    public function match($string)
    {
        if (in_array($string, $this->equal)) {
            return true;
        }

        foreach ($this->beginsWith as $substring) {
            if (strpos($string, $substring) === 0) {
                return true;
            }
        }

        foreach ($this->endsWith as $substring) {
            if (substr($string, strlen($substring) * -1) === $substring) {
                return true;
            }
        }

        foreach ($this->contains as $substring) {
            if (strpos($string, $substring) !== false) {
                return true;
            }
        }

        foreach ($this->regexes as $regex) {
            if (preg_match($regex, $string)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adds a string or an array of strings to match equally.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function is($string)
    {
        if (is_array($string)) {
            $strings = array_map('strval', $string);
            $this->equal = array_merge($this->equal, $strings);
        } else {
            $this->equal[] = (string) $string;
        }
    }

    /**
     * Adds a string or an array of strings to match at the beginning.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function beginsWith($string)
    {
        if (is_array($string)) {
            $strings = array_map('strval', $string);
            $this->beginsWith = array_merge($this->beginsWith, $strings);
        } else {
            $this->beginsWith[] = (string) $string;
        }
    }

    /**
     * Adds a string or an array of strings to match at the end.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function endsWith($string)
    {
        if (is_array($string)) {
            $strings = array_map('strval', $string);
            $this->endsWith = array_merge($this->endsWith, $strings);
        } else {
            $this->endsWith[] = (string) $string;
        }
    }

    /**
     * Adds a string or an array of strings to match anywhere.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function contains($string)
    {
        if (is_array($string)) {
            $strings = array_map('strval', $string);
            $this->contains = array_merge($this->contains, $strings);
        } else {
            $this->contains[] = (string) $string;
        }
    }

    /**
     * Adds a regular expression or an array of regular expressions to match.
     *
     * @param string|string[] $regex The regular expression(s) to add
     */
    public function matches($regex)
    {
        if (is_array($regex)) {
            $regexes = array_map('strval', $regex);
            $this->regexes = array_merge($this->regexes, $regexes);
        } else {
            $this->regexes[] = (string) $regex;
        }
    }
}
