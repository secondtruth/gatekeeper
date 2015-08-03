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

namespace FlameCore\Gatekeeper\Tests\Result;

use FlameCore\Gatekeeper\Result\NegativeResult;
use FlameCore\Gatekeeper\Result\PositiveResult;

/**
 * Test class for Result
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{
    public function testPositiveResult()
    {
        $expectedReportingClasses = [__CLASS__];
        $expectedExplanation = [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => sprintf('Request blocked by %s', implode(', ', $expectedReportingClasses))
        ];

        $result = new PositiveResult($expectedReportingClasses);
        $result->setExplanation($expectedExplanation);

        $this->assertEquals($expectedReportingClasses, $result->getReportingClasses());
        $this->assertEquals($expectedExplanation, $result->getExplanation());
    }

    public function testNegativeResultWhitelist()
    {
        $expectedReportingClasses = ['FlameCore\Gatekeeper\Screener'];
        $expectedExplanation = [
            'logtext' => 'Visitor is whitelisted'
        ];

        $result = new NegativeResult($expectedReportingClasses);
        $result->setExplanation($expectedExplanation);

        $this->assertEquals($expectedReportingClasses, $result->getReportingClasses());
        $this->assertEquals($expectedExplanation, $result->getExplanation());
    }

    public function testNegativeResult()
    {
        $expectedExplanation = [
            'logtext' => 'Request permitted'
        ];

        $result = new NegativeResult();
        $result->setExplanation($expectedExplanation);

        $this->assertEmpty($result->getReportingClasses());
        $this->assertEquals($expectedExplanation, $result->getExplanation());
    }
}
