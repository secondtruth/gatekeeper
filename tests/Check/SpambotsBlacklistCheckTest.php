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

namespace FlameCore\Gatekeeper\Tests\Check;

use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Check\SpambotsBlacklistCheck;

/**
 * Test class for SpambotsBlacklistCheck
 */
class SpambotsBlacklistCheckTest extends CheckTestCase
{
    protected function setUp()
    {
        $this->check = new SpambotsBlacklistCheck();
    }

    public function testCheckPositiveSpambotNamesBeginning()
    {
        $result = $this->runTestCheck('/', null, [], [], [], ['HTTP_USER_AGENT' => '8484 Boston Project']);

        $this->assertEquals('17f4e8c8', $result);
    }

    public function testCheckPositiveSpambotNamesAnywhere()
    {
        $result = $this->runTestCheck('/', null, [], [], [], ['HTTP_USER_AGENT' => 'foo bar <script></script>']);

        $this->assertEquals('17f4e8c8', $result);
    }

    public function testCheckPositiveSpambotNamesRegex()
    {
        $result = $this->runTestCheck('/', null, [], [], [], ['HTTP_USER_AGENT' => 'MSIE 2']);

        $this->assertEquals('17f4e8c8', $result);
    }

    public function testCheckPositiveSpambotUris()
    {
        $result = $this->runTestCheck('/?0x31303235343830303536');

        $this->assertEquals('96c0bd29', $result);
    }

    public function testCheckNegative()
    {
        $result = $this->runTestCheck();

        $this->assertEquals(CheckInterface::RESULT_OKAY, $result);
    }
}
