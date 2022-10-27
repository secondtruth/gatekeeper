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

use PHPUnit\Framework\TestCase;
use Secondtruth\Gatekeeper\Result\NegativeResult;
use Secondtruth\Gatekeeper\Result\PositiveResult;
use Secondtruth\Gatekeeper\Result\ResultInterface;
use Secondtruth\Gatekeeper\Screener;
use Secondtruth\Gatekeeper\Tests\Check\DummyCheck;
use Secondtruth\Gatekeeper\Visitor;
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
        $this->screener->addCheck(new DummyCheck());
    }

    public function testPositive()
    {
        /** @var PositiveResult $result */
        $result = $this->runTestScreening(true);

        $this->assertInstanceOf(PositiveResult::class, $result);

        $expected = array_keys($this->screener->getChecks());
        $this->assertEquals($expected, $result->getReportingClasses());
    }

    public function testNegative()
    {
        /** @var NegativeResult $result */
        $result = $this->runTestScreening();

        $this->assertInstanceOf(NegativeResult::class, $result);
        $this->assertEmpty($result->getReportingClasses());
    }

    /**
     * @param string $ip
     * @return ResultInterface
     */
    protected function runTestScreening(bool $block = false)
    {
        $request = Request::create('/', 'GET', [], [], [], $block ? ['HTTP_X_GATEKEEPER_BLOCK' => 'true'] : []);
        $visitor = new Visitor($request);

        return $this->screener->screenVisitor($visitor);
    }
}
