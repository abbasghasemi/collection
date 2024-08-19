<?php

namespace AG\Collection;

/**
 * @template K
 * @template-covariant V
 * @template-extends Map<K,V>
 */
abstract class AbstractMap implements Map
{
    use CheckTypeTrait;

    private int $pointer = 0;

    /**
     * @return K
     */
    public function key(): mixed
    {
        if ($this instanceof ObjectMap) {
            return $this->keys[$this->pointer];
        }
        return $this->keys()->get($this->pointer);
    }

    /**
     * @return V
     */
    public function current(): mixed
    {
        if ($this instanceof ObjectMap) {
            return $this->values[$this->pointer];
        }
        return $this->values()->get($this->pointer);
    }

    public function valid(): bool
    {
        return $this->pointer < $this->size();
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function forward(int $count = 1): void
    {
        assert($count > 0 && $this->pointer + $count < $this->size());
        $this->pointer += $count;
    }

    public function back(int $count = 1): void
    {
        assert($count > 0 && $this->pointer - $count > -1);
        $this->pointer -= $count;
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function count(): int
    {
        return $this->size();
    }

    public function isEmpty(): bool
    {
        return $this->size() === 0;
    }

    public function isNotEmpty(): bool
    {
        return $this->size() !== 0;
    }

    /**
     * @param callable(V,K): void $callback
     * @return void
     */
    public function forEach(callable $callback): void
    {
        foreach ($this->keys() as $k) call_user_func($callback, $this[$k], $k);
    }

    /**
     * @param K $key
     * @return ?V
     */
    public function get(mixed $key): mixed
    {
        return $this->offsetGet($key);
    }

    /**
     * @param K $key
     * @return bool
     */
    public function containsKey(mixed $key): bool
    {
        if (array_key_exists($key, $this->keys()->toArray())) {
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
        foreach ($this->values() as $v) if ($value === $v) {
            return true;
        }
        return false;
    }

    /**
     * @return ArrayList<Entry<K,V>>
     */
    public function entries(): ArrayList
    {
        $list = [];
        foreach ($this->keys() as $k) $list[] = new MapEntry($k, $this[$k]);
        return new ArrayList($list);
    }

    /**
     * @param K $key
     * @return ?Entry<K,V>
     */
    public function entryKey(mixed $key): ?Entry
    {
        foreach ($this->keys() as $k) if ($k === $key) return new MapEntry($k, $this[$k]);
        return null;
    }

    public function hashCode(): int
    {
        $hashCode = 1;
        foreach ($this as $e)
            $hashCode = 31 * $hashCode + ($e === null ? 0 : Collections::hashCode($e));
        return $hashCode;
    }

    public function equals(mixed $o): bool
    {
        if ($o === $this)
            return true;
        if (!($o instanceof Map))
            return false;
        $o1 = $this->keys();
        $o2 = $o->keys();
        while ($o1->valid() && $o2->valid()) {
            $e1 = $this[$o1->current()];
            $e2 = $o[$o2->current()];
            if (!($e1 === null ? $e2 === null : $e1 === $e2))
                return false;
            $o1->next();
            $o2->next();
        }
        return !($o1->valid() || $o2->valid());
    }

    public function __toString(): string
    {
        if ($this->isEmpty()) return "{}";
        $str = '';
        foreach ($this->keys() as $key) {
            $str .= $key . ":" . Collections::toString($this[$key]) . ',';
        }
        return "{{$str}}";
    }

    protected function requiredCheckKeyType(): bool
    {
        return $this->getKeyType() !== null && $this->getKeyType() !== 'mixed';
    }

    protected function equalsKeyType(Map $map): bool
    {
        $type = $this->getKeyType();
        return $type === $map->getKeyType() || $map->getKeyType() instanceof $type;
    }

    protected function checkKeyType(mixed $key): void
    {
        $this->checkType($this->getKeyType(), $key, fn() => "Key type most be '{$this->getKeyType()}'!");
    }

    protected function requiredCheckValueType(): bool
    {
        return $this->getValueType() !== null && $this->getValueType() !== 'mixed';
    }

    protected function equalsValueType(Map $map): bool
    {
        $type = $this->getValueType();
        return $type === $map->getValueType() || $map->getValueType() instanceof $type;
    }

    protected function checkValueType(mixed $value): void
    {
        $this->checkType($this->getValueType(), $value, fn() => "Value type must be '{$this->getValueType()}'!");
    }

    protected function checkKeyValueType(mixed $key, mixed $value): void
    {
        $this->checkKeyType($key);
        $this->checkValueType($value);
    }
}