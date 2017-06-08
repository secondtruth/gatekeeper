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

namespace FlameCore\Gatekeeper\Check\UserAgent\Bot;

use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Check\UserAgent\BotInterface;
use FlameCore\Gatekeeper\Exceptions\StopScreeningException;
use FlameCore\Gatekeeper\Visitor;
use FlameCore\Gatekeeper\Utils;

/**
 * Class AbstractBot
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractBot implements BotInterface
{
    /**
     * List of known IPs
     *
     * @var string[]
     */
    protected $knownIps = [];

    /**
     * {@inheritdoc}
     */
    public function scan(Visitor $visitor)
    {
        if (Utils::isIPv6($visitor->getIP())) {
            return CheckInterface::RESULT_OKAY;
        }

        if (Utils::matchCIDR($visitor->getIP(), $this->knownIps)) {
            throw new StopScreeningException();
        }

        return CheckInterface::RESULT_UNSURE;
    }
}
