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
 * Class UrlCheck
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UrlCheck implements CheckInterface
{
    const REASON_MISC = '96c0bd29';
    const REASON_SQL_INJECTION = 'dfd9b1ad';

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        // Case-sensitive checks
        foreach ($this->getBadPatterns() as $pattern) {
            if (strpos($visitor->getRequestURI(), $pattern[0]) !== false) {
                return $pattern[1] ?: CheckInterface::RESULT_BLOCK;
            }
        }

        // Case-insensitive checks
        foreach ($this->getBadPatternsCaseInsensitive() as $pattern) {
            if (stripos($visitor->getRequestURI(), $pattern[0]) !== false) {
                return $pattern[1] ?: CheckInterface::RESULT_BLOCK;
            }
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Gets list of request URI parts (case sensitive) which determine a bad bot.
     *
     * @return string[]
     */
    protected function getBadPatterns()
    {
        return array(
            ['../', self::REASON_MISC], // path traversal
            ['..\\', self::REASON_MISC], // path traversal
            [';DECLARE%20@', self::REASON_SQL_INJECTION], // SQL injection
            ['%27--', self::REASON_SQL_INJECTION], // SQL injection
            ['%27 --', self::REASON_SQL_INJECTION], // SQL injection
            ['%27%23', self::REASON_SQL_INJECTION], // SQL injection
            ['%27 %23', self::REASON_SQL_INJECTION], // SQL injection
        );
    }

    /**
     * Gets list of request URI parts (case insensitive) which determine a bad bot.
     *
     * @return string[]
     */
    protected function getBadPatternsCaseInsensitive()
    {
        return array(
            ['0x31303235343830303536', self::REASON_MISC], // Havij
            ['%60information_schema%60', self::REASON_SQL_INJECTION], // SQL injection probe
            ['+%2F*%21', self::REASON_SQL_INJECTION], // SQL injection probe
            ['benchmark%28', self::REASON_SQL_INJECTION], // SQL injection probe
            ['insert+into+', self::REASON_SQL_INJECTION], // SQL injection
            ['r3dm0v3', self::REASON_SQL_INJECTION], // SQL injection probe
            ['select+1+from', self::REASON_SQL_INJECTION], // SQL injection probe
            ['union+all+select', self::REASON_SQL_INJECTION], // SQL injection probe
            ['union+select', self::REASON_SQL_INJECTION], // SQL injection probe
            ['waitfor+delay+', self::REASON_SQL_INJECTION], // SQL injection probe
            ['w00tw00t', self::REASON_MISC] // vulnerability scanner
        );
    }
}
