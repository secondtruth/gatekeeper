<?php
/**
 * FlameCore Gatekeeper
 * Copyright (C) 2015 IceFlame.net
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * @package  FlameCore\Gatekeeper
 * @version  0.1-dev
 * @link     http://www.flamecore.org
 * @license  http://opensource.org/licenses/ISC ISC License
 */

namespace FlameCore\Gatekeeper\Check\UserAgent\Bot;

use FlameCore\Gatekeeper\UserAgent;

/**
 * Analyzes user agents claiming to be Googlebot.
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class GoogleBot extends AbstractBot
{
    protected $knownIps = [
        '66.249.64.0/19', '64.233.160.0/19', '72.14.192.0/18', '203.208.32.0/19', '74.125.0.0/16', '216.239.32.0/19',
        '209.85.128.0/17'
    ];

    public function is(UserAgent $ua)
    {
        return $ua->getBrowserName() == 'googlebot';
    }
}
