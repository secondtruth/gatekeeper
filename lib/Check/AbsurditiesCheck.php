<?php
/**
 * Gatekeeper Library
 * Copyright (C) 2015 IceFlame.net
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE
 * FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER
 * IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING
 * OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 *
 * @package  FlameCore\Gatekeeper
 * @version  0.1-dev
 * @link     http://www.flamecore.org
 * @license  ISC License <http://opensource.org/licenses/ISC>
 */

namespace FlameCore\Gatekeeper\Check;

use FlameCore\Gatekeeper\Visitor;

/**
 * Class AbsurditiesCheck
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class AbsurditiesCheck implements CheckInterface
{
    /**
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        $defaults = array(
            'strict' => false,
            'offsite_forms' => false
        );

        $this->settings = array_replace($defaults, $settings);
    }

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        if ($result = $this->checkCookies($visitor)) {
            return $result;
        }

        if ($result = $this->checkUri($visitor)) {
            return $result;
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Enforces RFC 2965 sec 3.3.5 and 9.1.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor
     * @return bool|string
     */
    protected function checkCookies(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();
        $uastring = $visitor->getUserAgent()->getUserAgentString();

        // The only valid value for $Version is 1 and when present, the user agent MUST send a Cookie2 header.
        // NOTE: RFC 2965 is obsoleted by RFC 6265. Current software MUST NOT use Cookie2 or $Version in Cookie.
        // First-gen Amazon Kindle is broken.
        if (strpos($headers->get('Cookie'), '$Version=0') !== false && !$headers->has('Cookie2') && strpos($uastring, 'Kindle/') === false) {
            return '6c502ff1';
        }

        return false;
    }

    /**
     * Analyzes the request URI.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor
     * @return bool|string
     */
    protected function checkUri(Visitor $visitor)
    {
        // A pretty nasty SQL injection attack on IIS servers
        if (strpos($visitor->getRequestURI(), ';DECLARE%20@') !== false) {
            return 'dfd9b1ad';
        }

        return false;
    }
}
