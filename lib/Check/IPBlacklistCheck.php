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

use FlameCore\Gatekeeper\Visitor;
use FlameCore\Gatekeeper\Listing\IPList;

/**
 * Blacklist visitor IPs which should get blocked.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class IPBlacklistCheck extends AbstractCheck
{
    /**
     * The IP blacklist
     *
     * @var \FlameCore\Gatekeeper\Listing\IPList
     */
    protected $blacklist;

    /**
     * Creates an IPBlacklistCheck object.
     *
     * @param \FlameCore\Gatekeeper\Listing\IPList $blacklist The IP blacklist
     */
    public function __construct(IPList $blacklist = null)
    {
        $this->setBlacklist($blacklist ?: new IPList());
    }

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        if ($this->blacklist->match($visitor->getIP())) {
            return CheckInterface::RESULT_BLOCK;
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Gets the IP blacklist.
     *
     * @return \FlameCore\Gatekeeper\Listing\IPList
     */
    public function getBlacklist()
    {
        return $this->blacklist;
    }

    /**
     * Sets the IP blacklist.
     *
     * @param \FlameCore\Gatekeeper\Listing\IPList $blacklist The IP blacklist
     */
    public function setBlacklist(IPList $blacklist)
    {
        $this->blacklist = $blacklist;
    }
}
