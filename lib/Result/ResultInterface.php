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
 * This interface represents a result.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface ResultInterface
{
    /**
     * Gets the list of reporting Check classes.
     *
     * @return string[]
     */
    public function getReportingClasses();

    /**
     * Gets the explanation.
     *
     * @return array
     */
    public function getExplanation();

    /**
     * Sets the explanation.
     *
     * @param array $explanation The explanation
     */
    public function setExplanation(array $explanation);
}
