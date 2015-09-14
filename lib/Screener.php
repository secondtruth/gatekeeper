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
use FlameCore\Gatekeeper\Result\NegativeResult;
use FlameCore\Gatekeeper\Result\PositiveResult;

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
     * @var string[]
     */
    protected $whitelist = array();

    /**
     * The list of trusted user agents
     *
     * @var \FlameCore\Gatekeeper\Listing
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
     * @param array $whitelist The IP whitelist
     * @param \FlameCore\Gatekeeper\Listing $trustedUserAgents The list of trusted user agents
     */
    public function __construct(array $whitelist = [], Listing $trustedUserAgents = null)
    {
        $this->setWhitelist($whitelist);
        $this->setTrustedUserAgents($trustedUserAgents ?: new Listing());
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
     * Registers the given check.
     *
     * @param \FlameCore\Gatekeeper\Check\CheckInterface $check The check to register
     */
    public function addCheck(CheckInterface $check)
    {
        $this->checks[] = $check;
    }

    /**
     * Returns the IP whitelist.
     *
     * @return string[]
     */
    public function getWhitelist()
    {
        return $this->whitelist;
    }

    /**
     * Sets the IP whitelist.
     *
     * @param string[] $whitelist The IP whitelist
     */
    public function setWhitelist(array $whitelist)
    {
        $this->whitelist = array_filter($whitelist);
    }

    /**
     * Loads the IP whitelist from the given file.
     *
     * @param string $file The name of the whitelist file
     */
    public function loadWhitelist($file)
    {
        $whitelist = file($file, FILE_SKIP_EMPTY_LINES);

        $this->setWhitelist($whitelist);
    }

    /**
     * Returns the list of trusted user agents.
     *
     * @return \FlameCore\Gatekeeper\Listing
     */
    public function getTrustedUserAgents()
    {
        return $this->trustedUserAgents;
    }

    /**
     * Sets the list of trusted user agents.
     *
     * @param \FlameCore\Gatekeeper\Listing $trustedUserAgents The list of trusted user agents
     */
    public function setTrustedUserAgents(Listing $trustedUserAgents)
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
        if (Utils::matchCIDR($visitor->getIP(), $this->whitelist)) {
            return true;
        }

        $uastring = $visitor->getUserAgent()->getUserAgentString();
        if ($this->trustedUserAgents->match($uastring)) {
            return true;
        }

        return false;
    }
}
