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

namespace FlameCore\Gatekeeper\Tests;

use PHPUnit\Framework\TestCase;
use FlameCore\Gatekeeper\Visitor;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for Visitor
 */
class VisitorTest extends TestCase
{
    const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36';

    /**
     * @var Visitor
     */
    private $visitor;

    protected function setUp(): void
    {
        $browser = array(
            'HTTP_USER_AGENT' => self::USER_AGENT
        );

        $request = Request::create('/', 'POST', [], [], [], $browser, null);
        $this->visitor = new Visitor($request);
    }

    public function testGetIP()
    {
        $this->assertEquals('127.0.0.1', $this->visitor->getIP());
    }

    public function testGetRequestHeaders()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\HeaderBag', $this->visitor->getRequestHeaders());
    }

    public function testGetRequestMethod()
    {
        $this->assertEquals('POST', $this->visitor->getRequestMethod());
    }

    public function testGetRequestURI()
    {
        $this->assertEquals('/', $this->visitor->getRequestURI());
    }

    public function testGetRequestData()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\ParameterBag', $this->visitor->getRequestData());
    }

    public function testGetRequestScheme()
    {
        $this->assertEquals('http', $this->visitor->getRequestScheme());
    }

    public function testGetServerProtocol()
    {
        $this->assertEquals('HTTP/1.1', $this->visitor->getServerProtocol());
    }

    public function testGetUserAgent()
    {
        $this->assertInstanceOf('FlameCore\Gatekeeper\UserAgent', $this->visitor->getUserAgent());
    }

    public function testToArray()
    {
        $array = $this->visitor->toArray();

        $this->assertIsArray($array);

        $keys = ['ip', 'headers', 'method', 'uri', 'data', 'protocol', 'scheme', 'user_agent'];
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $array);
        }

        $this->assertEquals('127.0.0.1', $array['ip']);
        $this->assertEquals('POST', $array['method']);
        $this->assertEquals('/', $array['uri']);
        $this->assertEquals('HTTP/1.1', $array['protocol']);
        $this->assertEquals('http', $array['scheme']);
        $this->assertEquals(self::USER_AGENT, $array['user_agent']);

        $this->assertIsArray($array['headers']);
        $this->assertIsArray($array['data']);
    }
}
