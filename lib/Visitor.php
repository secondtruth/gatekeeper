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

use FlameCore\Webtools\UserAgent;
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
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $data;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var \FlameCore\Webtools\UserAgent
     */
    protected $userAgent;

    /**
     * @var bool
     */
    protected $isBrowser;

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
        $this->data = $request->request;
        $this->scheme = $request->getScheme();
        $this->protocol = $request->server->get('SERVER_PROTOCOL');

        $userAgent = $request->headers->get('user-agent');
        $this->userAgent = new UserAgent($userAgent);
        $this->isBrowser = !$this->userAgent->isBot();
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
     * Gets the request data.
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    public function getRequestData()
    {
        return $this->data;
    }

    /**
     * Gets the request scheme.
     *
     * @return string
     */
    public function getRequestScheme()
    {
        return $this->scheme;
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
     * Gets the user agent information.
     *
     * @return \FlameCore\Webtools\UserAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Request comes from a browser?
     *
     * @return bool
     */
    public function isBrowser()
    {
        return $this->isBrowser;
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
            'data'       => $this->data->all(),
            'protocol'   => $this->protocol,
            'scheme'     => $this->scheme,
            'user_agent' => $this->userAgent->getUserAgentString(),
            'is_browser' => $this->isBrowser()
        );
    }
}
