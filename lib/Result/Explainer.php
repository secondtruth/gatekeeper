<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Result;

use Secondtruth\Gatekeeper\Screener;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Explainer
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Explainer
{
    /**
     * List of responses for known result codes
     *
     * @var array
     */
    protected static $responses = [
        '136673cd' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'Your IP address is listed on a blacklist of addresses involved in malicious or illegal activity.',
            'logtext' => 'IP address found on external blacklist'
        ],
        '17566707' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'An invalid request was received from your browser. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'Required header "Accept" missing'
        ],
        '2b021b1f' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. Before trying again, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'IP address found on http:BL blacklist'
        ],
        '2b90f772' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. If you are using the Opera browser, then Opera must appear in your user agent.',
            'logtext' => 'Connection: TE present, not supported by MSIE'
        ],
        '35ea7ffa' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. Check your browser\'s language and locale settings.',
            'logtext' => 'Invalid language specified'
        ],
        '408d7e72' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. Before trying again, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'POST comes too quickly after GET'
        ],
        '41feed15' => [
            'response' => Response::HTTP_BAD_REQUEST,
            'explanation' => 'An invalid request was received. This may be caused by a malfunctioning proxy server. Bypass the proxy server and connect directly, or contact your proxy server administrator.',
            'logtext' => 'Header "Pragma" without "Cache-Control" prohibited for HTTP/1.1 requests'
        ],
        '45b35e30' => [
            'response' => Response::HTTP_BAD_REQUEST,
            'explanation' => 'An invalid request was received from your browser. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'Header "Referer" is corrupt'
        ],
        '582ec5e4' => [
            'response' => Response::HTTP_BAD_REQUEST,
            'explanation' => 'An invalid request was received. If you are using a proxy server, bypass the proxy server or contact your proxy server administrator. This may also be caused by a bug in the Opera web browser.',
            'logtext' => 'Header "TE" present but TE not specified in "Connection" header'
        ],
        '69920ee5' => [
            'response' => Response::HTTP_BAD_REQUEST,
            'explanation' => 'An invalid request was received from your browser. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'Header "Referer" present but blank'
        ],
        '6c502ff1' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'Bot not fully compliant with RFC 2965'
        ],
        '70e45496' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'User agent claimed to be CloudFlare, claim appears false'
        ],
        '71436a15' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'An invalid request was received. You claimed to be a major search engine, but you do not appear to actually be a major search engine.',
            'logtext' => 'User-Agent claimed to be Yahoo, claim appears to be false'
        ],
        '799165c2' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'Rotating user-agents detected'
        ],
        '7a06532b' => [
            'response' => Response::HTTP_BAD_REQUEST,
            'explanation' => 'An invalid request was received from your browser. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'Required header "Accept-Encoding" missing'
        ],
        '7ad04a8a' => [
            'response' => Response::HTTP_BAD_REQUEST,
            'explanation' => 'The automated program you are using is not permitted to access this server. Please use a different program or a standard Web browser.',
            'logtext' => 'Prohibited header "Range" present'
        ],
        '7d12528e' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'Prohibited header "Range" or "Content-Range" in POST request'
        ],
        '939a6fbb' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'The proxy server you are using is not permitted to access this server. Please bypass the proxy server, or contact your proxy server administrator.',
            'logtext' => 'Banned proxy server in use'
        ],
        '96c0bd29' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'URL pattern found on blacklist'
        ],
        '9c9e4979' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'The proxy server you are using is not permitted to access this server. Please bypass the proxy server, or contact your proxy server administrator.',
            'logtext' => 'Prohibited header "via" (lowercase) present'
        ],
        'a0105122' => [
            'response' => Response::HTTP_EXPECTATION_FAILED,
            'explanation' => 'The content type expectation of your browser failed. Please retry your request.',
            'logtext' => 'Header "Expect" prohibited; resend without Expect'
        ],
        'a1084bad' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'User-Agent claimed to be MSIE, with invalid Windows version'
        ],
        'a52f0448' => [
            'response' => Response::HTTP_BAD_REQUEST,
            'explanation' => 'An invalid request was received.  This may be caused by a malfunctioning proxy server or browser privacy software. If you are using a proxy server, bypass the proxy server or contact your proxy server administrator.',
            'logtext' => 'Header "Connection" contains invalid values'
        ],
        'b0924802' => [
            'response' => Response::HTTP_BAD_REQUEST,
            'explanation' => 'An invalid request was received. This may be caused by malicious software on your computer.',
            'logtext' => 'Incorrect form of HTTP/1.0 Keep-Alive'
        ],
        'b40c8ddc' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. Before trying again, close your browser, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'POST more than two days after GET'
        ],
        'b7830251' => [
            'response' => Response::HTTP_BAD_REQUEST,
            'explanation' => 'Your proxy server sent an invalid request. Please contact the proxy server administrator to have this problem fixed.',
            'logtext' => 'Prohibited header "Proxy-Connection" present'
        ],
        'b9cc1d86' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'The proxy server you are using is not permitted to access this server. Please bypass the proxy server, or contact your proxy server administrator.',
            'logtext' => 'Prohibited header "X-Aaaaaaaaaa" or "X-Aaaaaaaaaaaa" present'
        ],
        'c1fa729b' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. Before trying again, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'Use of rotating proxy servers detected'
        ],
        'cd361abb' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. Data may not be posted from offsite forms.',
            'logtext' => 'Referer did not point to a form on this site'
        ],
        'd60b87c7' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. Before trying again, please remove any viruses or spyware from your computer.',
            'logtext' => 'Trackback received via proxy server'
        ],
        'dfd9b1ad' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'Request contained a malicious SQL injection attack'
        ],
        'dfd9b1ae' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'Request contained a malicious JavaScript injection attack'
        ],
        'e3990b47' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. Before trying again, please remove any viruses or spyware from your computer.',
            'logtext' => 'Obviously fake trackback received'
        ],
        'e4de0453' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'An invalid request was received. You claimed to be a major search engine, but you do not appear to actually be a major search engine.',
            'logtext' => 'User-Agent claimed to be msnbot, claim appears to be false'
        ],
        'e87553e1' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'I know you and I don\'t like you, dirty spammer'
        ],
        'f0dcb3fd' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. Before trying again, run anti-virus and anti-spyware software and remove any viruses and spyware from your computer.',
            'logtext' => 'Web browser attempted to send a trackback'
        ],
        'f1182195' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'An invalid request was received. You claimed to be a major search engine, but you do not appear to actually be a major search engine.',
            'logtext' => 'User-Agent claimed to be Googlebot, claim appears to be false'
        ],
        'f9f2b8b9' => [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server. This may be caused by a malfunctioning proxy server or browser privacy software.',
            'logtext' => 'A User-Agent is required but none was provided'
        ],
    ];

    /**
     * Explains the given result.
     *
     * @param ResultInterface $result The result
     *
     * @return array Returns the explanation.
     */
    public function explain(ResultInterface $result)
    {
        if ($result instanceof PositiveResult) {
            return $this->explainPositiveResult($result);
        } elseif ($result instanceof NegativeResult) {
            return $this->explainNegativeResult($result);
        }

        return [];
    }

    /**
     * Explains a positive result.
     *
     * @param PositiveResult $result The result
     *
     * @return array Returns the explanation.
     */
    protected function explainPositiveResult(PositiveResult $result)
    {
        $code = $result->getCode();

        if ($code !== null && isset(self::$responses[$code])) {
            return self::$responses[$code];
        }

        return [
            'response' => Response::HTTP_FORBIDDEN,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => sprintf('Request blocked by %s', implode(', ', $result->getReportingClasses()))
        ];
    }

    /**
     * Explains a negative result.
     *
     * @param NegativeResult $result The result
     *
     * @return array Returns the explanation.
     */
    protected function explainNegativeResult(NegativeResult $result)
    {
        if (in_array(Screener::class, $result->getReportingClasses())) {
            return [
                'logtext' => 'Visitor is whitelisted'
            ];
        }

        return [
            'logtext' => 'Request permitted'
        ];
    }
}
