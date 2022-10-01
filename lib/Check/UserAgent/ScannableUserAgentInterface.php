<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Check\UserAgent;

use Secondtruth\Gatekeeper\Visitor;

/**
 * Interface ScannableUserAgentInterface.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface ScannableUserAgentInterface extends UserAgentInterface
{
    /**
     * @param \Secondtruth\Gatekeeper\Visitor $visitor The visitor information
     *
     * @return int|string
     *
     * @throws \Secondtruth\Gatekeeper\Exceptions\StopScreeningException
     */
    public function scan(Visitor $visitor);
}
