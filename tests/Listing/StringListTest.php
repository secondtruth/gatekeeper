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

use Secondtruth\Gatekeeper\Listing\StringList;

/**
 * Test class for StringList
 */
class StringListTest extends ListingTestCase
{
    public function testMatch()
    {
        $list = new StringList();
        $list->add('foo');
        $list->add(['b*', '*z', '*u*']);
        $list->add('r:/cat/');

        $this->assertMatchesList($list, ['foo', 'bar', 'daz', 'uuu', 'cat']);
    }

    public function testMatchLoadFile()
    {
        $list = new StringList();
        $list->loadFile(__DIR__.'/fixtures/strings.txt');

        $this->assertMatchesList($list, ['foo', 'bar', 'daz', 'uuu', 'cat']);
    }

    public function testMatchIs()
    {
        $list = new StringList();
        $list->equal('daz');
        $list->equal(['bar', 'foo']);

        $this->assertMatchesList($list, 'foo');
    }

    public function testMatchBeginsWith()
    {
        $list = new StringList();
        $list->startsWith('d');
        $list->startsWith(['b', 'f']);

        $this->assertMatchesList($list, 'foo');
    }

    public function testMatchEndsWith()
    {
        $list = new StringList();
        $list->endsWith('z');
        $list->endsWith(['r', 'o']);

        $this->assertMatchesList($list, 'foo');
    }

    public function testMatchContains()
    {
        $list = new StringList();
        $list->contains('u');
        $list->contains(['a', 'o']);

        $this->assertMatchesList($list, 'foo');
    }

    public function testMatchRegex()
    {
        $list = new StringList();
        $list->matching('/daz/');
        $list->matching(['/bar/', '/foo/']);

        $this->assertMatchesList($list, 'foo');
    }
}
