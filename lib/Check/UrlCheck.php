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
    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        // Case-sensitive checks
        foreach ($this->getBadPatterns() as $badPattern) {
            if (strpos($visitor->getRequestURI(), $badPattern[0]) !== false) {
                return $badPattern[1] ?: CheckInterface::RESULT_BLOCK;
            }
        }

        // Case-insensitive checks
        foreach ($this->getBadPatternsCaseInsensitive() as $badPattern) {
            if (stripos($visitor->getRequestURI(), $badPattern[0]) !== false) {
                return $badPattern[1] ?: CheckInterface::RESULT_BLOCK;
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
            ['../', '96c0bd29'], // path traversal
            ['..\\', '96c0bd29'], // path traversal
            [';DECLARE%20@', 'dfd9b1ad'], // SQL injection
            ['%27--', 'dfd9b1ad'], // SQL injection
            ['%27 --', 'dfd9b1ad'], // SQL injection
            ['%27%23', 'dfd9b1ad'], // SQL injection
            ['%27 %23', 'dfd9b1ad'], // SQL injection
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
            ['0x31303235343830303536', '96c0bd29'], // Havij
            ['%60information_schema%60', 'dfd9b1ad'], // SQL injection probe
            ['+%2F*%21', 'dfd9b1ad'], // SQL injection probe
            ['benchmark%28', 'dfd9b1ad'], // SQL injection probe
            ['insert+into+', 'dfd9b1ad'], // SQL injection
            ['r3dm0v3', 'dfd9b1ad'], // SQL injection probe
            ['select+1+from', 'dfd9b1ad'], // SQL injection probe
            ['union+all+select', 'dfd9b1ad'], // SQL injection probe
            ['union+select', 'dfd9b1ad'], // SQL injection probe
            ['waitfor+delay+', 'dfd9b1ad'], // SQL injection probe
            ['w00tw00t', '96c0bd29'] // vulnerability scanner
        );
    }
}
