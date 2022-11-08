<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Bundle;

use Secondtruth\Gatekeeper\ACL\ACLInterface;
use Secondtruth\Gatekeeper\Check\CheckInterface;
use Secondtruth\Gatekeeper\Gatekeeper;
use Secondtruth\Gatekeeper\Screener;

/**
 * Base class for bundles
 *
 * @author Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractBundle implements BundleInterface
{
    /**
     * {@inheritdoc}
     */
    public function configure(Gatekeeper $gatekeeper): void
    {
        foreach ($this->createACLs() as $acl) {
            $gatekeeper->addACL($acl);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createScreener(): Screener
    {
        $screener = new Screener();

        foreach ($this->createChecks() as $check) {
            $screener->addCheck($check);
        }

        return $screener;
    }

    /**
     * Creates the Check objects to use.
     *
     * @return CheckInterface[]
     */
    protected function createChecks(): array
    {
        return [];
    }

    /**
     * Creates the ACL objects to use.
     *
     * @return ACLInterface[]
     */
    protected function createACLs(): array
    {
        return [];
    }
}
