<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Storage;

use Secondtruth\Gatekeeper\Result\NegativeResult;
use Secondtruth\Gatekeeper\Result\PositiveResult;
use Secondtruth\Gatekeeper\Result\ResultInterface;
use Secondtruth\Gatekeeper\Visitor;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * A basic Storage that writes to a Logger.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class LogStorage implements StorageInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Creates a LogStorage object.
     *
     * @param \Psr\Log\LoggerInterface $logger The logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }

    /**
     * {@inheritdoc}
     */
    public function insert(Visitor $visitor, ResultInterface $result)
    {
        $explanation = $result->getExplanation();
        $message = $explanation['logtext'];
        $context = $visitor->toArray();

        if ($result instanceof PositiveResult) {
            $this->logPositiveResult($message, $context);
        } elseif ($result instanceof NegativeResult) {
            $this->logNegativeResult($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return -1;
    }

    /**
     * {@inheritdoc}
     */
    public function countBlocked()
    {
        return -1;
    }

    /**
     * {@inheritdoc}
     */
    public function cleanup()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function optimize()
    {
        return true;
    }

    /**
     * Logs a positive result.
     *
     * @param string $message The message to log
     * @param array $context The context values
     */
    protected function logPositiveResult($message, array $context)
    {
        $this->logger->notice($message, $context);
    }

    /**
     * Logs a negative result.
     *
     * @param string $message The message to log
     * @param array $context The context values
     */
    protected function logNegativeResult($message, array $context)
    {
        $this->logger->info($message, $context);
    }
}
