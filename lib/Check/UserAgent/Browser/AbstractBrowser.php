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

namespace FlameCore\Gatekeeper\Check\UserAgent\Browser;

use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Check\UserAgent\BrowserInterface;
use FlameCore\Gatekeeper\Check\UserAgent\ScannableUserAgentInterface;
use FlameCore\Gatekeeper\Visitor;

/**
 * Class AbstractBrowser
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractBrowser implements BrowserInterface, ScannableUserAgentInterface
{
    /**
     * {@inheritdoc}
     */
    public function scan(Visitor $visitor)
    {
        if (!$visitor->getRequestHeaders()->has('Accept')) {
            return '17566707';
        }

        return CheckInterface::RESULT_OKAY;
    }
}
