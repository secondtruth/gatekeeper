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

use PHPUnit\Framework\TestCase;
use Secondtruth\Gatekeeper\Listing\ListInterface;

/**
 * Test case for Listing classes
 */
class ListingTestCase extends TestCase
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
