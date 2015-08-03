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

namespace FlameCore\Gatekeeper\Screener;

use FlameCore\Gatekeeper\Screener;
use FlameCore\Gatekeeper\Visitor;

/**
 * Base class for custom screeners
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class CustomScreener extends Screener
{
    /**
     * Is the screener set up?
     *
     * @var bool
     */
    protected $isSetUp = false;

    /**
     * {@inheritdoc}
     */
    public function screenVisitor(Visitor $visitor)
    {
        if (!$this->isSetUp) {
            $this->setup();
            $this->isSetUp = true;
        }

        return parent::screenVisitor($visitor);
    }

    /**
     * Sets up the screener. This method is intended mainly for registering the checks.
     */
    abstract protected function setup();
}
