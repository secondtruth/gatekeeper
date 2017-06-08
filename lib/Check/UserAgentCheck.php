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

use FlameCore\Gatekeeper\Check\UserAgent\BotInterface;
use FlameCore\Gatekeeper\Check\UserAgent\BrowserInterface;
use FlameCore\Gatekeeper\Check\UserAgent\UserAgentInterface;
use FlameCore\Gatekeeper\Visitor;

/**
 * Check for bad bots which pretend to be legitimate visitors.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class UserAgentCheck implements CheckInterface
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
