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

namespace FlameCore\Gatekeeper\Screener;

use FlameCore\Gatekeeper\Screener;
use FlameCore\Gatekeeper\Check\AbsurditiesCheck;
use FlameCore\Gatekeeper\Check\SpambotsBlacklistCheck;
use FlameCore\Gatekeeper\Check\UserAgentCheck;

/**
 * Check for known spam bots and block them.
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class BadBehaviorScreener extends CustomScreener
{
    /**
     * {@inheritdoc}
     */
    protected function setup()
    {
        $this->addCheck(new SpambotsBlacklistCheck());
        $this->addCheck(new AbsurditiesCheck());
        $this->addCheck(new UserAgentCheck());
    }
}
