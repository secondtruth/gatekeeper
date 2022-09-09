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

namespace FlameCore\Gatekeeper\Tests\Check;

use PHPUnit\Framework\TestCase;
use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Visitor;
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
