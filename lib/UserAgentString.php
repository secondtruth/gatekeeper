<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace FlameCore\Gatekeeper;

/**
 * The UserAgentString class.
 *
 * @author Christian Neff <christian.neff@gmail.com>
 */
class UserAgentString
{
    private $string;

    public function __construct($string)
    {
        $this->string = $string;
    }

    public function __toString()
    {
        return $this->string;
    }

    public function contains($needle, $caseSensitive = false)
    {
        return ($caseSensitive ? strpos($this->string, $needle) : stripos($this->string, $needle)) !== false;
    }

    public function containsAny(array $needles, $caseSensitive = false)
    {
        foreach ($needles as $needle) {
            if ($this->contains($needle, $caseSensitive)) {
                return true;
            }
        }

        return false;
    }

    public function startsWith($needle, $caseSensitive = false)
    {
        return ($caseSensitive ? strpos($this->string, $needle) : stripos($this->string, $needle)) === 0;
    }

    public function startsWithAny(array $needles, $caseSensitive = false)
    {
        foreach ($needles as $needle) {
            if ($this->startsWith($needle, $caseSensitive)) {
                return true;
            }
        }

        return false;
    }

    public function compare($string, $caseSensitive = true)
    {
        return $caseSensitive ? strcmp($this->string, $string) : strcasecmp($this->string, $string);
    }

    public function compareStart($needle, $caseSensitive = true)
    {
        return $caseSensitive ? strncmp($this->string, $needle, mb_strlen($needle)) : strncasecmp($this->string, $needle, mb_strlen($needle));
    }
}
