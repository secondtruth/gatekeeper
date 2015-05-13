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

namespace FlameCore\Gatekeeper\Result;

/**
 * Class Explainer
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Explainer
{
    protected static $responses = [
        '136673cd' => [
            'response' => 403,
            'explanation' => 'Your Internet Protocol address is listed on a blacklist of addresses involved in malicious or illegal activity. See the listing below for more details on specific blacklists and removal procedures.',
            'logtext' => 'IP address found on external blacklist'
        ],
        '17566707' => [
            'response' => 403,
            'explanation' => 'An invalid request was received from your browser. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'Required header "Accept" missing'
        ],
        '17f4e8c8' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'User-Agent was found on blacklist'
        ],
        '21f11d3f' => [
            'response' => 403,
            'explanation' => 'An invalid request was received. You claimed to be a mobile Web device, but you do not actually appear to be a mobile Web device.',
            'logtext' => 'User-Agent claimed to be AvantGo, claim appears false'
        ],
        '2b021b1f' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Before trying again, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'IP address found on http:BL blacklist'
        ],
        '2b90f772' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. If you are using the Opera browser, then Opera must appear in your user agent.',
            'logtext' => 'Connection: TE present, not supported by MSIE'
        ],
        '35ea7ffa' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Check your browser\'s language and locale settings.',
            'logtext' => 'Invalid language specified'
        ],
        '408d7e72' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Before trying again, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'POST comes too quickly after GET'
        ],
        '41feed15' => [
            'response' => 400,
            'explanation' => 'An invalid request was received. This may be caused by a malfunctioning proxy server. Bypass the proxy server and connect directly, or contact your proxy server administrator.',
            'logtext' => 'Header "Pragma" without "Cache-Control" prohibited for HTTP/1.1 requests'
        ],
        '45b35e30' => [
            'response' => 400,
            'explanation' => 'An invalid request was received from your browser. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'Header "Referer" is corrupt'
        ],
        '57796684' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Before trying again, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'Prohibited header "X-Aaaaaaaaaa" or "X-Aaaaaaaaaaaa" present'
        ],
        '582ec5e4' => [
            'response' => 400,
            'explanation' => 'An invalid request was received. If you are using a proxy server, bypass the proxy server or contact your proxy server administrator. This may also be caused by a bug in the Opera web browser.',
            'logtext' => 'Header "TE" present but TE not specified in "Connection" header'
        ],
        '69920ee5' => [
            'response' => 400,
            'explanation' => 'An invalid request was received from your browser. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'Header "Referer" present but blank'
        ],
        '6c502ff1' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'Bot not fully compliant with RFC 2965'
        ],
        '70e45496' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'User agent claimed to be CloudFlare, claim appears false'
        ],
        '71436a15' => [
            'response' => 403,
            'explanation' => 'An invalid request was received. You claimed to be a major search engine, but you do not appear to actually be a major search engine.',
            'logtext' => 'User-Agent claimed to be Yahoo, claim appears to be false'
        ],
        '799165c2' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'Rotating user-agents detected'
        ],
        '7a06532b' => [
            'response' => 400,
            'explanation' => 'An invalid request was received from your browser. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'Required header "Accept-Encoding" missing'
        ],
        '7ad04a8a' => [
            'response' => 400,
            'explanation' => 'The automated program you are using is not permitted to access this server. Please use a different program or a standard Web browser.',
            'logtext' => 'Prohibited header "Range" present'
        ],
        '7d12528e' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'Prohibited header "Range" or "Content-Range" in POST request'
        ],
        '939a6fbb' => [
            'response' => 403,
            'explanation' => 'The proxy server you are using is not permitted to access this server. Please bypass the proxy server, or contact your proxy server administrator.',
            'logtext' => 'Banned proxy server in use'
        ],
        '96c0bd29' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'URL pattern found on blacklist'
        ],
        '9c9e4979' => [
            'response' => 403,
            'explanation' => 'The proxy server you are using is not permitted to access this server. Please bypass the proxy server, or contact your proxy server administrator.',
            'logtext' => 'Prohibited header "via" (lowercase) present'
        ],
        'a0105122' => [
            'response' => 417,
            'explanation' => 'The content type expectation of your browser failed. Please retry your request.',
            'logtext' => 'Header "Expect" prohibited; resend without Expect'
        ],
        'a1084bad' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'User-Agent claimed to be MSIE, with invalid Windows version'
        ],
        'a52f0448' => [
            'response' => 400,
            'explanation' => 'An invalid request was received.  This may be caused by a malfunctioning proxy server or browser privacy software. If you are using a proxy server, bypass the proxy server or contact your proxy server administrator.',
            'logtext' => 'Header "Connection" contains invalid values'
        ],
        'b0924802' => [
            'response' => 400,
            'explanation' => 'An invalid request was received. This may be caused by malicious software on your computer.',
            'logtext' => 'Incorrect form of HTTP/1.0 Keep-Alive'
        ],
        'b40c8ddc' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Before trying again, close your browser, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'POST more than two days after GET'
        ],
        'b7830251' => [
            'response' => 400,
            'explanation' => 'Your proxy server sent an invalid request. Please contact the proxy server administrator to have this problem fixed.',
            'logtext' => 'Prohibited header "Proxy-Connection" present'
        ],
        'b9cc1d86' => [
            'response' => 403,
            'explanation' => 'The proxy server you are using is not permitted to access this server. Please bypass the proxy server, or contact your proxy server administrator.',
            'logtext' => 'Prohibited header "X-Aaaaaaaaaa" or "X-Aaaaaaaaaaaa" present'
        ],
        'c1fa729b' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Before trying again, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'Use of rotating proxy servers detected'
        ],
        'cd361abb' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Data may not be posted from offsite forms.',
            'logtext' => 'Referer did not point to a form on this site'
        ],
        'd60b87c7' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Before trying again, please remove any viruses or spyware from your computer.',
            'logtext' => 'Trackback received via proxy server'
        ],
        'dfd9b1ad' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'Request contained a malicious JavaScript or SQL injection attack'
        ],
        'e3990b47' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Before trying again, please remove any viruses or spyware from your computer.',
            'logtext' => 'Obviously fake trackback received'
        ],
        'e4de0453' => [
            'response' => 403,
            'explanation' => 'An invalid request was received. You claimed to be a major search engine, but you do not appear to actually be a major search engine.',
            'logtext' => 'User-Agent claimed to be msnbot, claim appears to be false'
        ],
        'e87553e1' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'I know you and I don\'t like you, dirty spammer'
        ],
        'f0dcb3fd' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. Before trying again, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'Web browser attempted to send a trackback'
        ],
        'f1182195' => [
            'response' => 403,
            'explanation' => 'An invalid request was received. You claimed to be a major search engine, but you do not appear to actually be a major search engine.',
            'logtext' => 'User-Agent claimed to be Googlebot, claim appears to be false'
        ],
        'f9f2b8b9' => [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'A User-Agent is required but none was provided'
        ],
    ];

    /**
     * @param \FlameCore\Gatekeeper\Result\PositiveResult $result
     * @return array|bool
     */
    public function explain(PositiveResult $result)
    {
        $code = $result->getCode();

        if ($code !== null && isset(self::$responses[$code])) {
            return self::$responses[$code];
        }

        return false;
    }
}
