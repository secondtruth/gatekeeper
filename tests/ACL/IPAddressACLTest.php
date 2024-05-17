<?php
/*
 * Gatekeeper
 * Copyright (C) 2024 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Tests\ACL;

use PHPUnit\Framework\TestCase;
use Secondtruth\Gatekeeper\Visitor;
use Secondtruth\Gatekeeper\ACL\IPAddressACL;
use Secondtruth\Gatekeeper\Listing\IPList;
use Symfony\Component\HttpFoundation\Request;

class IPAddressACLTest extends TestCase
{
    private $acl;

    protected function setUp(): void
    {
        $allowed = new IPList(['127.0.0.1']);
        $denied = new IPList(['192.168.1.1']);
        $this->acl = new IPAddressACL($allowed, $denied);
    }

    public function testIsAllowed()
    {
        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '127.0.0.1']);
        $this->assertTrue($this->acl->isAllowed(Visitor::fromSymfonyRequest($request)));
    }

    public function testIsDenied()
    {
        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.1']);
        $this->assertTrue($this->acl->isDenied(Visitor::fromSymfonyRequest($request)));
    }
}
