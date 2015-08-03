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

use FlameCore\Gatekeeper\Listing;
use FlameCore\Gatekeeper\Utils;
use FlameCore\Gatekeeper\Visitor;

/**
 * Blacklist visitor IPs and User Agents which should get blocked.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class BlacklistCheck implements CheckInterface
{
    /**
     * The IP blacklist
     *
     * @var string[]
     */
    protected $blacklist = array();

    /**
     * List of untrusted User Agents
     *
     * @var \FlameCore\Gatekeeper\Listing
     */
    protected $untrustedUserAgents;

    /**
     * Creates a BlacklistCheck object.
     *
     * @param array $blacklist The IP blacklist
     * @param \FlameCore\Gatekeeper\Listing $untrustedUserAgents List of untrusted User Agents
     */
    public function __construct(array $blacklist = [], Listing $untrustedUserAgents = null)
    {
        $this->setBlacklist($blacklist);
        $this->setUntrustedUserAgents($untrustedUserAgents ?: new Listing());
    }

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        if (Utils::matchCIDR($visitor->getIP(), $this->blacklist)) {
            return CheckInterface::RESULT_BLOCK;
        }

        $uastring = $visitor->getUserAgent()->getUserAgentString();
        if ($this->untrustedUserAgents->match($uastring)) {
            return CheckInterface::RESULT_BLOCK;
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Gets the IP blacklist.
     *
     * @return string[]
     */
    public function getBlacklist()
    {
        return $this->blacklist;
    }

    /**
     * Sets the IP blacklist.
     *
     * @param array $blacklist The IP blacklist
     */
    public function setBlacklist(array $blacklist)
    {
        $this->blacklist = array_filter($blacklist);
    }

    /**
     * Loads the IP blacklist from the given file.
     *
     * @param string $file The blacklist file
     */
    public function loadBlacklist($file)
    {
        $whitelist = file($file, FILE_SKIP_EMPTY_LINES);

        $this->setBlacklist($whitelist);
    }

    /**
     * Sets the list of untrusted User Agents.
     *
     * @return \FlameCore\Gatekeeper\Listing
     */
    public function getUntrustedUserAgents()
    {
        return $this->untrustedUserAgents;
    }

    /**
     * Gets the list of untrusted User Agents.
     *
     * @param \FlameCore\Gatekeeper\Listing $untrustedUserAgents List of untrusted User Agents
     */
    public function setUntrustedUserAgents(Listing $untrustedUserAgents)
    {
        $this->untrustedUserAgents = $untrustedUserAgents;
    }
}
