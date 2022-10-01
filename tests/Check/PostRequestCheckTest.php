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
use Secondtruth\Gatekeeper\Check\PostRequestCheck;

/**
 * Test class for PostRequestCheck
 */
class PostRequestCheckTest extends CheckTestCase
{
    protected function setUp(): void
    {
        $this->check = new PostRequestCheck();
    }

    public function testCheckPositive()
    {
        // Check #1
        $result = $this->runTestCheck('/', 'POST', [], [], [], ['HTTP_RANGE' => 'bytes=0-99998', 'HTTP_USER_AGENT' => 'MovableType']);
        $this->assertEquals('7d12528e', $result);

        // Check #3
        $result = $this->runTestCheck('/', 'POST', ["foo\tdocument.write" => 'bar']);
        $this->assertEquals('dfd9b1ae', $result);

        // Check #4
        $result = $this->runTestCheck('/', 'POST', [], [], [], ['HTTP_HOST' => 'example.org', 'HTTP_REFERER' => 'http://www.example.net/form.html']);
        $this->assertEquals('cd361abb', $result);
    }

    public function testCheckTrackbackPositive()
    {
        $data = ['title' => 'Foobar', 'url' => 'http://www.example.net', 'blog_name' => 'A Blog'];

        // Check #1
        $result = $this->runTestCheck('/', 'POST', $data, [], [], ['HTTP_USER_AGENT' => 'Mozilla/5.0 ... Chrome/41.0.2227.0 ...']);
        $this->assertEquals('f0dcb3fd', $result);

        // Check #2
        $result = $this->runTestCheck('/', 'POST', $data, [], [], ['HTTP_CLIENT_IP' => '127.0.0.1']);
        $this->assertEquals('d60b87c7', $result);

        // Check #3
        $result = $this->runTestCheck('/', 'POST', $data, [], [], ['HTTP_USER_AGENT' => 'WordPress/4.3; http://www.example.net']);
        $this->assertEquals('e3990b47', $result);
    }

    public function testCheckNegative()
    {
        $result = $this->runTestCheck();

        $this->assertEquals(CheckInterface::RESULT_OKAY, $result);
    }
}
