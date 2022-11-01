<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Listing;

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
    protected array $list = [];

    /**
     * {@inheritdoc}
     */
    public function match(mixed $value)
    {
        foreach ($this->list as $checkValue) {
            if (strpos($checkValue, '/')) {
                [$checkIP, $checkMask] = explode('/', $checkValue);
                $checkMask = pow(2, 32) - pow(2, (32 - $checkMask));

                if ((ip2long($value) & $checkMask) == (ip2long($checkIP) & $checkMask)) {
                    return true;
                }
            } else {
                if ((string) $value === $checkValue) {
                    return true;
                }
            }
        }

        return false;
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
     * {@inheritdoc}
     */
    public function add(string|array $values)
    {
        $ips = self::toArrayOfStrings($values);

        $this->list = self::merge($this->list, $ips);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->list = [];
    }

    /**
     * {@inheritdoc}
     */
    protected function addFileEntry(string $value)
    {
        $this->add($value);
    }
}
