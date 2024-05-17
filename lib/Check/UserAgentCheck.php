<?php
/*
 * Gatekeeper
 * Copyright (C) 2024 Christian Neff
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
     * Constructor.
     *
     * @param UserAgentInterface[] $userAgents The UserAgent objects to use
     *
     * @throws \InvalidArgumentException If an unsupported type is given.
     */
    public function __construct(array $userAgents = [])
    {
        foreach ($userAgents as $userAgent) {
            if (!$userAgent instanceof UserAgentInterface) {
                throw new \InvalidArgumentException('Unsupported type');
            }

            $this->addUserAgent($userAgent);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        /** @var UserAgentInterface[] $userAgents */
        $userAgents = $this->getUserAgents();

        foreach ($userAgents as $userAgent) {
            if ($userAgent->is($visitor->getUserAgent())) {
                return $userAgent->scan($visitor);
            }
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Adds the given user agent.
     *
     * @param UserAgentInterface $userAgent The UserAgent object to add
     *
     * @throws \InvalidArgumentException If an unsupported user agent type is given.
     */
    public function addUserAgent(UserAgentInterface $userAgent)
    {
        if ($userAgent instanceof BrowserInterface) {
            $this->browsers[] = $userAgent;
        } elseif ($userAgent instanceof BotInterface) {
            $this->bots[] = $userAgent;
        } else {
            throw new \InvalidArgumentException('Unsupported user agent type');
        }
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
     * Gets the list of user agents.
     *
     * @return UserAgentInterface[]
     */
    public function getUserAgents()
    {
        return array_merge($this->bots, $this->browsers);
    }

    /**
     * Gets the list of browsers.
     *
     * @return BrowserInterface[]
     */
    public function getBrowsers()
    {
        return $this->browsers;
    }

    /**
     * Gets the list of bots.
     *
     * @return BotInterface[]
     */
    public function getBots()
    {
        return $this->bots;
    }
}
