<?php
/**
 * FlameCore Gatekeeper
 * Copyright (C) 2015 IceFlame.net
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE
 * FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER
 * IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING
 * OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 *
 * @package  FlameCore\Gatekeeper
 * @version  0.1-dev
 * @link     http://www.flamecore.org
 * @license  http://opensource.org/licenses/ISC ISC License
 */

namespace FlameCore\Gatekeeper\Storage;

use FlameCore\Gatekeeper\Result\NegativeResult;
use FlameCore\Gatekeeper\Result\PositiveResult;
use FlameCore\Gatekeeper\Result\ResultInterface;
use FlameCore\Gatekeeper\Visitor;
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
