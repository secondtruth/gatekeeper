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
use FlameCore\Gatekeeper\Listing\StringList;

/**
 * Blacklist visitor User Agents which should get blocked.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UserAgentBlacklistCheck implements CheckInterface
{
    /**
     * List of untrusted User Agents
     *
     * @var \FlameCore\Gatekeeper\Listing\StringList
     */
    protected $blacklist;

    /**
     * Creates a UserAgentBlacklistCheck object.
     *
     * @param \FlameCore\Gatekeeper\Listing\StringList $blacklist List of untrusted User Agents
     */
    public function __construct(StringList $blacklist = null)
    {
        $this->setBlacklist($blacklist ?: new StringList());
    }

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        $uastring = $visitor->getUserAgent()->getUserAgentString();
        if ($this->blacklist->match($uastring)) {
            return CheckInterface::RESULT_BLOCK;
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Sets the list of untrusted User Agents.
     *
     * @return \FlameCore\Gatekeeper\Listing\StringList
     */
    public function getBlacklist()
    {
        return $this->blacklist;
    }

    /**
     * Gets the list of untrusted User Agents.
     *
     * @param \FlameCore\Gatekeeper\Listing\StringList $blacklist List of untrusted User Agents
     */
    public function setBlacklist(StringList $blacklist)
    {
        $this->blacklist = $blacklist;
    }
}
