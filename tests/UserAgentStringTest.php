<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace FlameCore\Gatekeeper\Tests;

use FlameCore\Gatekeeper\UserAgentString;
use PHPUnit\Framework\TestCase;

class UserAgentStringTest extends TestCase
{
    protected const USER_AGENT_STRING = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36';

    protected $userAgentString;

    public function setUp(): void
    {
        $this->userAgentString = new UserAgentString(self::USER_AGENT_STRING);
    }

    public function testToString()
    {
        $this->assertEquals(self::USER_AGENT_STRING, (string) $this->userAgentString);
    }

    public function testContains()
    {
        $this->assertTrue($this->userAgentString->contains('chrome'));
        $this->assertTrue($this->userAgentString->contains('Chrome', true));
    }

    public function testContainsAny()
    {
        $this->assertTrue($this->userAgentString->containsAny(['chrome', 'safari']));
        $this->assertTrue($this->userAgentString->containsAny(['Chrome', 'Safari'], true));
    }

    public function testStartsWith()
    {
        $this->assertTrue($this->userAgentString->startsWith('mozilla'));
        $this->assertTrue($this->userAgentString->startsWith('Mozilla', true));
    }

    public function testStartsWithAny()
    {
        $this->assertTrue($this->userAgentString->startsWithAny(['mozilla', 'foobar']));
        $this->assertTrue($this->userAgentString->startsWithAny(['Mozilla', 'FooBar'], true));
    }

    public function testCompare()
    {
        $this->assertEquals(0, $this->userAgentString->compare(self::USER_AGENT_STRING));
        $this->assertEquals(0, $this->userAgentString->compare(strtolower(self::USER_AGENT_STRING), false));
    }

    public function testCompareStart()
    {
        $this->assertEquals(0, $this->userAgentString->compareStart('Mozilla/5.0'));
        $this->assertEquals(0, $this->userAgentString->compareStart('mozilla/5.0', false));
    }
}
