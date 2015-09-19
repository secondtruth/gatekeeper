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

namespace FlameCore\Gatekeeper;

use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Listing\IPList;
use FlameCore\Gatekeeper\Result\NegativeResult;
use FlameCore\Gatekeeper\Result\PositiveResult;
use FlameCore\Gatekeeper\Listing\StringList;

/**
 * Class Screener
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Screener implements ScreenerInterface
{
    /**
     * The registered checks
     *
     * @var \FlameCore\Gatekeeper\Check\CheckInterface[]
     */
    protected $checks = array();

    /**
     * The IP whitelist
     *
     * @var \FlameCore\Gatekeeper\Listing\IPList
     */
    protected $whitelist;

    /**
     * The list of trusted user agents
     *
     * @var \FlameCore\Gatekeeper\Listing\StringList
     */
    protected $trustedUserAgents;

    /**
     * The rating threshold
     *
     * @var int
     */
    protected $ratingThreshold = 2;

    /**
     * The list of reporting classes
     *
     * @var string[]
     */
    private $reporting = array();

    /**
     * Creates a Screener object.
     *
     * @param \FlameCore\Gatekeeper\Listing\IPList $whitelist The IP whitelist
     * @param \FlameCore\Gatekeeper\Listing\StringList $trustedUserAgents The list of trusted user agents
     */
    public function __construct(IPList $whitelist = null, StringList $trustedUserAgents = null)
    {
        $this->setWhitelist($whitelist ?: new IPList());
        $this->setTrustedUserAgents($trustedUserAgents ?: new StringList());
    }

    /**
     * {@inheritdoc}
     */
    public function screenVisitor(Visitor $visitor)
    {
        if ($this->isWhitelisted($visitor)) {
            return new NegativeResult(__CLASS__);
        }

        $result = $this->doScreening($visitor);

        if ($result !== false) {
            return new PositiveResult($this->reporting, is_string($result) ? $result : null);
        } else {
            return new NegativeResult();
        }
    }

    /**
     * Returns a list of registered checks.
     *
     * @return \FlameCore\Gatekeeper\Check\CheckInterface[]
     */
    public function getChecks()
    {
        return $this->checks;
    }

    /**
     * Returns whether the given check is registered.
     *
     * @param string $class The class name of the check
     * @return bool
     */
    public function hasCheck($class)
    {
        return isset($this->checks[$class]);
    }

    /**
     * Returns a list of registered checks.
     *
     * @param string $class The class name of the check
     * @return \FlameCore\Gatekeeper\Check\CheckInterface
     */
    public function getCheck($class)
    {
        return isset($this->checks[$class]) ? $this->checks[$class] : null;
    }

    /**
     * Registers the given check.
     *
     * @param \FlameCore\Gatekeeper\Check\CheckInterface $check The check to register
     */
    public function addCheck(CheckInterface $check)
    {
        $class = get_class($check);

        if (isset($this->checks[$class])) {
            throw new \LogicException(sprintf('The check %s is already added to the screener.', $class));
        }

        $this->checks[$class] = $check;
    }

    /**
     * Returns the IP whitelist.
     *
     * @return \FlameCore\Gatekeeper\Listing\IPList
     */
    public function getWhitelist()
    {
        return $this->whitelist;
    }

    /**
     * Sets the IP whitelist.
     *
     * @param \FlameCore\Gatekeeper\Listing\IPList $whitelist The IP whitelist
     */
    public function setWhitelist(IPList $whitelist)
    {
        $this->whitelist = $whitelist;
    }

    /**
     * Returns the list of trusted user agents.
     *
     * @return \FlameCore\Gatekeeper\Listing\StringList
     */
    public function getTrustedUserAgents()
    {
        return $this->trustedUserAgents;
    }

    /**
     * Sets the list of trusted user agents.
     *
     * @param \FlameCore\Gatekeeper\Listing\StringList $trustedUserAgents The list of trusted user agents
     */
    public function setTrustedUserAgents(StringList $trustedUserAgents)
    {
        $this->trustedUserAgents = $trustedUserAgents;
    }

    /**
     * Returns the rating threshold.
     *
     * @return int
     */
    public function getRatingThreshold()
    {
        return $this->ratingThreshold;
    }

    /**
     * Sets the rating threshold.
     *
     * @param int $ratingThreshold The rating threshold
     */
    public function setRatingThreshold($ratingThreshold)
    {
        $ratingThreshold = (int) $ratingThreshold;

        if ($ratingThreshold < 1) {
            throw new \InvalidArgumentException('The screener\'s rating threshold value must be at least 1.');
        }

        $this->ratingThreshold = $ratingThreshold;
    }

    /**
     * Performs the real screening.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor
     * @return string|bool The abstract result
     */
    protected function doScreening(Visitor $visitor)
    {
        $rating = 0;

        foreach ($this->checks as $check) {
            $result = $check->checkVisitor($visitor);

            if ($result !== CheckInterface::RESULT_OKAY) {
                $this->reporting[] = get_class($check);

                if ($result === CheckInterface::RESULT_UNSURE) {
                    if (++$rating == $this->ratingThreshold) {
                        return true;
                    }
                } else {
                    return $result;
                }
            }
        }

        return false;
    }

    /**
     * Checks if the visitor is whitelisted.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor
     * @return bool
     */
    protected function isWhitelisted(Visitor $visitor)
    {
        if ($this->whitelist->match($visitor->getIP())) {
            return true;
        }

        $uastring = $visitor->getUserAgent()->getUserAgentString();
        if ($this->trustedUserAgents->match($uastring)) {
            return true;
        }

        return false;
    }
}
