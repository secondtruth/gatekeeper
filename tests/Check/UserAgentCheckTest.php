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

use Secondtruth\Gatekeeper\Exceptions\StopScreeningException;
use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Check\UserAgent\Bot\BaiduBot;
use Secondtruth\Gatekeeper\Check\UserAgent\Bot\GoogleBot;
use Secondtruth\Gatekeeper\Check\UserAgent\Bot\MsnBot;
use Secondtruth\Gatekeeper\Check\UserAgent\Bot\YahooBot;
use Secondtruth\Gatekeeper\Check\UserAgent\Browser\KonquerorBrowser;
use Secondtruth\Gatekeeper\Check\UserAgent\Browser\LynxBrowser;
use Secondtruth\Gatekeeper\Check\UserAgent\Browser\MozillaBrowser;
use Secondtruth\Gatekeeper\Check\UserAgent\Browser\MsieBrowser;
use Secondtruth\Gatekeeper\Check\UserAgent\Browser\OperaBrowser;
use Secondtruth\Gatekeeper\Check\UserAgent\Browser\SafariBrowser;
use Secondtruth\Gatekeeper\Check\UserAgentCheck;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test class for UserAgentCheck
 */
class UserAgentCheckTest extends CheckTestCase
{
    const UA_MSIE = 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko';
    const UA_KONQUEROR = 'Mozilla/5.0 (compatible; Konqueror/4.4; Linux) KHTML/4.4.1 (like Gecko) Fedora/4.4.1-1.fc12';
    const UA_LYNX = 'Lynx/2.8.6rel.5 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/1.0.0a';
    const UA_MOZILLA = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36';
    const UA_OPERA = 'Opera/9.80 (Windows NT 6.1; U; es-ES) Presto/2.9.181 Version/12.00';
    const UA_SAFARI = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_2; fr-fr) AppleWebKit/531.21.8 (KHTML, like Gecko) Version/4.0.4 Safari/531.21.10';
    const UA_GOOGLEBOT = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
    const UA_MSNBOT = 'msnbot/2.0b (+http://search.msn.com/msnbot.htm)';
    const UA_YAHOOBOT = 'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)';
    const UA_BAIDUBOT = 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)';

    protected function setUp(): void
    {
        $check = new UserAgentCheck();

        $check->addBrowser(new MsieBrowser());
        $check->addBrowser(new OperaBrowser());
        $check->addBrowser(new KonquerorBrowser());
        $check->addBrowser(new SafariBrowser());
        $check->addBrowser(new LynxBrowser());
        $check->addBrowser(new MozillaBrowser());
        $check->addBot(new MsnBot());
        $check->addBot(new GoogleBot());
        $check->addBot(new YahooBot());
        $check->addBot(new BaiduBot());

        $this->check = $check;
    }

    public function testCheckPositiveMsie()
    {
        $this->doTestCheckPositiveBrowser(['HTTP_USER_AGENT' => self::UA_MSIE]);
    }

    public function testCheckPositiveKonqueror()
    {
        $this->doTestCheckPositiveBrowser(['HTTP_USER_AGENT' => self::UA_KONQUEROR]);
    }

    public function testCheckPositiveLynx()
    {
        $this->doTestCheckPositiveBrowser(['HTTP_USER_AGENT' => self::UA_LYNX]);
    }

    public function testCheckPositiveMozilla()
    {
        $this->doTestCheckPositiveBrowser(['HTTP_USER_AGENT' => self::UA_MOZILLA]);
    }

    public function testCheckPositiveOpera()
    {
        $this->doTestCheckPositiveBrowser(['HTTP_USER_AGENT' => self::UA_OPERA]);
    }

    public function testCheckPositiveSafari()
    {
        $this->doTestCheckPositiveBrowser(['HTTP_USER_AGENT' => self::UA_SAFARI]);
    }

    public function testCheckPositiveGoogleBot()
    {
        $this->doTestCheckPositiveBot(['HTTP_USER_AGENT' => self::UA_GOOGLEBOT]);
    }

    public function testCheckPositiveMsnBot()
    {
        $this->doTestCheckPositiveBot(['HTTP_USER_AGENT' => self::UA_MSNBOT]);
    }

    public function testCheckPositiveYahooBot()
    {
        $this->doTestCheckPositiveBot(['HTTP_USER_AGENT' => self::UA_YAHOOBOT]);
    }

    public function testCheckPositiveBaiduBot()
    {
        $this->doTestCheckPositiveBot(['HTTP_USER_AGENT' => self::UA_BAIDUBOT]);
    }

    public function testCheckStopGoogleBot()
    {
        $this->expectException(StopScreeningException::class);

        $this->runTestCheck(null, null, [], [], [], ['HTTP_USER_AGENT' => self::UA_GOOGLEBOT, 'REMOTE_ADDR' => '66.249.64.0']);
    }

    public function testCheckStopMsnBot()
    {
        $this->expectException(StopScreeningException::class);

        $this->runTestCheck(null, null, [], [], [], ['HTTP_USER_AGENT' => self::UA_MSNBOT, 'REMOTE_ADDR' => '207.46.0.0']);
    }

    public function testCheckStopYahooBot()
    {
        $this->expectException(StopScreeningException::class);

        $this->runTestCheck(null, null, [], [], [], ['HTTP_USER_AGENT' => self::UA_YAHOOBOT, 'REMOTE_ADDR' => '202.160.176.0']);
    }

    public function testCheckStopBaiduBot()
    {
        $this->expectException(StopScreeningException::class);

        $this->runTestCheck(null, null, [], [], [], ['HTTP_USER_AGENT' => self::UA_BAIDUBOT, 'REMOTE_ADDR' => '119.63.192.0']);
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
