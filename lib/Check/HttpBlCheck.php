<?php
/**
 * FlameCore Gatekeeper
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
 * @license  http://opensource.org/licenses/ISC ISC License
 */

namespace FlameCore\Gatekeeper\Check;

use FlameCore\Gatekeeper\Utils;
use FlameCore\Gatekeeper\Visitor;
use FlameCore\Gatekeeper\Check\Traits\BlackholeTrait;

/**
 * Query the http:BL API and block visitors with matching IPs.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class HttpBlCheck implements CheckInterface
{
    use BlackholeTrait;

    /**
     * The API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The minimum threat level for found matches
     *
     * @var int
     */
    protected $threatLevel;

    /**
     * The maximum age for found matches
     *
     * @var int
     */
    protected $maxAge;

    /**
     * @param string $apiKey The API key
     * @param int $threatLevel The minimum threat level for found matches
     * @param int $maxAge The maximum age for found matches
     */
    public function __construct($apiKey, $threatLevel = null, $maxAge = null)
    {
        $this->setApiKey($apiKey);
        $this->setThreatLevel($threatLevel ?: 25);
        $this->setMaxAge($maxAge ?: 30);
    }

    /**
     * Gets the API key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets the API key.
     *
     * @param string $apiKey The API key
     */
    public function setApiKey($apiKey)
    {
        $apiKey = (string) $apiKey;

        if ($apiKey === '') {
            throw new \InvalidArgumentException('The http:BL API key must be specified.');
        }

        $this->apiKey = $apiKey;
    }

    /**
     * Gets the minimum threat level for found matches.
     *
     * @return int
     */
    public function getThreatLevel()
    {
        return $this->threatLevel;
    }

    /**
     * Sets the minimum threat level for found matches.
     *
     * @param int $threatLevel The minimum threat level for found matches
     */
    public function setThreatLevel($threatLevel)
    {
        $threatLevel = (int) $threatLevel;

        if ($threatLevel > 255 || $threatLevel < 0) {
            throw new \InvalidArgumentException('The http:BL threat level value is invalid. Allowed values range from 0 and 255.');
        }

        $this->threatLevel = $threatLevel;
    }

    /**
     * Gets the maximum age for found matches.
     *
     * @return int
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * Sets the maximum age for found matches.
     *
     * @param int $maxAge The maximum age for found matches
     */
    public function setMaxAge($maxAge)
    {
        $maxAge = (int) $maxAge;

        if ($maxAge > 255 || $maxAge < 0) {
            throw new \InvalidArgumentException('The http:BL max age value is invalid. Allowed values range from 0 and 255.');
        }

        $this->maxAge = $maxAge;
    }

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        $ip = $visitor->getIP();

        // Can't use IPv6 addresses yet
        if (Utils::isIPv6($ip)) {
            return CheckInterface::RESULT_OKAY;
        }

        $revip = $this->getIPv4Arpa($ip);
        $result = gethostbynamel("$this->apiKey.$revip.dnsbl.httpbl.org.");

        if (!empty($result)) {
            $resip = explode('.', $result[0]);

            if ($resip[0] == 127 && ($resip[3] & 7) && $resip[2] >= $this->threatLevel && $resip[1] <= $this->maxAge) {
                return CheckInterface::RESULT_BLOCK;
            }
        }

        return CheckInterface::RESULT_OKAY;
    }
}
