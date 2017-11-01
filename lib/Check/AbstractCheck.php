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

namespace FlameCore\Gatekeeper\Check;

use FlameCore\Gatekeeper\Visitor;

/**
 * Class AbstractCheck
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractCheck implements CheckInterface
{
    /**
     * {@inheritdoc}
     */
    public function isResponsibleFor(Visitor $visitor)
    {
        return true;
    }
}
