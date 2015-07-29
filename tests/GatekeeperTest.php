<?php
/**
 * FlameCore Gatekeeper
 * Copyright (C) 2015 IceFlame.net
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
 * @package  FlameCore\Gatekeeper
 * @version  0.1-dev
 * @link     http://www.flamecore.org
 * @license  http://opensource.org/licenses/ISC ISC License
 */

namespace FlameCore\Gatekeeper\Tests;

use FlameCore\Gatekeeper\Screener;
use FlameCore\Gatekeeper\Gatekeeper;
use FlameCore\Gatekeeper\AccessDeniedException;
use FlameCore\Gatekeeper\Check\BlacklistCheck;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for Gatekeeper
 */
class GatekeeperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FlameCore\Gatekeeper\Gatekeeper
     */
    private $gatekeeper;

    /**
     * @var \FlameCore\Gatekeeper\Screener
     */
    private $screener;

    protected function setUp()
    {
        $this->screener = new Screener();

        $check = new BlacklistCheck();
        $check->setBlacklist(['127.0.0.2/32']);
        $this->screener->addCheck($check);

        $this->gatekeeper = new Gatekeeper();
    }

    /**
     * @expectedException \FlameCore\Gatekeeper\AccessDeniedException
     */
    public function testPositive()
    {
        $request = Request::create('/', null, [], [], [], ['REMOTE_ADDR' => '127.0.0.2'], null);
        $this->gatekeeper->run($request, $this->screener);
    }

    public function testNegative()
    {
        try {
            $request = Request::create('/', null, [], [], [], [], null);
            $this->gatekeeper->run($request, $this->screener);
        } catch (AccessDeniedException $e) {
            $this->fail('AccessDeniedException was thrown.');
        }
    }
}
