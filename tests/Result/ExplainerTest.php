<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Tests\Result;

use PHPUnit\Framework\TestCase;
use Secondtruth\Gatekeeper\ACL\IPAddressACL;
use Secondtruth\Gatekeeper\Result\Explainer;
use Secondtruth\Gatekeeper\Result\NegativeResult;
use Secondtruth\Gatekeeper\Result\PositiveResult;

/**
 * Test class for Explainer
 */
class ExplainerTest extends TestCase
{
    /**
     * @var Explainer
     */
    private $explainer;

    protected function setUp(): void
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

    public function testExplainNegativeResultFromACL()
    {
        $expectedExplanation = [
            'logtext' => 'Visitor is explicitly allowed'
        ];

        $result = new NegativeResult(IPAddressACL::class);

        $this->assertEquals($expectedExplanation, $this->explainer->explain($result));
    }
}
