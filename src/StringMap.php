<?php

namespace AG\Collection;

use JsonSerializable;
use ReflectionClass;
use RuntimeException;
use Serializable;

/**
 * @template V
 * @template-extends AbstractStringMap<V>
 */
class StringMap extends AbstractStringMap implements JsonSerializable, Serializable
{
    protected array $map;

    public function __construct(null|array|ArrayMap $map = null)
    {
        if (is_array($map)) {
            foreach ($map as $k => $v) $this->checkKeyType($k);
        }
        $map ??= array();
        $this->map = $map instanceof ArrayMap ? $map->toMap() : $map;
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        $this->checkKeyType($offset);
        return isset($this->map[$offset]);
    }

    /**
     * @param string $offset
     * @return ?V
     */
    public function offsetGet(mixed $offset): mixed
    {
        $this->checkKeyType($offset);
        return $this->map[$offset] ?? null;
    }

    /**
     * @param string $offset
     * @param V $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->checkKeyType($offset);
        if ($this instanceof MutableMap) {
            $this->map[$offset] = $value;
        } else throw new RuntimeException(get_called_class() . ' is unchanged!, please use the ' . MutableStringMap::class . '.');
    }

    /**
     * @param string $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->checkKeyType($offset);
        if ($this instanceof MutableMap) {
            unset($this->map[$offset]);
        } else throw new RuntimeException(get_called_class() . ' is unchanged!, please use the ' . MutableStringMap::class . '.');
    }

    public function size(): int
    {
        return count($this->map);
    }

    /**
     * @return ArraySet<string>
     */
    public function keys(): ArraySet
    {
        $arraySet = new ArraySet();
        $class = new ReflectionClass($arraySet);
        $class->getProperty('list')->setValue($arraySet, array_keys($this->map));
        $class->getProperty('size')->setValue($arraySet, $this->size());
        return $arraySet;
    }

    /**
     * @return ArrayList<V>
     */
    public function values(): ArrayList
    {
        return new ArrayList(array_values($this->map));
    }

    /**
     * @return MutableArrayList<V>
     */
    public function mutableValues(): MutableArrayList
    {
        return new MutableArrayList(array_values($this->map));
    }

    public function jsonSerialize(): mixed
    {
        return $this->map;
    }

    public function serialize(): ?string
    {
        return serialize($this->map);
    }

    public function unserialize(string $data): void
    {
        $this->map = unserialize($data);
        foreach ($this->map as $k => $v) $this->checkKeyType($k);
    }

    public function __serialize(): array
    {
        return [$this->map];
    }

    public function __unserialize(array $data): void
    {
        $this->map = $data[0];
        foreach ($this->map as $k => $v) $this->checkKeyType($k);
    }

    public function toMap(): array
    {
        return $this->map;
    }
}