<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper;

use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Listing\IPList;
use Secondtruth\Gatekeeper\Result\NegativeResult;
use Secondtruth\Gatekeeper\Result\PositiveResult;
use Secondtruth\Gatekeeper\Listing\StringList;
use Secondtruth\Gatekeeper\Exceptions\StopScreeningException;

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
     * @var \Secondtruth\Gatekeeper\Check\CheckInterface[]
     */
    protected $checks = array();

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
     * {@inheritdoc}
     */
    public function screenVisitor(Visitor $visitor)
    {
        $result = $this->doScreening($visitor);

        if ($result !== false) {
            return new PositiveResult($this->reporting, is_string($result) ? $result : null);
        } else {
            return new NegativeResult($this->reporting);
        }
    }

    /**
     * Returns a list of registered checks.
     *
     * @return \Secondtruth\Gatekeeper\Check\CheckInterface[]
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
     * @return \Secondtruth\Gatekeeper\Check\CheckInterface
     */
    public function getCheck($class)
    {
        return isset($this->checks[$class]) ? $this->checks[$class] : null;
    }

    /**
     * Registers the given check.
     *
     * @param \Secondtruth\Gatekeeper\Check\CheckInterface $check The check to register
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
     * @param \Secondtruth\Gatekeeper\Visitor $visitor The visitor
     * @return string|bool The abstract result
     */
    protected function doScreening(Visitor $visitor)
    {
        $rating = 0;

        foreach ($this->checks as $check) {
            if (!$check->isResponsibleFor($visitor)) {
                continue;
            }

            try {
                $result = $check->checkVisitor($visitor);
            } catch (StopScreeningException $e) {
                return false;
            }

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
}
