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

namespace FlameCore\Gatekeeper\Listing;

use FlameCore\Gatekeeper\Utils;

/**
 * IP matching list
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class IPList extends AbstractList
{
    /**
     * List of IPs
     *
     * @var string[]
     */
    protected $list = array();

    /**
     * {@inheritdoc}
     */
    public function match($value)
    {
        return Utils::matchCIDR($value, $this->list);
    }

    /**
     * Gets the IP list.
     *
     * @return string[]
     */
    public function get()
    {
        return $this->list;
    }

    /**
     * Sets the IP list.
     *
     * @param string|string[] $ips The IP(s) to add
     */
    public function add($ips)
    {
        $ips = array_map('strval', (array) $ips);

        $this->list = $this->merge($this->list, $ips);
    }

    /**
     * {@inheritdoc}
     */
    protected function addFileEntry($value)
    {
        $this->add($value);
    }
}
