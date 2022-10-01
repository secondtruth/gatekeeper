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

/**
 * Interface CheckInterface
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface CheckInterface
{
    const RESULT_OKAY = 0;
    const RESULT_UNSURE = 1;
    const RESULT_BLOCK = 2;

    /**
     * Checks the visitor.
     *
     * @param \Secondtruth\Gatekeeper\Visitor $visitor The visitor information
     *
     * @return int|string Returns the check result.
     */
    public function checkVisitor(Visitor $visitor);

    /**
     * Is this check responsible for the given Visitor?
     *
     * @param \Secondtruth\Gatekeeper\Visitor $visitor The visitor information
     *
     * @return bool
     */
    public function isResponsibleFor(Visitor $visitor);
}
