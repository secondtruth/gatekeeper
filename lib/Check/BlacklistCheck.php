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

namespace FlameCore\Gatekeeper\Check;

use FlameCore\Gatekeeper\Visitor;

/**
 * Class BlacklistCheck
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class BlacklistCheck implements CheckInterface
{
    /**
     * @var array
     */
    protected $blacklist = array();

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        if (Utils::matchCIDR($visitor->getIP(), $this->blacklist)) {
            return true;
        }
    }

    /**
     * @return array
     */
    public function getBlacklist()
    {
        return $this->blacklist;
    }

    /**
     * @param array $blacklist
     */
    public function setBlacklist(array $blacklist)
    {
        $this->blacklist = array_filter($blacklist);
    }

    /**
     * @param string $file
     */
    public function loadBlacklist($file)
    {
        $whitelist = file($file, FILE_SKIP_EMPTY_LINES);

        $this->setBlacklist($whitelist);
    }
}
