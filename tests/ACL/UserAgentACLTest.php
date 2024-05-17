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
use Secondtruth\Gatekeeper\ACL\UserAgentACL;
use Secondtruth\Gatekeeper\Listing\StringList;
use Symfony\Component\HttpFoundation\Request;

class UserAgentACLTest extends TestCase
{
    private $acl;

    protected function setUp(): void
    {
        $allowed = new StringList(['Mozilla/5.0']);
        $denied = new StringList(['Googlebot']);
        $this->acl = new UserAgentACL($allowed, $denied);
    }

    public function testIsAllowed()
    {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_USER_AGENT' => 'Mozilla/5.0']);
        $this->assertTrue($this->acl->isAllowed(Visitor::fromSymfonyRequest($request)));
    }

    public function testIsDenied()
    {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_USER_AGENT' => 'Googlebot']);
        $this->assertTrue($this->acl->isDenied(Visitor::fromSymfonyRequest($request)));
    }
}
