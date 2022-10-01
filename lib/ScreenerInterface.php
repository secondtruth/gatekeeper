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

/**
 * Interface ScreenerInterface
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface ScreenerInterface
{
    /**
     * Screens the visitor.
     *
     * @param \Secondtruth\Gatekeeper\Visitor $visitor The visitor information
     *
     * @return \Secondtruth\Gatekeeper\Result\ResultInterface Returns the screening result.
     */
    public function screenVisitor(Visitor $visitor);
}
