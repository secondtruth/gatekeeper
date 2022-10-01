<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Check\UserAgent\Browser;

use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Check\UserAgent\BrowserInterface;
use Secondtruth\Gatekeeper\Check\UserAgent\ScannableUserAgentInterface;
use Secondtruth\Gatekeeper\Visitor;

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
