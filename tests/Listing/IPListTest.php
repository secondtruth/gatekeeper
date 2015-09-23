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
    /**
     * @var \FlameCore\Gatekeeper\Listing\IPList
     */
    protected $list;

    public function setUp()
    {
        $this->list = new IPList();
    }

    public function testMatch()
    {
        $this->list->add('127.0.0.1');
        $this->list->add(['127.0.0.2', '127.0.0.3/32']);

        $this->assertMatchesList(['127.0.0.1', '127.0.0.2', '127.0.0.3']);
    }

    public function testMatchLoadFile()
    {
        $this->list->loadFile(__DIR__.'/fixtures/ips.txt');

        $this->assertMatchesList(['127.0.0.1', '127.0.0.2', '127.0.0.3']);
    }

    public function testGet()
    {
        $this->assertSame([], $this->list->get());

        $this->list->add('127.0.0.1');

        $this->assertSame(['127.0.0.1'], $this->list->get());
    }
}
