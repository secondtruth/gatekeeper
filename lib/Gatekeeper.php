<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper;

use Psr\Http\Message\ServerRequestInterface;
use Secondtruth\Gatekeeper\Listing\IPList;
use Secondtruth\Gatekeeper\Listing\StringList;
use Secondtruth\Gatekeeper\Result\Explainer;
use Secondtruth\Gatekeeper\Result\NegativeResult;
use Secondtruth\Gatekeeper\Result\PositiveResult;
use Secondtruth\Gatekeeper\Result\ResultInterface;
use Secondtruth\Gatekeeper\Storage\StorageInterface;
use Secondtruth\Gatekeeper\Exceptions\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

/**
 * The Gatekeeper class.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Gatekeeper
{
    /**
     * The storage to use
     *
     * @var StorageInterface
     */
    protected $storage;

    /**
     * The explainer to use
     *
     * @var Explainer
     */
    protected $explainer;

    /**
     * The IP whitelist
     *
     * @var IPList
     */
    protected $whitelist;

    /**
     * The list of trusted user agents
     *
     * @var StringList
     */
    protected $trustedUserAgents;

    /**
     * The current visitor
     *
     * @var Visitor
     */
    protected $visitor;

    /**
     * List of defined settings
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Creates a Gatekeeper object.
     *
     * @param array            $settings The settings
     * @param StorageInterface $storage  The storage to use
     */
    public function __construct(array $settings = [], ?StorageInterface $storage = null)
    {
        $defaults = [
            'block_message' => "<p>Your request has been blocked.</p>\n<p>{explanation}</p>"
        ];

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
     * @param mixed  $value   The value
     */
    public function setSetting($setting, $value)
    {
        $this->settings[$setting] = $value;
    }

    /**
     * Returns the storage.
     *
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Sets the storage to use.
     *
     * @param StorageInterface $storage The storage to use
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * Returns the explainer.
     *
     * @return Explainer
     */
    public function getExplainer()
    {
        return $this->explainer;
    }

    /**
     * Sets the explainer to use.
     *
     * @param Explainer $explainer The explainer to use
     */
    public function setExplainer($explainer)
    {
        $this->explainer = $explainer;
    }

    /**
     * Returns the IP whitelist.
     *
     * @return IPList
     */
    public function getWhitelist()
    {
        return $this->whitelist;
    }

    /**
     * Sets the IP whitelist.
     *
     * @param IPList $whitelist The IP whitelist
     */
    public function setWhitelist(IPList $whitelist)
    {
        $this->whitelist = $whitelist;
    }

    /**
     * Returns the list of trusted user agents.
     *
     * @return StringList
     */
    public function getTrustedUserAgents()
    {
        return $this->trustedUserAgents;
    }

    /**
     * Sets the list of trusted user agents.
     *
     * @param StringList $trustedUserAgents The list of trusted user agents
     */
    public function setTrustedUserAgents(StringList $trustedUserAgents)
    {
        $this->trustedUserAgents = $trustedUserAgents;
    }

    /**
     * Runs the system.
     *
     * @param ServerRequestInterface|Request $request  The request of the visitor
     * @param ScreenerInterface              $screener The screener to use
     *
     * @throws AccessDeniedException
     */
    public function run(ServerRequestInterface|Request $request, ScreenerInterface $screener)
    {
        $visitor = $request instanceof Request ? new Visitor($request) : Visitor::fromPsr7($request);
        $this->visitor = $visitor;

        $result = $this->analyze($visitor, $screener);

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
     * Analyzes the visitor.
     *
     * @param Visitor           $visitor  The visitor
     * @param ScreenerInterface $screener The screener to use
     *
     * @return ResultInterface Returns a positive result if the visitor should be blocked, otherwise a negative result.
     */
    public function analyze(Visitor $visitor, ScreenerInterface $screener): ResultInterface
    {
        if ($this->isAllowed($visitor)) {
            return new NegativeResult();
        }

        $result = $screener->screenVisitor($visitor);

        if ($result instanceof PositiveResult) {
            $explainer = $this->explainer ?? new Explainer();
            $result->setExplanation($explainer->explain($result));
        }

        return $result;
    }

    /**
     * Perform actions for bad requests.
     *
     * @param PositiveResult $result The result
     *
     * @throws AccessDeniedException
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
     * @param PositiveResult $result The result
     */
    protected function penalize(PositiveResult $result)
    {
        // reserved for future use, maybe for reporting to stopforumspam.com or so
    }

    /**
     * Checks if the visitor is whitelisted.
     *
     * @param Visitor $visitor The visitor
     *
     * @return bool
     */
    protected function isAllowed(Visitor $visitor)
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
     * @param array  $context The context values
     *
     * @return string
     */
    protected function interpolate($message, array $context = [])
    {
        $replace = [];
        foreach ($context as $key => $value) {
            $replace['{' . $key . '}'] = $value;
        }

        return strtr($message, $replace);
    }
}
