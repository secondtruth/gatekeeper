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
 * This check analyzes the request headers.
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class HeadersCheck extends AbstractConfigurableCheck
{
    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();
        $uastring = $visitor->getUserAgent()->getUserAgentString();

        if ($visitor->getRequestMethod() != 'POST' && empty($uastring)) {
            return 'f9f2b8b9';
        }

        // 'Range:' field exists and begins with 0. Real user-agents do not start ranges at 0. (Also blocks whois.sc bot. No big loss.)
        // Exceptions: MT (not fixable); LJ (refuses to fix; may be blocked again in the future); Facebook
        if ($this->settings['strict'] && $headers->has('Range') && strpos($headers->get('Range'), '=0-') !== false) {
            if (!$uastring->startsWithAny(['MovableType', 'URI::Fetch', 'php-openid/', 'facebookexternalhit'], true)) {
                return '7ad04a8a';
            }
        }

        // Content-Range is a response header, not a request header
        if ($headers->has('Content-Range')) {
            return '7d12528e';
        }

        // Lowercase via is used by open proxies/referrer spammers
        // Exceptions: Clearswift uses lowercase via (refuses to fix; may be blocked again in the future)
        // TODO: Support this feature with HttpFoundation / HttpMessage?
//        if ($this->settings['strict'] && $headers->has('via') && strpos($headers->get('via'), 'Clearswift') === FALSE && strpos($ua, 'CoralWebPrx') === FALSE) {
//            return "9c9e4979";
//        }

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

        return CheckInterface::RESULT_OKAY;
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
     * {@inheritdoc}
     */
    protected function getSettingsDefaults()
    {
        return array(
            'strict' => false
        );
    }
}
