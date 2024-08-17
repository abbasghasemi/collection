<?php

namespace AG\Collection;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * @template K
 * @template V
 */
interface Map extends ArrayAccess, Iterator, Countable, ObjectC
{
    public function size(): int;

    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    /**
     * @param callable(K,V): void $callback
     * @return void
     */
    public function forEach(callable $callback): void;

    /**
     * @param K $key
     * @return bool
     */
    public function containsKey(mixed $key): bool;

    /**
     * @param V $value
     * @return bool
     */
    public function containsValue(mixed $value): bool;

    /**
     * @return ArraySet<K>
     */
    public function keys(): ArraySet;

    /**
     * @return ArrayList<V>
     */
    public function values(): ArrayList;

    /**
     * @return MutableArrayList<V>
     */
    public function mutableValues(): MutableArrayList;

    /**
     * @return ArrayList<Entry<K,V>>
     */
    public function entries(): ArrayList;

}