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
     * The settings
     *
     * @var array
     */
    protected $settings = array();

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
        // Check for common stuff
        if ($result = $this->checkProtocol($visitor)) {
            return $result;
        }

        if ($result = $this->checkCookies($visitor)) {
            return $result;
        }

        if ($result = $this->checkUri($visitor)) {
            return $result;
        }

        if ($result = $this->checkHeaders($visitor)) {
            return $result;
        }

        // More intensive screening applies to POST requests
        if ($visitor->getRequestMethod() == 'POST') {
            if ($result = $this->checkPostRequest($visitor)) {
                return $result;
            }
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Enforces adherence to protocol version claimed by user-agent.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor
     * @return bool|string
     */
    protected function checkProtocol(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();

        // We should never see 'Expect:' for HTTP/1.0 requests
        if ($headers->has('Expect') && stripos($headers->get('Expect'), '100-continue') !== false && !strcmp($visitor->getServerProtocol(), 'HTTP/1.0')) {
            return 'a0105122';
        }

        // Is it claiming to be HTTP/1.1? Then it shouldn't do HTTP/1.0 things.
        // Blocks some common corporate proxy servers in strict mode.
        if ($this->settings['strict'] && !strcmp($visitor->getServerProtocol(), 'HTTP/1.1')) {
            if ($headers->has('Pragma') && strpos($headers->get('Pragma'), 'no-cache') !== false && !$headers->has('Cache-Control')) {
                return '41feed15';
            }
        }

        return false;
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

        // Case-insensitive checks
        foreach ($this->getBadUriParts() as $badUriPart) {
            if (stripos($visitor->getRequestURI(), $badUriPart) !== false) {
                return '96c0bd29';
            }
        }

        return false;
    }

    /**
     * Analyzes the request headers.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor
     * @return bool|string
     */
    protected function checkHeaders(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();
        $uastring = $visitor->getUserAgent()->getUserAgentString();

        if ($visitor->getRequestMethod() != 'POST' && empty($uastring)) {
            return 'f9f2b8b9';
        }

        // 'Range:' field exists and begins with 0. Real user-agents do not start ranges at 0.
        // NOTE: This also blocks the whois.sc bot. No big loss.
        // Exceptions: MT (not fixable); LJ (refuses to fix; may be blocked again in the future); Facebook
        if ($this->settings['strict'] && $headers->has('Range') && strpos($headers->get('Range'), '=0-') !== false) {
            if (strncmp($uastring, 'MovableType', 11) && strncmp($uastring, 'URI::Fetch', 10) && strncmp($uastring, 'php-openid/', 11) && strncmp($uastring, 'facebookexternalhit', 19)) {
                return '7ad04a8a';
            }
        }

        // Content-Range is a response header, not a request header
        if ($headers->has('Content-Range')) {
            return '7d12528e';
        }

        // pinappleproxy is used by referrer spammers
        if ($headers->has('Via')) {
            if (stripos($headers->get('Via'), 'pinappleproxy') !== false || stripos($headers->get('Via'), 'PCNETSERVER') !== false || stripos($headers->get('Via'), 'Invisiware') !== false) {
                return '939a6fbb';
            }
        }

        // 'TE:' if present must have 'Connection: TE' (RFC 2616 14.39)
        // Blocks Microsoft ISA Server 2004 in strict mode. Contact Microsoft to obtain a hotfix.
        if ($this->settings['strict'] && $headers->has('Te')) {
            if (!preg_match('/\bTE\b/', $headers->get('Connection'))) {
                return '582ec5e4';
            }
        }

        // Analyze the Connection header if it exists
        if ($headers->has('Connection') && $result = $this->checkConnectionHeader($headers->get('Connection'))) {
            return $result;
        }

        // Headers which are not seen from normal user agents; only malicious bots
        if ($headers->has('X-Aaaaaaaaaaaa') || $headers->has('X-Aaaaaaaaaa')) {
            return 'b9cc1d86';
        }

        // 'Proxy-Connection' does not exist and should never be seen in the wild.
        // - http://lists.w3.org/Archives/Public/ietf-http-wg-old/1999JanApr/0032.html
        // - http://lists.w3.org/Archives/Public/ietf-http-wg-old/1999JanApr/0040.html
        if ($this->settings['strict'] && $headers->has('Proxy-Connection')) {
            return 'b7830251';
        }

        // Analyze the Referer header if it exists
        if ($headers->has('Referer') && $result = $this->checkRefererHeader($headers->get('Referer'))) {
            return $result;
        }

        return false;
    }

    /**
     * Analyzes the Connection header.
     *
     * @param string $value The header value
     * @return bool|string
     */
    protected function checkConnectionHeader($value)
    {
        // 'Connection: keep-alive' and 'close' are mutually exclusive
        if (preg_match('/\bKeep-Alive\b/i', $value) && preg_match('/\bClose\b/i', $value)) {
            return 'a52f0448';
        }

        // Close shouldn't appear twice
        if (preg_match('/\bclose,\s?close\b/i', $value)) {
            return 'a52f0448';
        }

        // Keey-Alive shouldn't appear twice either
        if (preg_match('/\bkeep-alive,\s?keep-alive\b/i', $value)) {
            return 'a52f0448';
        }

        // Keep-Alive format in RFC 2068; some bots mangle these headers
        if (stripos($value, 'Keep-Alive: ') !== false) {
            return 'b0924802';
        }

        return false;
    }

    /**
     * Analyzes the Referer header.
     *
     * @param string $value The header value
     * @return bool|string
     */
    protected function checkRefererHeader($value)
    {
        // Referer must not be blank
        if ($value === '') {
            return '69920ee5';
        }

        // While a relative URL is technically valid in Referer, all known legitimate user-agents send an absolute URL.
        if (strpos($value, ':') === false) {
            return '45b35e30';
        }

        return false;
    }

    /**
     * Analyzes POST requests.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor
     * @return bool|string
     */
    protected function checkPostRequest(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();
        $data = $visitor->getRequestData();

        // MovableType needs specialized screening
        if (stripos($visitor->getUserAgent()->getUserAgentString(), 'MovableType') !== false) {
            if (strcmp($headers->get('Range'), 'bytes=0-99999')) {
                return '7d12528e';
            }
        }

        // Trackbacks need special screening
        if ($data->has('title') && $data->has('url') && $data->has('blog_name')) {
            return $this->checkTrackback($visitor);
        }

        // Catch a few completely broken spambots
        foreach ($data->all() as $key => $value) {
            if (strpos($key, '	document.write') !== false) {
                return 'dfd9b1ad';
            }
        }

        // If 'Referer' exists, it should refer to a page on our site
        if (!$this->settings['offsite_forms'] && $headers->has('Referer')) {
            $url = parse_url($headers->get('Referer'));
            $url['host'] = preg_replace('|^www\.|', '', $url['host']);
            $host = preg_replace('|^www\.|', '', $headers->get('Host'));
            if (strcasecmp($host, $url['host'])) {
                return 'cd361abb';
            }
        }

        return false;
    }

    /**
     * Analyzes trackbacks.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor
     * @return bool|string
     */
    protected function checkTrackback(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();

        // Web browsers don't send trackbacks
        if ($visitor->isBrowser()) {
            return 'f0dcb3fd';
        }

        // Proxy servers don't send trackbacks either
        if ($headers->has('Via') || $headers->has('Max-Forwards') || $headers->has('X-Forwarded-For') || $headers->has('Client-Ip')) {
            return 'd60b87c7';
        }

        // Fake WordPress trackbacks
        // Real ones do not contain 'Accept:' and have a charset defined
        // Real WP trackbacks may contain 'Accept:' and have a charset defined
        if (strpos($visitor->getUserAgent()->getUserAgentString(), 'WordPress/') !== false) {
            if (strpos($headers->get('Accept'), 'charset=') === false) {
                return 'e3990b47';
            }
        }

        return false;
    }

    /**
     * Gets list of request URI parts which determine a bad bot.
     *
     * @return string[]
     */
    protected function getBadUriParts()
    {
        return array(
            '0x31303235343830303536', // Havij
            '../', // path traversal
            '..\\', // path traversal
            '%60information_schema%60', // SQL injection probe
            '+%2F*%21', // SQL injection probe
            '%27--', // SQL injection
            '%27 --', // SQL injection
            '%27%23', // SQL injection
            '%27 %23', // SQL injection
            'benchmark%28', // SQL injection probe
            'insert+into+', // SQL injection
            'r3dm0v3', // SQL injection probe
            'select+1+from', // SQL injection probe
            'union+all+select', // SQL injection probe
            'union+select', // SQL injection probe
            'waitfor+delay+', // SQL injection probe
            'w00tw00t', // vulnerability scanner
        );
    }
}
