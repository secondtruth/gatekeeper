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

namespace FlameCore\Gatekeeper\Tests\Listing;

use FlameCore\Gatekeeper\Listing\IPList;

/**
 * Test class for IPList
 */
class IPListTest extends ListingTestCase
{
    public function testMatch()
    {
        $list = new IPList();
        $list->add('127.0.0.1');
        $list->add(['127.0.0.2', '127.0.0.3/32']);

        $this->assertMatchesList($list, ['127.0.0.1', '127.0.0.2', '127.0.0.3']);
    }

    public function testMatchLoadFile()
    {
        $list = new IPList();
        $list->loadFile(__DIR__.'/fixtures/ips.txt');

        $this->assertMatchesList($list, ['127.0.0.1', '127.0.0.2', '127.0.0.3']);
    }

    public function testGet()
    {
        $list = new IPList();

        $this->assertSame([], $list->get());

        $list->add('127.0.0.1');

        $this->assertSame(['127.0.0.1'], $list->get());
    }
}
