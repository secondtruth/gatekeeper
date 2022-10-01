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

use Secondtruth\Gatekeeper\Listing\IPList;
use PHPUnit\Framework\TestCase;
use Secondtruth\Gatekeeper\Screener;
use Secondtruth\Gatekeeper\Gatekeeper;
use Secondtruth\Gatekeeper\Exceptions\AccessDeniedException;
use Secondtruth\Gatekeeper\Check\IPBlacklistCheck;
use Symfony\Component\HttpFoundation\Request;

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

        $check = new IPBlacklistCheck();

        $list = new IPList();
        $list->add(['127.0.0.3/32']);
        $check->setBlacklist($list);

        $this->screener->addCheck($check);

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
        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '127.0.0.2']);
        $this->gatekeeper->run($request, $this->screener);
    }

    public function testPositive()
    {
        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessageMatches('#<p>Your request has been blocked\.</p>#');

        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '127.0.0.3']);
        $this->gatekeeper->run($request, $this->screener);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNegative()
    {
        try {
            $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '127.0.0.1']);
            $this->gatekeeper->run($request, $this->screener);
        } catch (AccessDeniedException) {
            $this->fail('AccessDeniedException was thrown.');
        }
    }
}
