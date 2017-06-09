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

use FlameCore\Gatekeeper\Check\IPBlacklistCheck;
use FlameCore\Gatekeeper\Listing\IPList;
use FlameCore\Gatekeeper\Screener;
use FlameCore\Gatekeeper\Visitor;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for Screener
 */
class ScreenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FlameCore\Gatekeeper\Screener
     */
    private $screener;

    protected function setUp()
    {
        $this->screener = new Screener();

        $list = new IPList();
        $list->add(['127.0.0.1/32', '127.0.0.2']);
        $this->screener->setWhitelist($list);

        $check = new IPBlacklistCheck();

        $list = new IPList();
        $list->add(['127.0.0.3/32']);
        $check->setBlacklist($list);

        $this->screener->addCheck($check);
    }

    public function testWhitelist()
    {
        /** @var \FlameCore\Gatekeeper\Result\NegativeResult $result */
        $result = $this->runTestScreening('127.0.0.2');

        $this->assertInstanceOf('FlameCore\Gatekeeper\Result\NegativeResult', $result);

        $expected = [get_class($this->screener)];
        $this->assertEquals($expected, $result->getReportingClasses());
    }

    public function testPositive()
    {
        /** @var \FlameCore\Gatekeeper\Result\PositiveResult $result */
        $result = $this->runTestScreening('127.0.0.3');

        $this->assertInstanceOf('FlameCore\Gatekeeper\Result\PositiveResult', $result);

        $expected = array_keys($this->screener->getChecks());
        $this->assertEquals($expected, $result->getReportingClasses());
    }

    public function testNegative()
    {
        /** @var \FlameCore\Gatekeeper\Result\NegativeResult $result */
        $result = $this->runTestScreening('127.0.0.4');

        $this->assertInstanceOf('FlameCore\Gatekeeper\Result\NegativeResult', $result);
        $this->assertEmpty($result->getReportingClasses());
    }

    /**
     * @param string $ip
     * @return \FlameCore\Gatekeeper\Result\ResultInterface
     */
    protected function runTestScreening($ip)
    {
        $request = Request::create('/', null, [], [], [], ['REMOTE_ADDR' => $ip], null);
        $visitor = new Visitor($request);

        return $this->screener->screenVisitor($visitor);
    }
}
