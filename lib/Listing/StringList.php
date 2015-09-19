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

namespace FlameCore\Gatekeeper\Listing;

/**
 * String matching list
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class StringList extends AbstractList
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
     * {@inheritdoc}
     */
    public function match($value)
    {
        if (in_array($value, $this->equal)) {
            return true;
        }

        foreach ($this->beginsWith as $substring) {
            if (strpos($value, $substring) === 0) {
                return true;
            }
        }

        foreach ($this->endsWith as $substring) {
            if (substr($value, strlen($substring) * -1) === $substring) {
                return true;
            }
        }

        foreach ($this->contains as $substring) {
            if (strpos($value, $substring) !== false) {
                return true;
            }
        }

        foreach ($this->regexes as $regex) {
            if (preg_match($regex, $value)) {
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

    /**
     * {@inheritdoc}
     */
    protected function addFileEntry($value)
    {
        if ($value[0] == '*' && substr($value, -1) == '*') {
            $this->contains(trim(substr($value, 1, -1), '*'));
        } elseif ($value[0] == '*') {
            $this->endsWith(trim(substr($value, 1), '*'));
        } elseif (substr($value, -1) == '*') {
            $this->beginsWith(trim(substr($value, 0, -1), '*'));
        } elseif (substr($value, 0, 2) == 'r:') {
            $this->matches(substr($value, 2));
        } else {
            $this->is($value);
        }
    }
}
