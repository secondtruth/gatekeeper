<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Check\UserAgent\Bot;

use Secondtruth\Gatekeeper\UserAgent;

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
        '157.60.0.0/16', '157.56.0.0/14', '131.253.21.0/24', '131.253.22.0/23', '131.253.24.0/21', '131.253.32.0/20',
        '40.76.0.0/14'
    ];

    public function is(UserAgent $ua)
    {
        return $ua->getBrowserName() == 'bingbot' || $ua->getBrowserName() == 'msnbot';
    }
}
