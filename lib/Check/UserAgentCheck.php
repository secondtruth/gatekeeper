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

use FlameCore\Gatekeeper\Utils;
use FlameCore\Gatekeeper\Visitor;

/**
 * Class UserAgentCheck
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UserAgentCheck implements CheckInterface
{
    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        $browser = $visitor->getUserAgent()->getBrowserName();
        $uastring = $visitor->getUserAgent()->getUserAgentString();

        if ($visitor->getUserAgent()->isBot()) {
            if ($browser == 'msnbot') {
                return $this->checkMsnBot($visitor);
            } elseif ($browser == 'googlebot') {
                return $this->checkGoogleBot($visitor);
            } elseif ($browser == 'yahoobot') {
                return $this->checkYahooBot($visitor);
            } elseif ($browser == 'baidubot') {
                return $this->checkBaiduBot($visitor);
            }
        } else {
            if ($browser == 'msie') {
                if (stripos($uastring, 'Opera') !== false) {
                    return $this->checkOpera($visitor);
                } else {
                    return $this->checkMsie($visitor);
                }
            } elseif ($browser == 'konqueror') {
                return $this->checkKonqueror($visitor);
            } elseif ($browser == 'opera') {
                return $this->checkOpera($visitor);
            } elseif ($browser == 'safari') {
                return $this->checkSafari($visitor);
            } elseif ($browser == 'lynx') {
                return $this->checkLynx($visitor);
            } elseif (stripos($uastring, "Mozilla") === 0) {
                return $this->checkMozilla($visitor);
            }
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be MSIE.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkMsie(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();
        $uastring = $visitor->getUserAgent()->getUserAgentString();

        if (!$headers->has('Accept')) {
            return '17566707';
        }

        // MSIE does NOT send "Windows ME" or "Windows XP" in the user agent
        if (strpos($uastring, 'Windows ME') !== false || strpos($uastring, 'Windows XP') !== false || strpos($uastring, 'Windows 2000') !== false || strpos($uastring, 'Win32') !== false) {
            return 'a1084bad';
        }

        // MSIE does NOT send 'Connection: TE' but Akamai does. Bypass this test when Akamai detected.
        // The latest version of IE for Windows CE also uses 'Connection: TE'
        if (!$headers->has('Akamai-Origin-Hop') && strpos($uastring, 'IEMobile') === false && preg_match('/\bTE\b/i', $headers->get('Connection'))) {
            return '2b90f772';
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be Konqueror.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkKonqueror(Visitor $visitor)
    {
        if (!$visitor->getRequestHeaders()->has('Accept')) {
            return '17566707';
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be Lynx.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkLynx(Visitor $visitor)
    {
        if (!$visitor->getRequestHeaders()->has('Accept')) {
            return '17566707';
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be Mozilla.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkMozilla(Visitor $visitor)
    {
        $uastring = $visitor->getUserAgent()->getUserAgentString();

        if (strpos($uastring, 'Google Desktop') || strpos($uastring, 'PLAYSTATION 3')) {
            return CheckInterface::RESULT_UNSURE;
        }

        if (!$visitor->getRequestHeaders()->has('Accept')) {
            return '17566707';
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be Opera.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkOpera(Visitor $visitor)
    {
        if (!$visitor->getRequestHeaders()->has('Accept')) {
            return '17566707';
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be Safari.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkSafari(Visitor $visitor)
    {
        if (!$visitor->getRequestHeaders()->has('Accept')) {
            return '17566707';
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be Googlebot.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkGoogleBot(Visitor $visitor)
    {
        if (Utils::isIPv6($visitor->getIP())) {
            return CheckInterface::RESULT_UNSURE;
        }

        if (!Utils::matchCIDR($visitor->getIP(), ['66.249.64.0/19', '64.233.160.0/19', '72.14.192.0/18', '203.208.32.0/19', '74.125.0.0/16', '216.239.32.0/19', '209.85.128.0/17'])) {
            return CheckInterface::RESULT_UNSURE;
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be msnbot.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkMsnBot(Visitor $visitor)
    {
        if (Utils::isIPv6($visitor->getIP())) {
            return CheckInterface::RESULT_UNSURE;
        }

        if (!Utils::matchCIDR($visitor->getIP(), ['207.46.0.0/16', '65.52.0.0/14', '207.68.128.0/18', '207.68.192.0/20', '64.4.0.0/18', '157.54.0.0/15', '157.60.0.0/16', '157.56.0.0/14', '131.253.21.0/24', '131.253.22.0/23', '131.253.24.0/21', '131.253.32.0/20'])) {
            return CheckInterface::RESULT_UNSURE;
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be Yahoo! Slurp.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkYahooBot(Visitor $visitor)
    {
        if (Utils::isIPv6($visitor->getIP())) {
            return CheckInterface::RESULT_UNSURE;
        }

        if (!Utils::matchCIDR($visitor->getIP(), ['202.160.176.0/20', '67.195.0.0/16', '203.209.252.0/24', '72.30.0.0/16', '98.136.0.0/14', '74.6.0.0/16'])) {
            return CheckInterface::RESULT_UNSURE;
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes user agents claiming to be Baidu Spider.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor information
     * @return bool
     */
    protected function checkBaiduBot(Visitor $visitor)
    {
        if (Utils::isIPv6($visitor->getIP())) {
            return CheckInterface::RESULT_UNSURE;
        }

        if (!Utils::matchCIDR($visitor->getIP(), ['119.63.192.0/21', '123.125.71.0/24', '180.76.0.0/16', '220.181.0.0/16'])) {
            return CheckInterface::RESULT_UNSURE;
        }

        return CheckInterface::RESULT_OKAY;
    }
}
