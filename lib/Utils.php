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
 * Class Utils
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Utils
{
    /**
     * Constructor disabled
     */
    private function __construct()
    {
    }

    /**
     * Checks for an IPv6 address in a quick and dirty way.
     *
     * @param string $address The IP address
     * @return bool Returns TRUE if the IP address is IPv6, FALSE otherwise.
     */
    public static function isIPv6($address)
    {
        return strpos($address, ':') !== false;
    }

    /**
     * Determine if an IP address resides in a CIDR netblock or netblocks.
     *
     * @param string $address The IP address
     * @param string|array $cidr The CIDR netblock or netblocks
     * @return bool Returns TRUE if the IP address resides in the given CIDR netblock or netblocks, FALSE otherwise.
     */
    public static function matchCIDR($address, $cidr)
    {
        if (is_array($cidr)) {
            foreach ($cidr as $cidrlet) {
                if (static::matchCIDR($address, $cidrlet)) {
                    return true;
                }
            }
        } else {
            if (strpos($cidr, '/')) {
                list($ip, $mask) = explode('/', $cidr);

                $mask = pow(2, 32) - pow(2, (32 - $mask));

                return ((ip2long($address) & $mask) == (ip2long($ip) & $mask));
            } else {
                return $address === $cidr;
            }
        }

        return false;
    }
}
