<?php
/**
 * Webtools Library
 * Copyright (C) 2014 IceFlame.net
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE
 * FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER
 * IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING
 * OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 *
 * @package  FlameCore\Webtools
 * @version  1.2
 * @link     http://www.flamecore.org
 * @license  ISC License <http://opensource.org/licenses/ISC>
 */

namespace FlameCore\Gatekeeper\Tests;

use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Check\BlacklistCheck;
use FlameCore\Gatekeeper\Visitor;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for BlacklistCheck
 */
class BlacklistCheckTest extends CheckTestCase
{
    protected function setUp()
    {
        $this->check = new BlacklistCheck();
        $this->check->setBlacklist(['127.0.0.1/32']);
    }

    public function testCheckPositive()
    {
        $request = Request::create('/', null, [], [], [], [], null);
        $visitor = new Visitor($request);

        $result = $this->check->checkVisitor($visitor);

        $this->assertEquals(CheckInterface::RESULT_BLOCK, $result);
    }

    public function testCheckNegative()
    {
        $request = Request::create('/', null, [], [], [], ['REMOTE_ADDR' => '127.0.0.2'], null);
        $visitor = new Visitor($request);

        $result = $this->check->checkVisitor($visitor);

        $this->assertEquals(CheckInterface::RESULT_OKAY, $result);
    }
}
