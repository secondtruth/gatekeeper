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

namespace FlameCore\Gatekeeper\Check;

use FlameCore\Gatekeeper\Utils;
use FlameCore\Gatekeeper\Visitor;
use FlameCore\Gatekeeper\Check\Traits\BlackholeTrait;

/**
 * Query DNS blackhole lists and block visitors with matching IPs.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class BlackholeCheck implements CheckInterface
{
    use BlackholeTrait;

    /**
     * The registered blackhole lists
     *
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
                return CheckInterface::RESULT_BLOCK;
            }
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Gets the registered blackhole lists.
     *
     * @return string[]
     */
    public function getLists()
    {
        return $this->lists;
    }

    /**
     * Registers a blackhole list.
     *
     * @param string $list The blackhole list to add
     */
    public function addList($list)
    {
        if (in_array($list, $this->lists)) {
            return;
        }

        $this->lists[] = $list;
    }

    /**
     * Registers blackhole lists.
     *
     * @param string[] $lists The blackhole lists to query
     */
    public function addLists(array $lists)
    {
        $this->lists = array_unique(array_merge($this->lists, $lists));
    }
}
