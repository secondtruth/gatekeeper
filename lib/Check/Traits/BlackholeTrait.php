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

namespace FlameCore\Gatekeeper\Check\Traits;

/**
 * Trait BlackholeTrait
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
trait BlackholeTrait
{
    /**
     * Gets the .arpa version of an IPv4 address.
     *
     * @param string $ip The IPv4 address
     * @return string
     */
    protected function getIPv4Arpa($ip)
    {
        return implode('.', array_reverse(explode('.', $ip)));
    }

    /**
     * Gets the .arpa version of an IPv6 address.
     *
     * @param string $ip The IPv6 address
     * @return string
     */
    protected function getIPv6Arpa($ip)
    {
        $unpack = unpack('H*hex', inet_pton($ip));
        return implode('.', array_reverse(str_split($unpack['hex'])));
    }
}
