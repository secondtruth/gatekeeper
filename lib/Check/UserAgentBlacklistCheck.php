<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Check;

use Secondtruth\Gatekeeper\Visitor;
use Secondtruth\Gatekeeper\Listing\StringList;

/**
 * Blacklist visitor User Agents which should get blocked.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UserAgentBlacklistCheck extends AbstractCheck
{
    /**
     * List of untrusted User Agents
     *
     * @var \Secondtruth\Gatekeeper\Listing\StringList
     */
    protected $blacklist;

    /**
     * Creates a UserAgentBlacklistCheck object.
     *
     * @param \Secondtruth\Gatekeeper\Listing\StringList $blacklist List of untrusted User Agents
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
        if ($this->blacklist->match((string) $uastring)) {
            return CheckInterface::RESULT_BLOCK;
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Sets the list of untrusted User Agents.
     *
     * @return \Secondtruth\Gatekeeper\Listing\StringList
     */
    public function getBlacklist()
    {
        return $this->blacklist;
    }

    /**
     * Gets the list of untrusted User Agents.
     *
     * @param \Secondtruth\Gatekeeper\Listing\StringList $blacklist List of untrusted User Agents
     */
    public function setBlacklist(StringList $blacklist)
    {
        $this->blacklist = $blacklist;
    }
}
