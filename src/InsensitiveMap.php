<?php
declare(strict_types=1);

namespace AG\Collection;

use RuntimeException;

/**
 * @template-covariant V
 * @template-extends StringMap<V>
 */
class InsensitiveMap extends StringMap
{

    public function __construct(
        null|array|Map $map = null,
        ?string        $type = null,
    )
    {
        parent::__construct(null, $type);
        if (!empty($map)) {
            if (is_array($map) || ($map instanceof Map && (
                        !$this->equalsKeyType($map) ||
                        $this->requiredCheckValueType() && !$this->equalsValueType($map)
                    ))) {
                foreach ($map as $k => $v) $this->checkKeyValueType($k, $v);
            }
            if ($map instanceof InsensitiveMap) {
                $this->map = $map->toMap();
                $this->size = count($this->map);
            } else {
                $map = $map instanceof AbstractStringMap ? $map->toMap() :
                    ($map instanceof Map ? array_combine($map->keys()->toArray(), $map->values()->toArray()) : $map);
                foreach ($map as $k => $v) {
                    $this[$k] = $v;
                }
            }
        }
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->containsKey($offset);
    }

    /**
     * @param string $offset
     * @return ?V
     */
    public function offsetGet(mixed $offset): mixed
    {
        $entry = $this->entryKey($offset);
        if (!empty($entry)) {
            return $entry->getValue();
        }
        return null;
    }

    /**
     * @param string $offset
     * @param V $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this instanceof MutableMap) {
            $this->checkKeyValueType($offset, $value);
            $entry = $this->entryKey($offset);
            if (empty($entry)) $this->size++; else unset($this->map[$entry->getKey()]);
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
            $entry = $this->entryKey($offset);
            if (!empty($entry)) {
                $this->size--;
                unset($this->map[$entry->getKey()]);
            }
        } else throw new RuntimeException(get_called_class() . ' is unchanged!, please use the ' . MutableStringMap::class . '.');
    }

    /**
     * @param string $key
     * @return bool
     */
    public function containsKey(mixed $key): bool
    {
        return $this->entryKey($key) !== null;
    }

    /**
     * @param string $key
     * @return ?MapEntry<string, V>
     */
    public function entryKey(mixed $key): ?MapEntry
    {
        if (is_string($key)) foreach ($this->map as $k => $v) if (strtolower($k) === strtolower($key)) {
            return new MapEntry($k, $v);
        }
        return null;
    }
}