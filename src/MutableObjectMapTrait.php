<?php

namespace AG\Collection;
/**
 * @template K
 * @template-covariant V
 */
trait MutableObjectMapTrait
{
    /**
     * @param K $key
     * @param callable():V $ifAbsent
     * @return V
     */
    public function putIfAbsent(mixed $key, callable $ifAbsent): mixed
    {
        $var = $this[$key];
        if (empty($var)) {
            $var = $ifAbsent();
            $this[$key] = $var;
        }
        return $var;
    }

    /**
     * @param Map<K,V> $map
     * @return void
     */
    public function putAll(Map $map): void
    {
        foreach ($map as $k => $v) $this[$k] = $v;
    }

    /**
     * @param K $key
     * @param V $value
     * @return void
     */
    public function update(mixed $key, mixed $value): void
    {
        unset($this[$key]);
        $this[$key] = $value;
    }

    /**
     * @param K $key
     * @param K $newKey
     * @return void
     */
    public function updateKey(mixed $key, mixed $newKey): void
    {
        $v = $this[$key];
        if (!empty($v)) {
            unset($this[$key]);
            $this[$newKey] = $v;
        }
    }

    /**
     * @param K $key
     * @param callable():V $replace
     * @return void
     */
    public function replace(mixed $key, callable $replace): void
    {
        if (!empty($this[$key])) {
            $this[$key] = $replace();
        }
    }

    /**
     * @param K $key
     * @return ?V
     */
    function remove(mixed $key): mixed
    {
        $v = $this[$key];
        if (!empty($v)) unset($this[$key]);
        return $v;
    }

    /**
     * @param callable(K,V):bool $test
     * @return void
     */
    function removeWhere(callable $test): void
    {
        foreach ($this as $k => $v) if ($test($k, $v)) unset($this[$k]);
    }
}