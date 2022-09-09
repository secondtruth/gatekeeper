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

namespace FlameCore\Gatekeeper;

use FlameCore\Gatekeeper\Listing\IPList;
use FlameCore\Gatekeeper\Listing\StringList;
use FlameCore\Gatekeeper\Result\Explainer;
use FlameCore\Gatekeeper\Result\NegativeResult;
use FlameCore\Gatekeeper\Result\PositiveResult;
use FlameCore\Gatekeeper\Storage\StorageInterface;
use FlameCore\Gatekeeper\Exceptions\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Gatekeeper
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Gatekeeper
{
    /**
     * The storage to use
     *
     * @var \FlameCore\Gatekeeper\Storage\StorageInterface
     */
    protected $storage;

    /**
     * The explainer to use
     *
     * @var \FlameCore\Gatekeeper\Result\Explainer
     */
    protected $explainer;

    /**
     * The IP whitelist
     *
     * @var \FlameCore\Gatekeeper\Listing\IPList
     */
    protected $whitelist;

    /**
     * The list of trusted user agents
     *
     * @var \FlameCore\Gatekeeper\Listing\StringList
     */
    protected $trustedUserAgents;

    /**
     * The current visitor
     *
     * @var \FlameCore\Gatekeeper\Visitor
     */
    protected $visitor;

    /**
     * List of defined settings
     *
     * @var array
     */
    protected $settings = array();

    /**
     * Creates a Gatekeeper object.
     *
     * @param array $settings The settings
     * @param \FlameCore\Gatekeeper\Storage\StorageInterface $storage The storage to use
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
     * Returns a list of defined settings.
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Sets the value for the given setting.
     *
     * @param string $setting The setting
     * @param mixed $value The value
     */
    public function setSetting($setting, $value)
    {
        $this->settings[$setting] = $value;
    }

    /**
     * Returns the storage.
     *
     * @return \FlameCore\Gatekeeper\Storage\StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Sets the storage to use.
     *
     * @param \FlameCore\Gatekeeper\Storage\StorageInterface $storage The storage to use
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * Returns the explainer.
     *
     * @return \FlameCore\Gatekeeper\Result\Explainer
     */
    public function getExplainer()
    {
        return $this->explainer;
    }

    /**
     * Sets the explainer to use.
     *
     * @param \FlameCore\Gatekeeper\Result\Explainer $explainer The explainer to use
     */
    public function setExplainer($explainer)
    {
        $this->explainer = $explainer;
    }

    /**
     * Returns the IP whitelist.
     *
     * @return \FlameCore\Gatekeeper\Listing\IPList
     */
    public function getWhitelist()
    {
        return $this->whitelist;
    }

    /**
     * Sets the IP whitelist.
     *
     * @param \FlameCore\Gatekeeper\Listing\IPList $whitelist The IP whitelist
     */
    public function setWhitelist(IPList $whitelist)
    {
        $this->whitelist = $whitelist;
    }

    /**
     * Returns the list of trusted user agents.
     *
     * @return \FlameCore\Gatekeeper\Listing\StringList
     */
    public function getTrustedUserAgents()
    {
        return $this->trustedUserAgents;
    }

    /**
     * Sets the list of trusted user agents.
     *
     * @param \FlameCore\Gatekeeper\Listing\StringList $trustedUserAgents The list of trusted user agents
     */
    public function setTrustedUserAgents(StringList $trustedUserAgents)
    {
        $this->trustedUserAgents = $trustedUserAgents;
    }

    /**
     * Runs the system.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request The request of the visitor
     * @param \FlameCore\Gatekeeper\ScreenerInterface $screener The screener to use
     */
    public function run(Request $request, ScreenerInterface $screener)
    {
        $visitor = new Visitor($request);
        $this->visitor = $visitor;

        if (!$this->isWhitelisted($visitor)) {
            $result = $screener->screenVisitor($visitor);

            $explainer = $this->explainer ?: new Explainer();
            $result->setExplanation($explainer->explain($result));
        } else {
            $result = new NegativeResult(__CLASS__);
        }

        if ($this->storage) {
            $this->storage->insert($visitor, $result);
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
     * @param \FlameCore\Gatekeeper\Result\PositiveResult $result The result
     * @throws \FlameCore\Gatekeeper\Exceptions\AccessDeniedException
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
     * @param \FlameCore\Gatekeeper\Result\PositiveResult $result The result
     */
    protected function penalize(PositiveResult $result)
    {
        // reserved for future use, maybe for reporting to stopforumspam.com or so
    }

    /**
     * Checks if the visitor is whitelisted.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor The visitor
     * @return bool
     */
    protected function isWhitelisted(Visitor $visitor)
    {
        if ($this->whitelist && $this->whitelist->match($visitor->getIP())) {
            return true;
        }

        $uastring = $visitor->getUserAgent()->getUserAgentString();
        if ($this->trustedUserAgents && $this->trustedUserAgents->match((string) $uastring)) {
            return true;
        }

        return false;
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message The message
     * @param array $context The context values
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
