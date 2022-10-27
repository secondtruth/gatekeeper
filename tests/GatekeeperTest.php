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

use Nyholm\Psr7\ServerRequest;
use Secondtruth\Gatekeeper\Listing\IPList;
use PHPUnit\Framework\TestCase;
use Secondtruth\Gatekeeper\Screener;
use Secondtruth\Gatekeeper\Gatekeeper;
use Secondtruth\Gatekeeper\Exceptions\AccessDeniedException;
use Secondtruth\Gatekeeper\Tests\Check\DummyCheck;

/**
 * Test class for Gatekeeper
 */
class GatekeeperTest extends TestCase
{
    /**
     * @var Gatekeeper
     */
    private $gatekeeper;

    /**
     * @var Screener
     */
    private $screener;

    protected function setUp(): void
    {
        $this->screener = new Screener();
        $this->screener->addCheck(new DummyCheck());

        $this->gatekeeper = new Gatekeeper();

        $list = new IPList();
        $list->add(['127.0.0.2/32']);
        $this->gatekeeper->setWhitelist($list);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testWhitelist()
    {
        $request = new ServerRequest('GET', '/', [], null, '1.1', ['REMOTE_ADDR' => '127.0.0.2']);
        $this->gatekeeper->run($request, $this->screener);
    }

    public function testPositive()
    {
        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessageMatches('#<p>Your request has been blocked\.</p>#');

        $request = new ServerRequest('GET', '/', ['X-Gatekeeper-Block' => 'true'], null, '1.1', ['REMOTE_ADDR' => '127.0.0.1']);
        $this->gatekeeper->run($request, $this->screener);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNegative()
    {
        try {
            $request = new ServerRequest('GET', '/', [], null, '1.1', ['REMOTE_ADDR' => '127.0.0.1']);
            $this->gatekeeper->run($request, $this->screener);
        } catch (AccessDeniedException) {
            $this->fail('AccessDeniedException was thrown.');
        }
    }
}
