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
use FlameCore\Gatekeeper\Visitor;
use FlameCore\Gatekeeper\UserAgent;

/**
 * Analyzes user agents claiming to be a Mozilla browser.
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class MozillaBrowser extends AbstractBrowser
{
    /**
     * {@inheritdoc}
     */
    public function scan(Visitor $visitor)
    {
        $uastring = $visitor->getUserAgent()->getUserAgentString();

        if ($uastring->contains('Google Desktop', true) || $uastring->contains('PLAYSTATION 3', true)) {
            return CheckInterface::RESULT_OKAY;
        }

        return parent::scan($visitor);
    }

    /**
     * {@inheritdoc}
     */
    public function is(UserAgent $ua)
    {
        return $ua->getUserAgentString()->startsWith('Mozilla');
    }
}
