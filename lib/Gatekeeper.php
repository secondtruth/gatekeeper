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
use Secondtruth\Gatekeeper\ACL\ACLInterface;
use Secondtruth\Gatekeeper\Result\Explainer;
use Secondtruth\Gatekeeper\Result\NegativeResult;
use Secondtruth\Gatekeeper\Result\PositiveResult;
use Secondtruth\Gatekeeper\Result\ResultInterface;
use Secondtruth\Gatekeeper\Storage\StorageInterface;
use Secondtruth\Gatekeeper\Exceptions\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * The list of ACLs
     *
     * @var ACLInterface[]
     */
    protected array $acls = [];

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
     * Adds an Access Control List (ACL).
     *
     * @param ACLInterface $acl The ACL to add
     */
    public function addACL(ACLInterface $acl): void
    {
        $this->acls[] = $acl;
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
        $visitor = $request instanceof Request ? new Visitor($request) : Visitor::fromPsr7Request($request);
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
        foreach ($this->acls as $acl) {
            if ($acl->isAllowed($visitor)) {
                return new NegativeResult($acl::class);
            }

            if ($acl->isDenied($visitor)) {
                $result = new PositiveResult($acl::class);
                $result->setExplanation([
                    'response' => Response::HTTP_FORBIDDEN,
                    'explanation' => 'You do not have permission to access this server.',
                    'logtext' => sprintf('Request blocked by: %s', $acl::class)
                ]);

                return $result;
            }
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
