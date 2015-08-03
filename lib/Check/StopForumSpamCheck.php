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

namespace FlameCore\Gatekeeper\Check;

use FlameCore\Gatekeeper\Visitor;

/**
 * Query the StopForumSpam API and block visitors with matching IPs.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class StopForumSpamCheck implements CheckInterface
{
    const CHECK_URL = 'http://www.stopforumspam.com/api';

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        $params = http_build_query([
            'ip' => $visitor->getIP()
        ]);

        $response = @simplexml_load_file(self::CHECK_URL.'?'.$params);

        if ($response === false) {
            return CheckInterface::RESULT_OKAY;
        }

        foreach ($response->appears as $appears) {
            if ($appears == 'yes') {
                return CheckInterface::RESULT_BLOCK;
            }
        }

        return CheckInterface::RESULT_OKAY;
    }
}
