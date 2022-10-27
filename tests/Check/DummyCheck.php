<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Tests\Check;

use Secondtruth\Gatekeeper\Check\AbstractCheck;
use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Visitor;

/**
 * The DummyCheck class.
 *
 * @author Christian Neff <christian.neff@gmail.com>
 */
class DummyCheck extends AbstractCheck
{
    /**
     * @inheritdoc
     */
    public function checkVisitor(Visitor $visitor)
    {
        if ($visitor->getRequestHeaders()->get('X-Gatekeeper-Block') === 'true') {
            return '00000000';
        }

        return CheckInterface::RESULT_OKAY;
    }
}
