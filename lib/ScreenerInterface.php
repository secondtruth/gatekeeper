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

namespace FlameCore\Gatekeeper;

/**
 * Interface ScreenerInterface
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface ScreenerInterface
{
    /**
     * Screens the visitor.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     *
     * @return \FlameCore\Gatekeeper\Result\ResultInterface Returns the screening result.
     */
    public function screenVisitor(Visitor $visitor);
}
