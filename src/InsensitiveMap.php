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

    public function __construct(null|array|ArrayMap $map = null)
    {
        parent::__construct();
        if (is_array($map)) {
            foreach ($map as $k => $v) $this->checkKeyType($k);
        }
        if (!empty($map)) {
            if ($map instanceof InsensitiveMap) {
                $this->map = $map->toMap();
                $this->size = count($map);
            } else {
                $map = $map instanceof ArrayMap ? $map->toMap() : $map;
                foreach ($map as $k => $v) {
                    $this[$k] = $v;
                }
            }
        }
    }

    /**
     * @param string $key
     * @return ?V
     */
    public function get(string $key): mixed
    {
        return $this[$key];
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        $this->checkKeyType($offset);
        return $this->containsKey($offset);
    }

    /**
     * @param string $offset
     * @return ?V
     */
    public function offsetGet(mixed $offset): mixed
    {
        $this->checkKeyType($offset);
        $entry = $this->findByKey($offset);
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
        $this->checkKeyType($offset);
        if ($this instanceof MutableMap) {
            if (isset($this->map[$offset])) unset($this[$offset]); else $this->size++;
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
            $entry = $this->findByKey($offset);
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
        $this->checkKeyType($key);
        return $this->findByKey($key) !== null;
    }

    /**
     * @param string $key
     * @return ?MapEntry<string, V>
     */
    public function findByKey(mixed $key): ?MapEntry
    {
        $this->checkKeyType($key);
        foreach ($this->map as $k => $v) if (strtolower($k) === strtolower($key)) {
            return new MapEntry($k, $v);
        }
        return null;
    }
}