<?php

namespace AG\Collection;

use JsonSerializable;
use ReflectionClass;
use RuntimeException;
use Serializable;

/**
 * @template K
 * @template V
 * @template-extends AbstractMap<K<V>
 */
class ObjectMap extends AbstractMap implements JsonSerializable, Serializable
{
    protected array $keys;
    protected array $values;

    public function __construct(?Map $map = null)
    {
        if ($map === null) {
            $this->keys = [];
            $this->values = [];
        } else {
            $this->keys = $map->keys()->toArray();
            $this->values = $map->values()->toArray();
        }
    }

    /**
     * @param K $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        $indexOf = $this->keys()->indexOf($offset);
        return $indexOf !== -1;
    }

    /**
     * @param K $offset
     * @return ?V
     */
    public function offsetGet(mixed $offset): mixed
    {
        $indexOf = $this->keys()->indexOf($offset);
        if ($indexOf === -1) {
            return null;
        }
        return $this->values[$indexOf];
    }

    /**
     * @param K $offset
     * @param V $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this instanceof MutableMap) {
            $indexOf = $this->keys()->indexOf($offset);
            if ($indexOf === -1) {
                $this->keys[] = $offset;
                $this->values[] = $value;
            } else {
                $this->values[$indexOf] = $value;
            }
        } else throw new RuntimeException(get_called_class() . ' is unchanged!, please use the ' . MutableObjectMap::class . '.');
    }

    /**
     * @param K $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        if ($this instanceof MutableMap) {
            $indexOf = $this->keys()->indexOf($offset);
            if ($indexOf !== -1) {
                unset($this->keys[$indexOf]);
                unset($this->values[$indexOf]);
            }
        } else throw new RuntimeException(get_called_class() . ' is unchanged!, please use the ' . MutableObjectMap::class . '.');
    }

    public function size(): int
    {
        return count($this->keys);
    }

    /**
     * @return ArraySet<K>
     */
    public function keys(): ArraySet
    {
        $arraySet = new ArraySet();
        $class = new ReflectionClass($arraySet);
        $class->getProperty('list')->setValue($arraySet, $this->keys);
        $class->getProperty('size')->setValue($arraySet, $this->size());
        return $arraySet;
    }

    /**
     * @return ArrayList<V>
     */
    public function values(): ArrayList
    {
        return new ArrayList($this->values);
    }

    /**
     * @return MutableArrayList<V>
     */
    public function mutableValues(): MutableArrayList
    {
        return new MutableArrayList($this->values);
    }

    public function jsonSerialize(): mixed
    {
        return $this->values;
    }

    public function serialize(): ?string
    {
        return serialize([$this->keys, $this->values]);
    }

    public function unserialize(string $data): void
    {
        list($this->keys, $this->values) = unserialize($data);
    }

    public function __serialize(): array
    {
        return [$this->keys, $this->values];
    }

    public function __unserialize(array $data): void
    {
        list($this->keys, $this->values) = $data;
    }
}