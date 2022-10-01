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
 * Class IP
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class IP
{
    /**
     * The IP address
     *
     * @var string
     */
    protected $ip;

    /**
     * Whether the IP address is IPv6
     *
     * @var bool
     */
    protected $isIPv6 = false;

    /**
     * Creates an IP object.
     *
     * @param string $ip The IP address
     */
    public function __construct($ip)
    {
        $this->ip = (string) $ip;
        $this->isIPv6 = strpos($ip, ':') !== false;
    }

    /**
     * Checks for an IPv6 address in a quick and dirty way.
     *
     * @return bool Returns TRUE if the IP address is IPv6, FALSE otherwise.
     */
    public function isIPv6()
    {
        return $this->isIPv6;
    }

    /**
     * Gets the .arpa version of the IP address.
     *
     * @return string
     */
    public function toArpa()
    {
        if ($this->isIPv6) {
            $unpacked = unpack('H*hex', inet_pton($this->ip));
            $parts = str_split($unpacked['hex']);
        } else {
            $parts = explode('.', $this->ip);
        }

        return implode('.', array_reverse($parts));
    }

    /**
     * Returns the string representation of the object.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->ip;
    }
}
