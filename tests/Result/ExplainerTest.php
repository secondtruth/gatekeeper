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

use FlameCore\Gatekeeper\Result\Explainer;
use FlameCore\Gatekeeper\Result\NegativeResult;
use FlameCore\Gatekeeper\Result\PositiveResult;

/**
 * Test class for Explainer
 */
class ExplainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FlameCore\Gatekeeper\Result\Explainer
     */
    private $explainer;

    protected function setUp()
    {
        $this->explainer = new Explainer();
    }

    public function testExplainPositiveResult()
    {
        $expectedReportingClasses = [__CLASS__];
        $expectedExplanation = [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => sprintf('Request blocked by %s', implode(', ', $expectedReportingClasses))
        ];

        $result = new PositiveResult($expectedReportingClasses);

        $this->assertEquals($expectedExplanation, $this->explainer->explain($result));
    }

    public function testExplainPositiveResultWithCode()
    {
        $expectedExplanation = [
            'response' => 403,
            'explanation' => 'You do not have permission to access this server.',
            'logtext' => 'I know you and I don\'t like you, dirty spammer'
        ];

        $result = new PositiveResult([__CLASS__], 'e87553e1');

        $this->assertEquals($expectedExplanation, $this->explainer->explain($result));
    }

    public function testExplainNegativeResult()
    {
        $expectedExplanation = [
            'logtext' => 'Request permitted'
        ];

        $result = new NegativeResult();

        $this->assertEquals($expectedExplanation, $this->explainer->explain($result));
    }

    public function testExplainNegativeResultWhitelist()
    {
        $expectedExplanation = [
            'logtext' => 'Visitor is whitelisted'
        ];

        $result = new NegativeResult(['FlameCore\Gatekeeper\Screener']);

        $this->assertEquals($expectedExplanation, $this->explainer->explain($result));
    }
}
