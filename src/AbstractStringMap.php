<?php

namespace AG\Collection;

/**
 * @template-covariant V
 * @template-extends AbstractMap<string,V>
 */
abstract class AbstractStringMap extends AbstractMap
{
    private ?array $keys = null;

    private int $pointer = 0;

    public function key(): string
    {
        return $this->keys[$this->pointer];
    }

    public function current(): string
    {
        return $this->toMap()[$this->key()];
    }

    public function valid(): bool
    {
        return $this->pointer < $this->size();
    }

    public function next(): void
    {
        $this->pointer++;
        if (!$this->valid()) {
            $this->keys = null;
            $this->pointer = 0;
        }
    }

    public function forward(int $count = 1): void
    {
        assert($this->keys !== null && $count > 0 && $this->pointer + $count < $this->size());
        $this->pointer += $count;
    }

    public function back(int $count = 1): void
    {
        assert($this->keys !== null && $count > 0 && $this->pointer - $count > -1);
        $this->pointer -= $count;
    }

    public function rewind(): void
    {
        $this->pointer = 0;
        $this->keys = array_keys($this->toMap());
    }

    /**
     * @param callable(V,string): void $callback
     * @return void
     */
    public function forEach(callable $callback): void
    {
        foreach ($this->toMap() as $k => $v) call_user_func($callback, $v, $k);
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

    public function getKeyType(): ?string
    {
        return 'string';
    }

    public abstract function toMap(): array;
}