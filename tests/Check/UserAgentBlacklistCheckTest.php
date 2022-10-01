<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Tests\Check;

use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Check\UserAgentBlacklistCheck;
use Secondtruth\Gatekeeper\Listing\StringList;

/**
 * Test class for UserAgentBlacklistCheck
 */
class UserAgentBlacklistCheckTest extends CheckTestCase
{
    protected function setUp(): void
    {
        $this->check = new UserAgentBlacklistCheck();

        $list = new StringList();
        $list->is(['Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)']);
        $this->check->setBlacklist($list);
    }

    public function testCheckPositive()
    {
        $result = $this->runTestCheck(null, null, [], [], [], ['HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)']);

        $this->assertEquals(CheckInterface::RESULT_BLOCK, $result);
    }

    public function testCheckNegative()
    {
        $result = $this->runTestCheck();

        $this->assertEquals(CheckInterface::RESULT_OKAY, $result);
    }
}
