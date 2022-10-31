<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Listing;

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
    protected array $equal = [];

    /**
     * List of strings to match at the beginning
     *
     * @var string[]
     */
    protected array $startsWith = [];

    /**
     * List of strings to match at the end
     *
     * @var string[]
     */
    protected array $endsWith = [];

    /**
     * List of strings to match anywhere
     *
     * @var string[]
     */
    protected array $contains = [];

    /**
     * List of regular expressions to match
     *
     * @var string[]
     */
    protected array $matching = [];

    /**
     * {@inheritdoc}
     */
    public function match(mixed $value)
    {
        if (in_array($value, $this->equal)) {
            return true;
        }

        foreach ($this->startsWith as $substring) {
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

        foreach ($this->matching as $regex) {
            if (preg_match($regex, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function add(string|array $values)
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
    public function equal(string|array $string)
    {
        $strings = self::toArrayOfStrings($string);
        $this->equal = self::merge($this->equal, $strings);
    }

    /**
     * Adds a string or an array of strings to match at the beginning.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function startsWith(string|array $string)
    {
        $strings = self::toArrayOfStrings($string);
        $this->startsWith = self::merge($this->startsWith, $strings);
    }

    /**
     * Adds a string or an array of strings to match at the end.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function endsWith(string|array $string)
    {
        $strings = self::toArrayOfStrings($string);
        $this->endsWith = self::merge($this->endsWith, $strings);
    }

    /**
     * Adds a string or an array of strings to match anywhere.
     *
     * @param string|string[] $string The string(s) to add
     */
    public function contains(string|array $string)
    {
        $strings = self::toArrayOfStrings($string);
        $this->contains = self::merge($this->contains, $strings);
    }

    /**
     * Adds a regular expression or an array of regular expressions to match.
     *
     * @param string|string[] $regex The regular expression(s) to add
     */
    public function matching(string|array $regex)
    {
        $regexes = self::toArrayOfStrings($regex);
        $this->matching = self::merge($this->matching, $regexes);
    }

    /**
     * Adds an entry using a wildcard pattern or regular expression ("r:")
     *
     * @param string $value The pattern to add
     */
    protected function addPattern(string $value)
    {
        if ($value[0] == '*' && substr($value, -1) == '*') {
            $this->contains(trim(substr($value, 1, -1), '*'));
        } elseif ($value[0] == '*') {
            $this->endsWith(trim(substr($value, 1), '*'));
        } elseif (substr($value, -1) == '*') {
            $this->startsWith(trim(substr($value, 0, -1), '*'));
        } elseif (substr($value, 0, 2) == 'r:') {
            $this->matching(substr($value, 2));
        } else {
            $this->equal($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function addFileEntry(string $value)
    {
        $this->addPattern($value);
    }
}
