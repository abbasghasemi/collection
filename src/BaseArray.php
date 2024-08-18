<?php

namespace AG\Collection;

use Countable;
use Iterator;

/**
 * @template K
 * @template-covariant V
 * @template-extends Iterator<K,V>
 */
interface BaseArray extends Iterator, Countable, ObjectC
{
    public function size(): int;

    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function forward(int $count = 1): void;

    public function back(int $count = 1): void;

    /**
     * @param callable(V,K): void $callback
     * @return void
     */
    public function forEach(callable $callback): void;
}