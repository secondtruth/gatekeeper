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
 * Analyzes user agents claiming to be Yahoo! Slurp.
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class YahooBot extends AbstractBot
{
    protected $knownIps = [
        '202.160.176.0/20', '67.195.0.0/16', '203.209.252.0/24', '72.30.0.0/16', '98.136.0.0/14', '74.6.0.0/16'
    ];

    public function is(UserAgent $ua)
    {
        return $ua->getBrowserName() == 'yahoobot';
    }
}
