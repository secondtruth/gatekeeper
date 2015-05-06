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

namespace FlameCore\Gatekeeper;

use FlameCore\Gatekeeper\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Gatekeeper
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Gatekeeper
{
    /**
     * @var \FlameCore\Gatekeeper\Storage\StorageInterface
     */
    protected $storage;

    /**
     * @var \FlameCore\Gatekeeper\Visitor
     */
    protected $visitor;

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @param array $settings
     * @param \FlameCore\Gatekeeper\Storage\StorageInterface $storage
     */
    public function __construct(array $settings = [], StorageInterface $storage = null)
    {
        $defaults = array(
            'block_message' => 'Your request has been blocked.'
        );

        $this->settings = array_replace($defaults, $settings);

        $this->storage = $storage;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \FlameCore\Gatekeeper\ScreenerInterface $screener
     */
    public function run(Request $request, ScreenerInterface $screener)
    {
        $this->visitor = new Visitor($request);

        $result = $screener->screenVisitor($this->visitor);

        if ($this->storage) {
            $this->storage->insert($request, $result !== false ? $result : '00000000');
        }

        if ($result !== false) {
            $this->blockRequest();
        } else {
            $this->approveRequest();
        }
    }

    /**
     * Perform actions for bad requests.
     */
    public function blockRequest()
    {
        throw new AccessDeniedException($this->settings['block_message']);
    }

    /**
     * Perform actions for good requests.
     */
    public function approveRequest()
    {
        // do nothing
    }
}
