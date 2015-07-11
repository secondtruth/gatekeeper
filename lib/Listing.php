<?php
/**
 * Gatekeeper Library
 * Copyright (C) 2015 IceFlame.net
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE
 * FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER
 * IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING
 * OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 *
 * @package  FlameCore\Gatekeeper
 * @version  0.1-dev
 * @link     http://www.flamecore.org
 * @license  ISC License <http://opensource.org/licenses/ISC>
 */

namespace FlameCore\Gatekeeper;

/**
 * Class List
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Listing
{
    /**
     * @var string[]
     */
    protected $equal = array();

    /**
     * @var string[]
     */
    protected $beginsWith = array();

    /**
     * @var string[]
     */
    protected $endsWith = array();

    /**
     * @var string[]
     */
    protected $contains = array();

    /**
     * @var string[]
     */
    protected $regexes = array();

    /**
     * Checks if the string matches the list.
     *
     * @param string $string
     * @return bool
     */
    public function match($string)
    {
        if (in_array($string, $this->equal)) {
            return true;
        }

        foreach ($this->beginsWith as $value) {
            if (strpos($string, $value) === 0) {
                return true;
            }
        }

        foreach ($this->endsWith as $value) {
            if (substr($string, -1) === $value) {
                return true;
            }
        }

        foreach ($this->contains as $value) {
            if (strpos($string, $value) !== false) {
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
     * @param string|string[] $string
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
     * @param string|string[] $string
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
     * @param string|string[] $string
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
     * @param string|string[] $string
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
     * @param string|string[] $regex
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
