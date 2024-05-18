<?php
/*
 * Gatekeeper
 * Copyright (C) 2024 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Check;

use Secondtruth\Gatekeeper\Visitor;

/**
 * Class UrlCheck
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UrlCheck extends AbstractCheck
{
    const REASON_MISC = '96c0bd29';
    const REASON_SQL_INJECTION = 'dfd9b1ad';

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        foreach ($this->getBadPatterns() as $reason => $items) {
            foreach ($items as $item) {
                if ($this->match($visitor->getRequestURI(), $item['pattern'], $item['ignore_case'])) {
                    return $reason ?: CheckInterface::RESULT_BLOCK;
                }
            }
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Tries to find the pattern in the request URI.
     *
     * @param string $uri The request URI
     * @param string $pattern The bad pattern to match
     * @param bool $ignoreCase Whether to ignore the case
     * @return bool Returns TRUE if the pattern is matched, FALSE otherwise.
     */
    protected function match($uri, $pattern, $ignoreCase = false)
    {
        $func = $ignoreCase ? 'stripos' : 'strpos';

        return $func($uri, $pattern) !== false;
    }

    /**
     * Gets list of request URI parts which determine a bad bot.
     *
      * @return array<string, array<array{pattern: string, ignore_case: bool}>>
     */
    protected function getBadPatterns()
    {
        return [
            self::REASON_MISC => [
                ['pattern' => '../', 'ignore_case' => false], // path traversal
                ['pattern' => '..\\', 'ignore_case' => false], // path traversal
                ['pattern' => '0x31303235343830303536', 'ignore_case' => true], // Havij
                ['pattern' => 'w00tw00t', 'ignore_case' => true] // vulnerability scanner
            ],
            self::REASON_SQL_INJECTION => [
                ['pattern' => ';DECLARE%20@', 'ignore_case' => false], // SQL injection
                ['pattern' => '%27--', 'ignore_case' => false], // SQL injection
                ['pattern' => '%27 --', 'ignore_case' => false], // SQL injection
                ['pattern' => '%27%23', 'ignore_case' => false], // SQL injection
                ['pattern' => '%27 %23', 'ignore_case' => false], // SQL injection
                ['pattern' => '%60information_schema%60', 'ignore_case' => true], // SQL injection probe
                ['pattern' => '+%2F*%21', 'ignore_case' => true], // SQL injection probe
                ['pattern' => 'benchmark%28', 'ignore_case' => true], // SQL injection probe
                ['pattern' => 'insert+into+', 'ignore_case' => true], // SQL injection
                ['pattern' => 'r3dm0v3', 'ignore_case' => true], // SQL injection probe
                ['pattern' => 'select+1+from', 'ignore_case' => true], // SQL injection probe
                ['pattern' => 'union+all+select', 'ignore_case' => true], // SQL injection probe
                ['pattern' => 'union+select', 'ignore_case' => true], // SQL injection probe
                ['pattern' => 'waitfor+delay+', 'ignore_case' => true], // SQL injection probe
            ]
        ];
    }
}
