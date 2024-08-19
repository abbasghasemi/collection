<?php

namespace AG\Collection;

/**
 * @template-covariant V
 * @template-extends InsensitiveMap<V>
 * @template-implements MutableMap<string,V>
 */
class MutableInsensitiveMap extends InsensitiveMap implements MutableMap
{

    use MutableObjectMapTrait;


    /**
     * @param Map<string,V> $map
     * @return void
     */
    public function putAll(Map $map): void
    {
        foreach ($map as $k => $value) if (!$this->containsKey($k)) {
            $this->checkKeyValueType($k, $value);
            $this->map[$k] = $value;
            $this->size++;
        }
    }

    /**
     * @param string $key
     * @param V $value
     * @return void
     */
    public function update(mixed $key, mixed $value): void
    {
        $this->checkKeyValueType($key, $value);
        $entry = $this->entryKey($key);
        if (empty($entry)) $this->size++;
        else unset($this->map[$entry->getKey()]);
        $this->map[$key] = $value;
    }

    /**
     * @param string $key
     * @param string $newKey
     * @return void
     */
    public function updateKey(mixed $key, mixed $newKey): void
    {
        $this->checkKeyType($key);
        $this->checkKeyType($newKey);
        $entry = $this->entryKey($key);
        if (!empty($entry)) {
            unset($this->map[$entry->getKey()]);
            $this->map[$newKey] = $entry->getValue();
        }
    }

    /**
     * @param string $key
     * @param callable():V $replace
     * @return void
     */
    public function replace(mixed $key, callable $replace): void
    {
        $entry = $this->entryKey($key);
        if (!empty($entry)) {
            $var = $replace();
            $this->checkValueType($var);
            $this->map[$entry->getKey()] = $var;
        }
    }

    /**
     * @param string $key
     * @return ?V
     */
    public function remove(mixed $key): mixed
    {
        $entry = $this->entryKey($key);
        if (!empty($entry)) {
            unset($this->map[$entry->getKey()]);
            $this->size--;
            return $entry->getValue();
        }
        return null;
    }

    /**
     * @param callable(string,V):bool $test
     * @return void
     */
    public function removeWhere(callable $test): void
    {
        foreach ($this->map as $k => $v) if ($test($k, $v)) {
            unset($this->map[$k]);
            $this->size--;
        }
    }

}