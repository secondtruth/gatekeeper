<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Result;

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
     * Creates a AbstractResult object.
     *
     * @param string[]|string $reporting List of reporting Check classes
     */
    public function __construct($reporting = [])
    {
        $this->reporting = (array) $reporting;
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
