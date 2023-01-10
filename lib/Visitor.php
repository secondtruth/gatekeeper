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
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Visitor
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Visitor
{
    /**
     * The client IP address
     *
     * @var IP
     */
    protected IP $ip;

    /**
     * The request headers
     *
     * @var HeaderBag
     */
    protected HeaderBag $headers;

    /**
     * The request method
     *
     * @var string
     */
    protected string $method;

    /**
     * The request URI
     *
     * @var string
     */
    protected string $uri;

    /**
     * The request data
     *
     * @var ParameterBag
     */
    protected ParameterBag $data;

    /**
     * The request scheme
     *
     * @var string
     */
    protected string $scheme;

    /**
     * The server protocol
     *
     * @var string
     */
    protected string $protocol;

    /**
     * The user agent information
     *
     * @var UserAgent
     */
    protected UserAgent $userAgent;

    /**
     * Creates a Visitor object.
     *
     * @param IP|string          $ip        The client IP address
     * @param HeaderBag|array    $headers   The request headers
     * @param string             $method    The request method
     * @param string             $uri       The request URI
     * @param ParameterBag|array $data      The request data
     * @param string             $scheme    The request scheme
     * @param string             $protocol  The server protocol
     * @param UserAgent|string   $userAgent The user agent information object or the user agent string
     */
    public function __construct(
        IP|string $ip,
        HeaderBag|array $headers,
        string $method,
        string $uri,
        ParameterBag|array $data,
        string $scheme,
        string $protocol,
        UserAgent|string $userAgent
    ) {
        $this->ip = $ip instanceof IP ? $ip : new IP($ip);
        $this->headers = $headers instanceof HeaderBag ? $headers : new HeaderBag($headers);
        $this->method = $method;
        $this->uri = $uri;
        $this->data = $data instanceof ParameterBag ? $data : new ParameterBag($data);
        $this->scheme = $scheme;
        $this->protocol = $protocol;
        $this->userAgent = $userAgent instanceof UserAgent ? $userAgent : new UserAgent($userAgent);
    }

    /**
     * Creates a Visitor object from a Symfony HttpFoundation Request.
     *
     * @param Request $request The request of the visitor
     *
     * @return self
     */
    public static function fromSymfonyRequest(Request $request): self
    {
        return new self(
            $request->getClientIp(),
            $request->headers,
            $request->getRealMethod(),
            $request->getRequestUri(),
            $request->request,
            $request->getScheme(),
            $request->server->get('SERVER_PROTOCOL', ''),
            $request->headers->get('user-agent', '')
        );
    }

    /**
     * Creates a Visitor object from a PSR-7 server request.
     *
     * @param ServerRequestInterface     $request               The server request of the visitor
     * @param HttpFoundationFactory|null $httpFoundationFactory
     *
     * @return self
     */
    public static function fromPsr7Request(ServerRequestInterface $request, ?HttpFoundationFactory $httpFoundationFactory = null)
    {
        $httpFoundationFactory ??= new HttpFoundationFactory();
        $request = $httpFoundationFactory->createRequest($request);

        return self::fromSymfonyRequest($request);
    }

    /**
     * Gets the client IP address.
     *
     * @return \Secondtruth\Gatekeeper\IP
     */
    public function getIP()
    {
        return $this->ip;
    }

    /**
     * Gets the request headers.
     *
     * @return HeaderBag
     */
    public function getRequestHeaders()
    {
        return $this->headers;
    }

    /**
     * Gets the request method.
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->method;
    }

    /**
     * Gets the request URI.
     *
     * @return string
     */
    public function getRequestURI()
    {
        return $this->uri;
    }

    /**
     * Gets the request data.
     *
     * @return ParameterBag
     */
    public function getRequestData()
    {
        return $this->data;
    }

    /**
     * Gets the request scheme.
     *
     * @return string
     */
    public function getRequestScheme()
    {
        return $this->scheme;
    }

    /**
     * Gets the server protocol.
     *
     * @return string
     */
    public function getServerProtocol()
    {
        return $this->protocol;
    }

    /**
     * Gets the user agent information.
     *
     * @return UserAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Export data to array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'ip' => (string) $this->ip,
            'headers' => $this->headers->all(),
            'method' => $this->method,
            'uri' => $this->uri,
            'data' => $this->data->all(),
            'protocol' => $this->protocol,
            'scheme' => $this->scheme,
            'user_agent' => $this->userAgent->getUserAgentString()
        ];
    }
}
