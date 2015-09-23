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

use FlameCore\Gatekeeper\Listing\StringList;

/**
 * Test class for StringList
 */
class StringListTest extends ListingTestCase
{
    /**
     * @var \FlameCore\Gatekeeper\Listing\StringList
     */
    protected $list;

    public function setUp()
    {
        $this->list = new StringList();
    }

    public function testMatch()
    {
        $this->list->add('foo');
        $this->list->add(['b*', '*z', '*u*']);
        $this->list->add('r:/cat/');

        $this->assertMatchesList(['foo', 'bar', 'daz', 'uuu', 'cat']);
    }

    public function testMatchLoadFile()
    {
        $this->list->loadFile(__DIR__.'/fixtures/strings.txt');

        $this->assertMatchesList(['foo', 'bar', 'daz', 'uuu', 'cat']);
    }

    public function testMatchIs()
    {
        $this->list->is('daz');
        $this->list->is(['bar', 'foo']);

        $this->assertMatchesList('foo');
    }

    public function testMatchBeginsWith()
    {
        $this->list->beginsWith('d');
        $this->list->beginsWith(['b', 'f']);

        $this->assertMatchesList('foo');
    }

    public function testMatchEndsWith()
    {
        $this->list->endsWith('z');
        $this->list->endsWith(['r', 'o']);

        $this->assertMatchesList('foo');
    }

    public function testMatchContains()
    {
        $this->list->contains('u');
        $this->list->contains(['a', 'o']);

        $this->assertMatchesList('foo');
    }

    public function testMatchRegex()
    {
        $this->list->matches('/daz/');
        $this->list->matches(['/bar/', '/foo/']);

        $this->assertMatchesList('foo');
    }
}
