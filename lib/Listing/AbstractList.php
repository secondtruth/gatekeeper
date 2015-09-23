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

/**
 * Generic matching list
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
abstract class AbstractList implements ListInterface
{
    /**
     * Creates the list.
     *
     * @param string|string[] $values The value(s) to add
     */
    public function __construct($values = [])
    {
        if (!empty($values)) {
            $this->add($values);
        }
    }

    /**
     * Adds the given value(s) to the list.
     *
     * @param string|string[] $values The value(s) to add
     */
    abstract public function add($values);

    /**
     * Loads the list from the given file.
     *
     * @param string $file The list file
     */
    public function loadFile($file)
    {
        $lines = file($file, FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line[0] == '#') {
                continue;
            }

            $this->addFileEntry($line);
        }
    }

    /**
     * Adds the given entry from the file.
     *
     * @param string $value The entry value
     */
    abstract protected function addFileEntry($value);

    /**
     * Merges the given new entries into the list.
     *
     * @param array $list The existing list
     * @param array $newEntries The new entries
     * @return array Returns the updated list.
     */
    protected function merge(array $list, array $newEntries)
    {
        return array_unique(array_merge($list, $newEntries));
    }
}
