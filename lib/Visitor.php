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
    protected $ip;

    /**
     * The request headers
     *
     * @var \Symfony\Component\HttpFoundation\HeaderBag
     */
    protected $headers;

    /**
     * The request method
     *
     * @var string
     */
    protected $method;

    /**
     * The request URI
     *
     * @var string
     */
    protected $uri;

    /**
     * The request data
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $data;

    /**
     * The request scheme
     *
     * @var string
     */
    protected $scheme;

    /**
     * The server protocol
     *
     * @var string
     */
    protected $protocol;

    /**
     * The user agent information
     *
     * @var \Secondtruth\Gatekeeper\UserAgent
     */
    protected $userAgent;

    /**
     * Creates a Visitor object.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request The request of the visitor
     */
    public function __construct(Request $request)
    {
        $this->ip = new IP($request->getClientIp());
        $this->headers = $request->headers;
        $this->method = $request->getRealMethod();
        $this->uri = $request->getRequestUri();
        $this->data = $request->request;
        $this->scheme = $request->getScheme();
        $this->protocol = $request->server->get('SERVER_PROTOCOL');

        $userAgent = $request->headers->get('user-agent');
        $this->userAgent = new UserAgent($userAgent);
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
     * @return \Symfony\Component\HttpFoundation\HeaderBag
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
     * @return \Symfony\Component\HttpFoundation\ParameterBag
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
     * @return \Secondtruth\Gatekeeper\UserAgent
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
        return array(
            'ip'         => (string) $this->ip,
            'headers'    => $this->headers->all(),
            'method'     => $this->method,
            'uri'        => $this->uri,
            'data'       => $this->data->all(),
            'protocol'   => $this->protocol,
            'scheme'     => $this->scheme,
            'user_agent' => $this->userAgent->getUserAgentString()
        );
    }
}
