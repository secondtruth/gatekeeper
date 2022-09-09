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
use FlameCore\Gatekeeper\Result\NegativeResult;
use FlameCore\Gatekeeper\Result\PositiveResult;
use FlameCore\Gatekeeper\Result\ResultInterface;
use FlameCore\Gatekeeper\Check\IPBlacklistCheck;
use FlameCore\Gatekeeper\Listing\IPList;
use FlameCore\Gatekeeper\Screener;
use FlameCore\Gatekeeper\Visitor;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for Screener
 */
class ScreenerTest extends TestCase
{
    /**
     * @var Screener
     */
    private $screener;

    protected function setUp(): void
    {
        $this->screener = new Screener();

        $check = new IPBlacklistCheck();

        $list = new IPList();
        $list->add(['127.0.0.3/32']);
        $check->setBlacklist($list);

        $this->screener->addCheck($check);
    }

    public function testPositive()
    {
        /** @var PositiveResult $result */
        $result = $this->runTestScreening('127.0.0.3');

        $this->assertInstanceOf('FlameCore\Gatekeeper\Result\PositiveResult', $result);

        $expected = array_keys($this->screener->getChecks());
        $this->assertEquals($expected, $result->getReportingClasses());
    }

    public function testNegative()
    {
        /** @var NegativeResult $result */
        $result = $this->runTestScreening('127.0.0.4');

        $this->assertInstanceOf('FlameCore\Gatekeeper\Result\NegativeResult', $result);
        $this->assertEmpty($result->getReportingClasses());
    }

    /**
     * @param string $ip
     * @return ResultInterface
     */
    protected function runTestScreening($ip)
    {
        $request = Request::create('/', null, [], [], [], ['REMOTE_ADDR' => $ip], null);
        $visitor = new Visitor($request);

        return $this->screener->screenVisitor($visitor);
    }
}
