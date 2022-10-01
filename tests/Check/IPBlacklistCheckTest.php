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
use Secondtruth\Gatekeeper\Check\IPBlacklistCheck;
use Secondtruth\Gatekeeper\Listing\IPList;

/**
 * Test class for IPBlacklistCheck
 */
class IPBlacklistCheckTest extends CheckTestCase
{
    protected function setUp(): void
    {
        $this->check = new IPBlacklistCheck();

        $list = new IPList();
        $list->add(['127.0.0.2/32']);
        $this->check->setBlacklist($list);
    }

    public function testCheckPositive()
    {
        $result = $this->runTestCheck(null, null, [], [], [], ['REMOTE_ADDR' => '127.0.0.2']);

        $this->assertEquals(CheckInterface::RESULT_BLOCK, $result);
    }

    public function testCheckNegative()
    {
        $result = $this->runTestCheck();

        $this->assertEquals(CheckInterface::RESULT_OKAY, $result);
    }
}
