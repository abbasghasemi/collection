<?php

namespace AG\Collection;

use JsonSerializable;
use ReflectionClass;
use RuntimeException;
use Serializable;

/**
 * @template K
 * @template-covariant V
 * @template-extends AbstractMap<K,V>
 */
class ObjectMap extends AbstractMap implements JsonSerializable, Serializable
{
    protected array $keys;
    protected array $values;
    protected int $size = 0;

    public function __construct(
        null|Map|array           $map = null,
        private readonly ?string $keyType = null,
        private readonly ?string $valueType = null,
    )
    {
        if (is_array($map) || ($map instanceof Map && (
                    $this->requiredCheckKeyType() && !$this->equalsKeyType($map) ||
                    $this->requiredCheckValueType() && !$this->equalsValueType($map)
                ))) {
            foreach ($map as $k => $v) $this->checkKeyValueType($k, $v);
        }
        if ($map === null) {
            $this->keys = [];
            $this->values = [];
        } elseif ($map instanceof Map) {
            $this->keys = $map->keys()->toArray();
            $this->values = $map->values()->toArray();
        } else {
            $this->keys = array_keys($map);
            $this->values = array_values($map);
        }
        $this->size = count($this->keys);
    }

    /**
     * @param K $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->keys()->contains($offset);
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
            $this->checkKeyValueType($offset, $value);
            $indexOf = $this->keys()->indexOf($offset);
            if ($indexOf === -1) {
                $this->keys[] = $offset;
                $this->values[] = $value;
                $this->size++;
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
                $this->size--;
            }
        } else throw new RuntimeException(get_called_class() . ' is unchanged!, please use the ' . MutableObjectMap::class . '.');
    }

    public function size(): int
    {
        return $this->size;
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

    public function getKeyType(): ?string
    {
        return $this->keyType;
    }

    public function getValueType(): ?string
    {
        return $this->valueType;
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
        $this->size = count($this->keys);
        if ($this->size() !== count($this->values)) {
            throw new RuntimeException("Keys not followed values!");
        }
    }

    public function __serialize(): array
    {
        return [$this->keys, $this->values];
    }

    public function __unserialize(array $data): void
    {
        list($this->keys, $this->values) = $data;
        $this->size = count($this->keys);
        if ($this->size() !== count($this->values)) {
            throw new RuntimeException("Keys not followed values!");
        }
    }
}