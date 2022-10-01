<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Check\UserAgent\Bot;

use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Check\UserAgent\BotInterface;
use Secondtruth\Gatekeeper\Check\UserAgent\ScannableUserAgentInterface;
use Secondtruth\Gatekeeper\Exceptions\StopScreeningException;
use Secondtruth\Gatekeeper\Listing\IPList;
use Secondtruth\Gatekeeper\Visitor;

/**
 * Class AbstractBot
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractBot implements BotInterface, ScannableUserAgentInterface
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
        $ip = $visitor->getIP();

        // Skip IPv6 addresses
        if ($ip->isIPv6()) {
            return CheckInterface::RESULT_OKAY;
        }

        $knownIps = new IPList($this->knownIps);
        if ($knownIps->match($ip)) {
            throw new StopScreeningException();
        }

        return CheckInterface::RESULT_UNSURE;
    }
}
