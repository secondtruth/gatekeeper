<?php
/**
 * Gatekeeper Library
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
 * @license  ISC License <http://opensource.org/licenses/ISC>
 */

namespace FlameCore\Gatekeeper\Result;

/**
 * The base class for results
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractResult implements ResultInterface
{
    /**
     * List of reporting Check classes
     *
     * @var string[]
     */
    protected $reporting = array();

    /**
     * The explanation
     *
     * @var array
     */
    protected $explanation = array();

    /**
     * Creates a PositiveResult object.
     *
     * @param string[] $reporting List of reporting Check classes
     */
    public function __construct(array $reporting = [])
    {
        $this->reporting = $reporting;
    }

    /**
     * {@inheritdoc}
     */
    public function getReportingClasses()
    {
        return $this->reporting;
    }

    /**
     * {@inheritdoc}
     */
    public function getExplanation()
    {
        return $this->explanation;
    }

    /**
     * {@inheritdoc}
     */
    public function setExplanation(array $explanation)
    {
        $this->explanation = $explanation;
    }
}
