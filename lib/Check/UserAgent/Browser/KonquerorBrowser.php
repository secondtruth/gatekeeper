<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Check\UserAgent\Browser;

use Secondtruth\Gatekeeper\UserAgent;

/**
 * Class KonquerorBrowser
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class KonquerorBrowser extends AbstractBrowser
{
    /**
     * {@inheritdoc}
     */
    public function is(UserAgent $ua)
    {
        return $ua->getUserAgentString()->contains('Konqueror');
    }
}
