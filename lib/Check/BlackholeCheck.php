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
 * Class BlackholeCheck
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class BlackholeCheck implements CheckInterface
{
    /**
     * @var string[]
     */
    protected $lists = array();

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        $ip = $visitor->getIP();
        $revip = Utils::isIPv6($ip) ? $this->getIPv6Arpa($ip) : $this->getIPv4Arpa($ip);

        foreach ($this->lists as $list) {
            if (checkdnsrr("$revip.$list.", 'A')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getLists()
    {
        return $this->lists;
    }

    /**
     * @param string $list
     */
    public function addList($list)
    {
        if (in_array($list, $this->lists)) {
            return;
        }

        $this->lists[] = $list;
    }

    /**
     * @param string[] $lists
     */
    public function addLists(array $lists)
    {
        $this->lists = array_unique(array_merge($this->lists, $lists));
    }

    /**
     * @param string $ip
     * @return string
     */
    protected function getIPv4Arpa($ip)
    {
        return implode('.', array_reverse(explode('.', $ip)));
    }

    /**
     * @param string $ip
     * @return string
     */
    protected function getIPv6Arpa($ip)
    {
        $unpack = unpack('H*hex', inet_pton($ip));
        return implode('.', array_reverse(str_split($unpack['hex'])));
    }
}
