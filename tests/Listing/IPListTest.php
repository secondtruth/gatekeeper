<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Tests\Listing;

use Secondtruth\Gatekeeper\Listing\IPList;

/**
 * Test class for IPList
 */
class IPListTest extends ListingTestCase
{
    public function testBasic()
    {
        $list1 = new IPList('127.0.0.1');
        $list1->add('127.0.0.2');
        $this->assertSame(['127.0.0.1', '127.0.0.2'], $list1->get());

        $list2 = new IPList(['127.0.0.1', '127.0.0.2/32']);
        $list2->add(['127.0.0.3', '127.0.0.4/32']);
        $this->assertSame(['127.0.0.1', '127.0.0.2/32', '127.0.0.3', '127.0.0.4/32'], $list2->get());

        $list2->set(['127.0.0.5', '127.0.0.6/32']);
        $this->assertSame(['127.0.0.5', '127.0.0.6/32'], $list2->get());

        $list2->clear();
        $this->assertSame([], $list2->get());
    }

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
}
