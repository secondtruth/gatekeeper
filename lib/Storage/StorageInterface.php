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

namespace FlameCore\Gatekeeper\Storage;

use FlameCore\Gatekeeper\Result\ResultInterface;
use FlameCore\Gatekeeper\Visitor;

/**
 * Interface StorageInterface
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface StorageInterface extends \Countable
{
    /**
     * Inserts a result into the storage.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor
     * @param \FlameCore\Gatekeeper\Result\ResultInterface $result The result
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function insert(Visitor $visitor, ResultInterface $result);

    /**
     * Returns the number of entries in the storage.
     *
     * @return int
     */
    public function count();

    /**
     * Returns the number of blocked visitors.
     *
     * @return int
     */
    public function countBlocked();

    /**
     * Cleans up the storage.
     *
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function cleanup();

    /**
     * Optimizes the storage.
     *
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function optimize();
}
