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

use Secondtruth\Gatekeeper\Visitor;

/**
 * The Access Control List (ACL) interface.
 *
 * @author Christian Neff <christian.neff@gmail.com>
 */
interface ACLInterface
{
    /**
     * Checks if access is allowed for the given visitor.
     *
     * @param Visitor $visitor The visitor object
     *
     * @return bool
     */
    public function isAllowed(Visitor $visitor);

    /**
     * Checks if access is denied for the given visitor.
     *
     * @param Visitor $visitor The visitor object
     *
     * @return bool
     */
    public function isDenied(Visitor $visitor);
}
