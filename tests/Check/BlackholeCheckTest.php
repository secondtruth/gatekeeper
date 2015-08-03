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
use FlameCore\Gatekeeper\Check\BlackholeCheck;

/**
 * Test class for BlackholeCheck
 */
class BlackholeCheckTest extends CheckTestCase
{
    protected function setUp()
    {
        $this->check = new BlackholeCheck();
        $this->check->addList('dnsbl.test.flamecore.org');
    }

    public function testCheckPositive()
    {
        $result = $this->runTestCheck();

        $this->assertEquals(CheckInterface::RESULT_BLOCK, $result);
    }

    public function testCheckNegative()
    {
        $result = $this->runTestCheck(null, null, [], [], [], ['REMOTE_ADDR' => '127.0.0.2']);

        $this->assertEquals(CheckInterface::RESULT_OKAY, $result);
    }
}
