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

namespace FlameCore\Gatekeeper\Tests\Check;

use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Check\UrlCheck;

/**
 * Test class for UrlCheck
 */
class UrlCheckTest extends CheckTestCase
{
    protected function setUp()
    {
        $this->check = new UrlCheck();
    }

    public function testCheckPositive()
    {
        // Check #1
        $result = $this->runTestCheck('/?;DECLARE%20@');
        $this->assertEquals('dfd9b1ad', $result);

        // Check #2
        $result = $this->runTestCheck('/?0x31303235343830303536');
        $this->assertEquals('96c0bd29', $result);
    }

    public function testCheckNegative()
    {
        $result = $this->runTestCheck();

        $this->assertEquals(CheckInterface::RESULT_OKAY, $result);
    }
}
