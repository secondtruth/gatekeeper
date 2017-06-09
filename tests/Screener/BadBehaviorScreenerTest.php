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

namespace FlameCore\Gatekeeper\Tests\Screener;

use FlameCore\Gatekeeper\Visitor;
use FlameCore\Gatekeeper\Screener\BadBehaviorScreener;
use FlameCore\Gatekeeper\Result\PositiveResult;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for BadBehaviorScreener
 */
class BadBehaviorScreenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FlameCore\Gatekeeper\Screener
     */
    private $screener;

    protected function setUp()
    {
        $this->screener = new BadBehaviorScreener();
    }

    public function testCheckPositiveSpambotNamesBeginning()
    {
        /** @var $result PositiveResult */
        $result = $this->runTestScreening(['HTTP_USER_AGENT' => '8484 Boston Project']);

        $this->assertInstanceOf('FlameCore\Gatekeeper\Result\PositiveResult', $result);
        $this->assertEquals(['FlameCore\Gatekeeper\Check\UserAgentBlacklistCheck'], $result->getReportingClasses());
    }

    public function testCheckPositiveSpambotNamesAnywhere()
    {
        /** @var $result PositiveResult */
        $result = $this->runTestScreening(['HTTP_USER_AGENT' => 'foo bar <script></script>']);

        $this->assertInstanceOf('FlameCore\Gatekeeper\Result\PositiveResult', $result);
        $this->assertEquals(['FlameCore\Gatekeeper\Check\UserAgentBlacklistCheck'], $result->getReportingClasses());
    }

    public function testCheckPositiveSpambotNamesRegex()
    {
        /** @var $result PositiveResult */
        $result = $this->runTestScreening(['HTTP_USER_AGENT' => 'MSIE 2']);

        $this->assertInstanceOf('FlameCore\Gatekeeper\Result\PositiveResult', $result);
        $this->assertEquals(['FlameCore\Gatekeeper\Check\UserAgentBlacklistCheck'], $result->getReportingClasses());
    }

    public function testCheckNegative()
    {
        $result = $this->runTestScreening();

        $this->assertInstanceOf('FlameCore\Gatekeeper\Result\NegativeResult', $result);
    }

    /**
     * @param array $server
     * @return \FlameCore\Gatekeeper\Result\ResultInterface
     */
    protected function runTestScreening(array $server = [])
    {
        $request = Request::create('/', null, [], [], [], $server);
        $visitor = new Visitor($request);

        return $this->screener->screenVisitor($visitor);
    }
}
