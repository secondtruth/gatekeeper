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

namespace FlameCore\Gatekeeper\Listing;

/**
 * Interface ListInterface
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface ListInterface
{
    /**
     * Checks if the given value matches the list.
     *
     * @param string $value The value to test
     * @return bool Returns TRUE if the value matches the list, FALSE otherwise.
     */
    public function match($value);
}
