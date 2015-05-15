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

use FlameCore\Gatekeeper\Result\Explainer;
use FlameCore\Gatekeeper\Result\PositiveResult;
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
     * @var \FlameCore\Gatekeeper\Result\Explainer
     */
    protected $explainer;

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
            'block_message' => "<p>Your request has been blocked.</p>\n<p>{explanation}</p>"
        );

        $this->settings = array_replace($defaults, $settings);

        $this->storage = $storage;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param $setting
     * @param $value
     */
    public function setSetting($setting, $value)
    {
        $this->settings[$setting] = $value;
    }

    /**
     * @return \FlameCore\Gatekeeper\Storage\StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param \FlameCore\Gatekeeper\Storage\StorageInterface $storage
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return \FlameCore\Gatekeeper\Result\Explainer
     */
    public function getExplainer()
    {
        return $this->explainer;
    }

    /**
     * @param \FlameCore\Gatekeeper\Result\Explainer $explainer
     */
    public function setExplainer($explainer)
    {
        $this->explainer = $explainer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \FlameCore\Gatekeeper\ScreenerInterface $screener
     */
    public function run(Request $request, ScreenerInterface $screener)
    {
        $this->visitor = new Visitor($request);

        $result = $screener->screenVisitor($this->visitor);

        if ($result instanceof PositiveResult) {
            $explainer = $this->explainer ?: new Explainer();

            $explanation = $explainer->explain($result);
            $result->setExplanation($explanation);
        }

        if ($this->storage) {
            $this->storage->insert($this->visitor, $result);
        }

        if ($result instanceof PositiveResult) {
            $this->blockRequest($result);
        } else {
            $this->approveRequest();
        }
    }

    /**
     * Perform actions for bad requests.
     *
     * @param \FlameCore\Gatekeeper\Result\PositiveResult $result
     * @throws \FlameCore\Gatekeeper\AccessDeniedException
     */
    protected function blockRequest(PositiveResult $result)
    {
        $this->penalize($result);

        $explanation = $result->getExplanation();
        $message = $this->interpolate($this->settings['block_message'], $explanation);
        throw new AccessDeniedException($message, $explanation['response']);
    }

    /**
     * Perform actions for good requests.
     */
    protected function approveRequest()
    {
        // do nothing
    }

    /**
     * Penalizes blocked visitors.
     *
     * @param \FlameCore\Gatekeeper\Result\PositiveResult $result
     */
    protected function penalize(PositiveResult $result)
    {
        // reserved for future use, maybe for reporting to stopforumspam.com or so
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message The message
     * @param array $context The context values (optional)
     * @return string
     */
    protected function interpolate($message, array $context = [])
    {
        $replace = array();
        foreach ($context as $key => $value) {
            $replace['{'.$key.'}'] = $value;
        }

        return strtr($message, $replace);
    }
}
