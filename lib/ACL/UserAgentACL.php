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

use Secondtruth\Gatekeeper\Listing\StringList;
use Secondtruth\Gatekeeper\Visitor;

/**
 * Access Control List based on User Agents
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UserAgentACL implements ACLInterface
{
    /**
     * The list of trusted user agents
     */
    public readonly StringList $allowed;

    /**
     * List of untrusted User Agents
     */
    public readonly StringList $denied;

    /**
     * Creates a new Access Control List.
     *
     * @param StringList|null $allowed
     * @param StringList|null $denied
     */
    public function __construct(?StringList $allowed = null, ?StringList $denied = null)
    {
        $this->allowed = $allowed ?? new StringList();
        $this->denied = $denied ?? new StringList();
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed(Visitor $visitor)
    {
        return $this->allowed->match((string) $visitor->getUserAgent()->getUserAgentString());
    }

    /**
     * {@inheritdoc}
     */
    public function isDenied(Visitor $visitor)
    {
        return $this->denied->match((string) $visitor->getUserAgent()->getUserAgentString());
    }
}
