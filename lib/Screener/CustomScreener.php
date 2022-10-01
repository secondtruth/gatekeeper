<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Screener;

use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Screener;
use Secondtruth\Gatekeeper\Visitor;

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
     * {@inheritdoc}
     *
     * @throws \LogicException when adding a check after the screener was set up, which is not allowed.
     */
    public function addCheck(CheckInterface $check)
    {
        if ($this->isSetUp) {
            throw new \LogicException('Adding checks is not allowed after the screener was set up.');
        }
        
        parent::addCheck($check);
    }

    /**
     * Sets up the screener. This method is intended mainly for registering the checks.
     */
    abstract protected function setup();
}
