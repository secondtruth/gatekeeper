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

use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Visitor;
use Secondtruth\Gatekeeper\UserAgent;

/**
 * Class MsieBrowser
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class MsieBrowser extends AbstractBrowser
{
    /**
     * {@inheritdoc}
     */
    public function scan(Visitor $visitor)
    {
        if ($result = parent::scan($visitor)) {
            return $result;
        }

        $uastring = $visitor->getUserAgent()->getUserAgentString();
        $headers = $visitor->getRequestHeaders();

        // MSIE does NOT send "Windows ME" or "Windows XP" in the user agent
        if ($uastring->containsAny(['Windows ME', 'Windows XP', 'Windows 2000', 'Win32'], true)) {
            return 'a1084bad';
        }

        // MSIE does NOT send 'Connection: TE' but Akamai does. Bypass this test when Akamai detected.
        // The latest version of IE for Windows CE also uses 'Connection: TE'
        if (!$headers->has('Akamai-Origin-Hop') && !$uastring->contains('IEMobile', true) && preg_match('/\bTE\b/i', $headers->get('Connection'))) {
            return '2b90f772';
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * {@inheritdoc}
     */
    public function is(UserAgent $ua)
    {
        return $ua->getUserAgentString()->containsAny(['; MSIE', 'Opera']);
    }
}
