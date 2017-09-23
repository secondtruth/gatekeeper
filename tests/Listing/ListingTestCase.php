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

use FlameCore\Gatekeeper\Listing\ListInterface;

/**
 * Test case for Listing classes
 */
class ListingTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param ListInterface $list
     * @param string|string[] $values
     */
    public function assertMatchesList($list, $values)
    {
        foreach ((array) $values as $value) {
            $this->assertTrue($list->match($value));
        }
    }
}
