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
use PHPUnit\Framework\TestCase;
use Secondtruth\Gatekeeper\ACL\IPAddressACL;
use Secondtruth\Gatekeeper\Listing\IPList;
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

        $allowed = new IPList('127.0.0.2');
        $denied = new IPList('127.0.0.3');
        $acl = new IPAddressACL($allowed, $denied);
        $this->gatekeeper->addACL($acl);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testAllowList()
    {
        $request = $this->createRequest([], '127.0.0.2');
        $this->gatekeeper->run($request, $this->screener);
    }

    public function testDenyList()
    {
        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessageMatches('#<p>Your request has been blocked\.</p>\n<p>You do not have permission to access this server\.</p>#');

        $request = $this->createRequest([], '127.0.0.3');
        $this->gatekeeper->run($request, $this->screener);
    }

    public function testPositive()
    {
        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessageMatches('#<p>Your request has been blocked\.</p>#');

        $request = $this->createRequest(['X-Gatekeeper-Block' => 'true']);
        $this->gatekeeper->run($request, $this->screener);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNegative()
    {
        try {
            $request = $this->createRequest();
            $this->gatekeeper->run($request, $this->screener);
        } catch (AccessDeniedException) {
            $this->fail('AccessDeniedException was thrown.');
        }
    }

    /**
     * @param array $headers
     * @param string $ip
     * @return ServerRequest
     */
    protected function createRequest(array $headers = [], string $ip = '127.0.0.1'): ServerRequest
    {
        return new ServerRequest('GET', '/', $headers, null, '1.1', ['REMOTE_ADDR' => $ip]);
    }
}
