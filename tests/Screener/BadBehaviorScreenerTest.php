<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Tests\Screener;

use PHPUnit\Framework\TestCase;
use Secondtruth\Gatekeeper\Screener;
use Secondtruth\Gatekeeper\Result\ResultInterface;
use Secondtruth\Gatekeeper\Visitor;
use Secondtruth\Gatekeeper\Screener\BadBehaviorScreener;
use Secondtruth\Gatekeeper\Result\PositiveResult;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for BadBehaviorScreener
 */
class BadBehaviorScreenerTest extends TestCase
{
    /**
     * @var Screener
     */
    private $screener;

    protected function setUp(): void
    {
        $this->screener = new BadBehaviorScreener();
    }

    public function testCheckPositiveSpambotNamesBeginning()
    {
        /** @var $result PositiveResult */
        $result = $this->runTestScreening(['HTTP_USER_AGENT' => '8484 Boston Project']);

        $this->assertInstanceOf('Secondtruth\Gatekeeper\Result\PositiveResult', $result);
        $this->assertEquals(['Secondtruth\Gatekeeper\Check\UserAgentBlacklistCheck'], $result->getReportingClasses());
    }

    public function testCheckPositiveSpambotNamesAnywhere()
    {
        /** @var $result PositiveResult */
        $result = $this->runTestScreening(['HTTP_USER_AGENT' => 'foo bar <script></script>']);

        $this->assertInstanceOf('Secondtruth\Gatekeeper\Result\PositiveResult', $result);
        $this->assertEquals(['Secondtruth\Gatekeeper\Check\UserAgentBlacklistCheck'], $result->getReportingClasses());
    }

    public function testCheckPositiveSpambotNamesRegex()
    {
        /** @var $result PositiveResult */
        $result = $this->runTestScreening(['HTTP_USER_AGENT' => 'MSIE 2']);

        $this->assertInstanceOf('Secondtruth\Gatekeeper\Result\PositiveResult', $result);
        $this->assertEquals(['Secondtruth\Gatekeeper\Check\UserAgentBlacklistCheck'], $result->getReportingClasses());
    }

    public function testCheckNegative()
    {
        $result = $this->runTestScreening();

        $this->assertInstanceOf('Secondtruth\Gatekeeper\Result\NegativeResult', $result);
    }

    /**
     * @param array $server
     * @return ResultInterface
     */
    protected function runTestScreening(array $server = [])
    {
        $request = Request::create('/', 'POST', [], [], [], $server);
        $visitor = new Visitor($request);

        return $this->screener->screenVisitor($visitor);
    }
}
