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
    /**
     * @var string
     */
    protected $ip;

    /**
     * @var \Symfony\Component\HttpFoundation\HeaderBag
     */
    protected $headers;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var bool|string
     */
    protected $browser;

    /**
     * @var bool|string
     */
    protected $searchEngine;

    /**
     * Creates a Visitor object.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(Request $request)
    {
        $this->ip = $request->getClientIp();
        $this->headers = $request->headers;
        $this->method = $request->getRealMethod();
        $this->uri = $request->getRequestUri();
        $this->protocol = $request->getScheme();
        $this->userAgent = $request->headers->get('user-agent');
        $this->browser = $this->determineBrowser($this->userAgent);
        $this->searchEngine = $this->determineSearchEngine($this->userAgent);
    }

    /**
     * Gets the client IP address.
     *
     * @return string
     */
    public function getIP()
    {
        return $this->ip;
    }

    /**
     * Gets the request headers.
     *
     * @return \Symfony\Component\HttpFoundation\HeaderBag
     */
    public function getRequestHeaders()
    {
        return $this->headers;
    }

    /**
     * Gets the request method.
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->method;
    }

    /**
     * Gets the request URI.
     *
     * @return string
     */
    public function getRequestURI()
    {
        return $this->uri;
    }

    /**
     * Gets the server protocol.
     *
     * @return string
     */
    public function getServerProtocol()
    {
        return $this->protocol;
    }

    /**
     * Gets the user agent string.
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Returns whether the request comes from a browser and if so, which browser it is.
     *
     * @return bool|string
     */
    public function isBrowser()
    {
        return $this->browser;
    }

    /**
     * Returns whether the request comes from a search engine bot and if so, which bot it is.
     *
     * @return bool|string
     */
    public function isSearchEngine()
    {
        return $this->searchEngine;
    }

    /**
     * Export data to array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'ip'         => $this->ip,
            'headers'    => $this->headers->all(),
            'method'     => $this->method,
            'uri'        => $this->uri,
            'protocol'   => $this->protocol,
            'user_agent' => $this->userAgent,
            'is_browser' => $this->isBrowser(),
            'is_se'      => $this->isSearchEngine()
        );
    }

    /**
     * @param string $userAgent
     * @return string|bool
     */
    protected function determineBrowser($userAgent)
    {
        if (stripos($userAgent, '; MSIE') !== false) {
            return 'ie';
        } elseif (stripos($userAgent, 'Konqueror') !== false) {
            return 'konqueror';
        } elseif (stripos($userAgent, 'Opera') !== false) {
            return 'opera';
        } elseif (stripos($userAgent, 'Safari') !== false) {
            return 'safari';
        } elseif (stripos($userAgent, 'Mozilla') !== false && stripos($userAgent, 'Mozilla') == 0) {
            return 'mozilla';
        } elseif (stripos($userAgent, 'Lynx') !== false) {
            return 'lynx';
        } elseif (stripos($userAgent, 'MovableType') !== false) {
            return 'movabletype';
        } else {
            return false;
        }
    }

    /**
     * @param string $userAgent
     * @return bool|string
     */
    protected function determineSearchEngine($userAgent)
    {
        if (stripos($userAgent, 'bingbot') !== false || stripos($userAgent, 'msnbot') !== false || stripos($userAgent, 'MS Search') !== false) {
            return 'bing';
        } elseif (stripos($userAgent, 'Googlebot') !== false || stripos($userAgent, 'Mediapartners-Google') !== false || stripos($userAgent, 'Google Web Preview') !== false) {
            return 'google';
        } elseif (stripos($userAgent, 'Yahoo! Slurp') !== false || stripos($userAgent, 'Yahoo! SearchMonkey') !== false) {
            return 'yahoo';
        } elseif (stripos($userAgent, 'Baidu') !== false) {
            return 'baidu';
        } else {
            return false;
        }
    }
}
