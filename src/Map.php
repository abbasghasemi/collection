<?php

namespace AG\Collection;

use ArrayAccess;

/**
 * @template K
 * @template-covariant V
 * @template-extends BaseArray<K,V>
 */
interface Map extends BaseArray, ArrayAccess
{

    /**
     * @param K $key
     * @return ?V
     */
    public function get(mixed $key): mixed;

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

    /**
     * @param K $key
     * @return ?Entry<K,V>
     */
    public function entryKey(mixed $key): ?Entry;

    public function getKeyType(): ?string;

    public function getValueType(): ?string;

}