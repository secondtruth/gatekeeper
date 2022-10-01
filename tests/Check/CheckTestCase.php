<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Tests\Check;

use PHPUnit\Framework\TestCase;
use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Visitor;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test case for Check classes
 */
abstract class CheckTestCase extends TestCase
{
    /**
     * @var CheckInterface
     */
    protected $check;

    /**
     * @param string $uri
     * @param string $method
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string $content
     * @return int|string
     */
    protected function runTestCheck($uri = '/', $method = 'GET', $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $request = Request::create($uri, $method, $parameters, $cookies, $files, $server, $content);
        return $this->runCustomTestCheck($request);
    }

    /**
     * @param Request $request
     * @return int|string
     */
    protected function runCustomTestCheck(Request $request)
    {
        $visitor = new Visitor($request);
        return $this->check->checkVisitor($visitor);
    }
}
