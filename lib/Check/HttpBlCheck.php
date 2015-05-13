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

namespace FlameCore\Gatekeeper\Check;

use FlameCore\Gatekeeper\Utils;
use FlameCore\Gatekeeper\Visitor;

/**
 * Class HttpBlCheck
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class HttpBlCheck implements CheckInterface
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var int
     */
    protected $threatLevel;

    /**
     * @var int
     */
    protected $maxAge;

    /**
     * @param string $apiKey
     * @param int $threatLevel
     * @param int $maxAge
     */
    public function __construct($apiKey, $threatLevel = null, $maxAge = null)
    {
        $this->setApiKey($apiKey);
        $this->setThreatLevel($threatLevel ?: 25);
        $this->setMaxAge($maxAge ?: 30);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
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
     * @return int
     */
    public function getThreatLevel()
    {
        return $this->threatLevel;
    }

    /**
     * @param int $threatLevel
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
     * @return int
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * @param int $maxAge
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

    /**
     * @param string $ip
     * @return string
     */
    protected function getIPv4Arpa($ip)
    {
        return implode('.', array_reverse(explode('.', $ip)));
    }
}
