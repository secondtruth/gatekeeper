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
     * @param $address
     * @return bool
     */
    public static function isIPv6($address)
    {
        return strpos($address, ':') !== false;
    }

    /**
     * Determine if an IP address resides in a CIDR netblock or netblocks.
     *
     * @param string $address
     * @param string|array $cidr
     * @return bool
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
