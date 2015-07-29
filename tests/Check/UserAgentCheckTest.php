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

namespace FlameCore\Gatekeeper\Tests\Check;

use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Check\UserAgentCheck;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for UserAgentCheck
 */
class UserAgentCheckTest extends CheckTestCase
{
    protected function setUp()
    {
        $this->check = new UserAgentCheck();
    }

    public function testCheckPositiveMsie()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'
        );

        $this->doTestCheckPositiveBrowser($browser);
    }

    public function testCheckPositiveKonqueror()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; Konqueror/4.4; Linux) KHTML/4.4.1 (like Gecko) Fedora/4.4.1-1.fc12'
        );

        $this->doTestCheckPositiveBrowser($browser);
    }

    public function testCheckPositiveLynx()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'Lynx/2.8.6rel.5 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/1.0.0a'
        );

        $this->doTestCheckPositiveBrowser($browser);
    }

    public function testCheckPositiveMozilla()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36'
        );

        $this->doTestCheckPositiveBrowser($browser);
    }

    public function testCheckPositiveOpera()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'Opera/9.80 (Windows NT 6.1; U; es-ES) Presto/2.9.181 Version/12.00'
        );

        $this->doTestCheckPositiveBrowser($browser);
    }

    public function testCheckPositiveSafari()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_2; fr-fr) AppleWebKit/531.21.8 (KHTML, like Gecko) Version/4.0.4 Safari/531.21.10'
        );

        $this->doTestCheckPositiveBrowser($browser);
    }

    public function testCheckPositiveGoogleBot()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
        );

        $this->doTestCheckPositiveBot($browser);
    }

    public function testCheckPositiveMsnBot()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'msnbot/2.0b (+http://search.msn.com/msnbot.htm)'
        );

        $this->doTestCheckPositiveBot($browser);
    }

    public function testCheckPositiveYahooBot()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)'
        );

        $this->doTestCheckPositiveBot($browser);
    }

    public function testCheckPositiveBaiduBot()
    {
        $browser = array(
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)'
        );

        $this->doTestCheckPositiveBot($browser);
    }

    public function testCheckNegative()
    {
        $result = $this->runTestCheck();

        $this->assertEquals(CheckInterface::RESULT_OKAY, $result);
    }

    protected function doTestCheckPositiveBrowser(array $browser)
    {
        $request = Request::create('/', null, [], [], [], $browser);
        $request->headers->remove('Accept');

        $result = $this->runCustomTestCheck($request);

        $this->assertEquals('17566707', $result);
    }

    protected function doTestCheckPositiveBot(array $browser)
    {
        $result = $this->runTestCheck(null, null, [], [], [], $browser);

        $this->assertEquals(CheckInterface::RESULT_UNSURE, $result);
    }
}
