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
     * {@inheritdoc}
     */
    public function add($values)
    {
        foreach ((array) $values as $value) {
            $this->addPattern($value);
        }
    }

    /**
     * Adds a string or an array of strings to match equally.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function is($string)
    {
        $strings = array_map('strval', (array) $string);

        $this->equal = $this->merge($this->equal, $strings);
    }

    /**
     * Adds a string or an array of strings to match at the beginning.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function beginsWith($string)
    {
        $strings = array_map('strval', (array) $string);

        $this->beginsWith = $this->merge($this->beginsWith, $strings);
    }

    /**
     * Adds a string or an array of strings to match at the end.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function endsWith($string)
    {
        $strings = array_map('strval', (array) $string);

        $this->endsWith = $this->merge($this->endsWith, $strings);
    }

    /**
     * Adds a string or an array of strings to match anywhere.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function contains($string)
    {
        $strings = array_map('strval', (array) $string);

        $this->contains = $this->merge($this->contains, $strings);
    }

    /**
     * Adds a regular expression or an array of regular expressions to match.
     *
     * @param string|string[] $regex The regular expression(s) to add
     */
    public function matches($regex)
    {
        $regexes = array_map('strval', (array) $regex);

        $this->regexes = $this->merge($this->regexes, $regexes);
    }

    protected function addPattern($value)
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

    /**
     * {@inheritdoc}
     */
    protected function addFileEntry($value)
    {
        $this->addPattern($value);
    }
}
