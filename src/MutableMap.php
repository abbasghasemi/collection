<?php

namespace AG\Collection;

/**
 * @template K
 * @template-covariant V
 * @template-extends Map<K,V>
 */
interface MutableMap extends Map
{

    /**
     * @param K $key
     * @param callable():V $ifAbsent
     * @return V
     */
    public function putIfAbsent(mixed $key, callable $ifAbsent): mixed;


    /**
     * @param Map $map
     * @return void
     */
    public function putAll(Map $map): void;

    /**
     * @param K $key
     * @param V $value
     * @return void
     */
    public function update(mixed $key, mixed $value): void;

    /**
     * @param K $key
     * @param K $newKey
     * @return void
     */
    public function updateKey(mixed $key, mixed $newKey): void;

    /**
     * @param K $key
     * @param callable():V $replace
     * @return void
     */
    public function replace(mixed $key, callable $replace): void;

    /**
     * @param K $key
     * @return V
     */
    function remove(mixed $key): mixed;


    /**
     * @param callable(K,V):bool $test
     * @return void
     */
    function removeWhere(callable $test): void;

}