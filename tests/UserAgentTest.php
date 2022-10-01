<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Tests;

use Secondtruth\Gatekeeper\UserAgent;
use PHPUnit\Framework\TestCase;

class UserAgentTest extends TestCase
{
    protected const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36';

    /**
     * @var UserAgent
     */
    private $userAgent;

    protected function setUp(): void
    {
        $this->userAgent = new UserAgent(self::USER_AGENT);
    }

    public function testGetUserAgentString()
    {
        $this->assertEquals(self::USER_AGENT, (string) $this->userAgent->getUserAgentString());
    }

    public function testIsUnknown()
    {
        $this->assertFalse($this->userAgent->isUnknown());
    }

    public function testIsBrowser()
    {
        $this->assertTrue($this->userAgent->isBrowser());
    }

    public function testIsBot()
    {
        $this->assertFalse($this->userAgent->isBot());
    }
}
