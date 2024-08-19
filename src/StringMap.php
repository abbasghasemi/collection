<?php

namespace AG\Collection;

use JsonSerializable;
use ReflectionClass;
use RuntimeException;
use Serializable;

/**
 * @template-covariant V
 * @template-extends AbstractStringMap<V>
 */
class StringMap extends AbstractStringMap implements JsonSerializable, Serializable
{
    protected array $map;
    protected int $size = 0;

    public function __construct(
        null|array|Map           $map = null,
        private readonly ?string $type = null,
    )
    {
        if (is_array($map) || ($map instanceof Map && (
                    !$this->equalsKeyType($map) ||
                    $this->requiredCheckValueType() && !$this->equalsValueType($map)
                ))) {
            foreach ($map as $k => $v) $this->checkKeyValueType($k, $v);
        }
        if ($map === null) {
            $this->map = [];
        } elseif ($map instanceof AbstractStringMap) {
            $this->map = $map->toMap();
        } elseif ($map instanceof Map) {
            $this->map = array_combine($map->keys()->toArray(), $map->values()->toArray());
        } else {
            $this->map = $map;
        }
        $this->size = count($this->map);
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        if (!is_string($offset)) return false;
        return isset($this->map[$offset]);
    }

    /**
     * @param string $offset
     * @return ?V
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (!is_string($offset)) return null;
        return $this->map[$offset] ?? null;
    }

    /**
     * @param string $offset
     * @param V $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->checkKeyValueType($offset, $value);
        if ($this instanceof MutableMap) {
            if (!isset($this->map[$offset])) {
                $this->size++;
            }
            $this->map[$offset] = $value;
        } else throw new RuntimeException(get_called_class() . ' is unchanged!, please use the ' . MutableStringMap::class . '.');
    }

    /**
     * @param string $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        if ($this instanceof MutableMap) {
            if (!is_string($offset)) return;
            if (isset($this->map[$offset])) {
                unset($this->map[$offset]);
                $this->size--;
            }
        } else throw new RuntimeException(get_called_class() . ' is unchanged!, please use the ' . MutableStringMap::class . '.');
    }

    public function size(): int
    {
        return $this->size;
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
        return new ArrayList($this->map);
    }

    /**
     * @return MutableArrayList<V>
     */
    public function mutableValues(): MutableArrayList
    {
        return new MutableArrayList($this->map);
    }

    public function getValueType(): ?string
    {
        return $this->type;
    }

    public function toMap(): array
    {
        return $this->map;
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
        $this->size = count($this->map);
    }

    public function __serialize(): array
    {
        return [$this->map];
    }

    public function __unserialize(array $data): void
    {
        $this->map = $data[0];
        foreach ($this->map as $k => $v) $this->checkKeyType($k);
        $this->size = count($this->map);
    }
}