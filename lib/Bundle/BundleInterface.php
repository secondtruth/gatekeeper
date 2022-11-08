<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Bundle;

use Secondtruth\Gatekeeper\Gatekeeper;
use Secondtruth\Gatekeeper\Screener;

/**
 * Bundles are predefined sets of Gatekeeper and Screener configurations.
 *
 * @author Christian Neff <christian.neff@gmail.com>
 */
interface BundleInterface
{
    /**
     * Configures a Gatekeeper object.
     *
     * @param Gatekeeper $gatekeeper The Gatekeeper object to configure
     */
    public function configure(Gatekeeper $gatekeeper): void;

    /**
     * Creates the Screener object.
     */
    public function createScreener(): Screener;
}
