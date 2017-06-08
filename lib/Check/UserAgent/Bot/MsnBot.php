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

use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Exceptions\StopScreeningException;
use FlameCore\Gatekeeper\Visitor;
use FlameCore\Gatekeeper\UserAgent;

/**
 * Analyzes user agents claiming to be msnbot.
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class MsnBot extends AbstractBot
{
    protected $knownIps = [
        '207.46.0.0/16', '65.52.0.0/14', '207.68.128.0/18', '207.68.192.0/20', '64.4.0.0/18', '157.54.0.0/15',
        '157.60.0.0/16', '157.56.0.0/14', '131.253.21.0/24', '131.253.22.0/23', '131.253.24.0/21', '131.253.32.0/20'
    ];

    public function is(UserAgent $ua)
    {
        return $ua->getBrowserName() == 'msnbot';
    }
}
