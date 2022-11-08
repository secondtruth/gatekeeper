<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\ACL;

use Secondtruth\Gatekeeper\Listing\IPList;
use Secondtruth\Gatekeeper\Visitor;

/**
 * Access Control List based on IP addresses
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class IPAddressACL implements ACLInterface
{
    /**
     * The IP whitelist
     */
    public readonly IPList $allowed;

    /**
     * The IP blacklist
     */
    public readonly IPList $denied;

    public function __construct(?IPList $allowed = null, ?IPList $denied = null)
    {
        $this->allowed = $allowed ?? new IPList();
        $this->denied = $denied ?? new IPList();
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed(Visitor $visitor)
    {
        return $this->allowed->match($visitor->getIP());
    }

    /**
     * {@inheritdoc}
     */
    public function isDenied(Visitor $visitor)
    {
        return $this->denied->match($visitor->getIP());
    }
}
