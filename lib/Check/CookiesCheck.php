<?php
/**
 * FlameCore Gatekeeper
 * Copyright (C) 2017 IceFlame.net
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

namespace FlameCore\Gatekeeper\Check;

use FlameCore\Gatekeeper\Visitor;

/**
 * Enforces RFC 2965 sec 3.3.5 and 9.1.
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class CookiesCheck extends AbstractCheck
{
    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();
        $uastring = $visitor->getUserAgent()->getUserAgentString();

        // The only valid value for $Version is 1 and when present, the user agent MUST send a Cookie2 header.
        // NOTE: RFC 2965 is obsoleted by RFC 6265. Current software MUST NOT use Cookie2 or $Version in Cookie.
        // First-gen Amazon Kindle is broken.
        if (strpos($headers->get('Cookie'), '$Version=0') !== false && !$headers->has('Cookie2') && strpos($uastring, 'Kindle/') === false) {
            return '6c502ff1';
        }

        return CheckInterface::RESULT_OKAY;
    }
}
