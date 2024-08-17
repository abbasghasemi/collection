<?php

namespace AG\Collection;

use TypeError;

/**
 * @template V
 * @template-extends AbstractMap<string,V>
 */
abstract class AbstractStringMap extends AbstractMap implements ArrayMap
{
    private ?array $iterator = null;

    public function key(): string
    {
        return array_key_first($this->iterator);
    }

    public function current(): string
    {
        return $this->iterator[$this->key()];
    }

    public function valid(): bool
    {
        return !empty($this->iterator);
    }

    public function next(): void
    {
        array_shift($this->iterator);
    }

    public function rewind(): void
    {
        $this->iterator = array_replace($this->toMap());
    }

    /**
     * @param callable(string,V): void $callback
     * @return void
     */
    public function forEach(callable $callback): void
    {
        foreach ($this->toMap() as $k => $v) call_user_func($callback, $k, $v);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function containsKey(mixed $key): bool
    {
        $this->checkKeyType($key);
        if (array_key_exists($key, $this->toMap())) {
            return true;
        }
        return false;
    }

    /**
     * @param V $value
     * @return bool
     */
    public function containsValue(mixed $value): bool
    {
        foreach ($this->toMap() as $k => $v) if ($value === $v) {
            return true;
        }
        return false;
    }

    /**
     * @return ArrayList<Entry<string,V>>
     */
    public function entries(): ArrayList
    {
        $list = [];
        foreach ($this->toMap() as $k => $v) $list[] = new MapEntry($k, $v);
        return new ArrayList($list);
    }

    /**
     * @param mixed $key
     * @return void
     */
    public function checkKeyType(mixed $key): void
    {
        if (!is_string($key)) {
            throw new TypeError("key most be string");
        }
    }
}