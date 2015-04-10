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

namespace FlameCore\Gatekeeper;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class Visitor
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Visitor
{
    private $ip;

    private $headers;

    private $method;

    private $uri;

    private $protocol;

    private $userAgent;

    private $browser;

    public function __construct(Request $request)
    {
        $this->ip = $request->getClientIp();
        $this->headers = $request->headers;
        $this->method = $request->getRealMethod();
        $this->uri = $request->getRequestUri();
        $this->protocol = $request->getScheme();
        $this->userAgent = $request->headers->get('user-agent');
        $this->browser = $this->determineBrowser($this->userAgent);
    }

    public function getIP()
    {
        return $this->ip;
    }

    public function getRequestHeaders()
    {
        return $this->headers;
    }

    public function getRequestMethod()
    {
        return $this->method;
    }

    public function getRequestURI()
    {
        return $this->uri;
    }

    public function getServerProtocol()
    {
        return $this->protocol;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function isBrowser()
    {
        return $this->browser;
    }

    public function toArray()
    {
        return array(
            'ip'         => $this->ip,
            'headers'    => $this->headers->all(),
            'method'     => $this->method,
            'uri'        => $this->uri,
            'protocol'   => $this->protocol,
            'user_agent' => $this->userAgent,
            'is_browser' => $this->isBrowser()
        );
    }

    private function determineBrowser($userAgent)
    {
        if (stripos($userAgent, "; MSIE") !== false) {
            return 'ie';
        } elseif (stripos($userAgent, "Konqueror") !== false) {
            return 'konqueror';
        } elseif (stripos($userAgent, "Opera") !== false) {
            return 'opera';
        } elseif (stripos($userAgent, "Safari") !== false) {
            return 'safari';
        } elseif (stripos($userAgent, "Mozilla") !== false && stripos($userAgent, "Mozilla") == 0) {
            return 'mozilla';
        } elseif (stripos($userAgent, "Lynx") !== false) {
            return 'lynx';
        } elseif (stripos($userAgent, "MovableType") !== false) {
            return 'movabletype';
        } else {
            return false;
        }
    }
}