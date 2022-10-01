<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Check;

use Secondtruth\Gatekeeper\Check\UserAgent\BotInterface;
use Secondtruth\Gatekeeper\Check\UserAgent\BrowserInterface;
use Secondtruth\Gatekeeper\Check\UserAgent\UserAgentInterface;
use Secondtruth\Gatekeeper\Visitor;

/**
 * Check for bad bots which pretend to be legitimate visitors.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UserAgentCheck extends AbstractCheck
{
    /**
     * @var BrowserInterface[]
     */
    protected $browsers = [];

    /**
     * @var BotInterface[]
     */
    protected $bots = [];

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        /** @var UserAgentInterface[] $userAgents */
        $userAgents = array_merge($this->bots, $this->browsers);

        foreach ($userAgents as $userAgent) {
            if ($userAgent->is($visitor->getUserAgent())) {
                return $userAgent->scan($visitor);
            }
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Adds the given browser.
     *
     * @param BrowserInterface $userAgent The UserAgent object
     */
    public function addBrowser(BrowserInterface $userAgent)
    {
        $this->browsers[] = $userAgent;
    }

    /**
     * Adds the given bot.
     *
     * @param BotInterface $userAgent The UserAgent object
     */
    public function addBot(BotInterface $userAgent)
    {
        $this->bots[] = $userAgent;
    }

    /**
     * Gets list of browsers.
     *
     * @return BrowserInterface[]
     */
    public function getBrowsers()
    {
        return $this->browsers;
    }

    /**
     * Gets list of bots.
     *
     * @return BotInterface[]
     */
    public function getBots()
    {
        return $this->bots;
    }
}
