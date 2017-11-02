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

namespace FlameCore\Gatekeeper\Check;

/**
 * AbstractConfigurableCheck
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractConfigurableCheck extends AbstractCheck
{
    /**
     * The settings
     *
     * @var array
     */
    protected $settings = array();

    /**
     * @param array $settings The settings
     */
    public function __construct(array $settings = [])
    {
        $defaults = (array) $this->getSettingsDefaults();
        $this->settings = array_replace($defaults, $settings);
    }

    /**
     * @return array
     */
    abstract protected function getSettingsDefaults();
}
