<?php

namespace AG\Collection;

class Collections
{
    /**
     * @template T
     * @param int<0,max> $length
     * @param T $element
     * @return ArrayList<T>
     */
    public static function filled(int $length, mixed $element): ArrayList
    {
        assert($length > -1);
        $list = [];
        while ($length > 0) {
            $list[] = $element;
            $length--;
        }
        return new ArrayList($list);
    }

    /**
     * @template T
     * @param int<0,max> $length
     * @param callable(int):T $callback
     * @return ArrayList<T>
     */
    public static function generate(int $length, callable $callback): ArrayList
    {
        assert($length > -1);
        $i = 0;
        $list = [];
        while ($length > $i) {
            $list[] = $callback($i);
            $i++;
        }
        return new ArrayList($list);
    }

    /**
     * @template T
     * @param T ...$elements
     * @return ArrayList<T>
     */
    public static function of(mixed ...$elements): ArrayList
    {
        return new ArrayList($elements);
    }

    /**
     * @template T
     * @param int<0,max> $length
     * @param T $element
     * @return MutableArrayList<T>
     */
    public static function mutableFilled(int $length, mixed $element): MutableArrayList
    {
        assert($length > -1);
        $list = [];
        while ($length > 0) {
            $list[] = $element;
            $length--;
        }
        return new MutableArrayList($list);
    }

    /**
     * @template T
     * @param int<0,max> $length
     * @param callable(int):T $callback
     * @return MutableArrayList<T>
     */
    public static function mutableGenerate(int $length, callable $callback): MutableArrayList
    {
        assert($length > -1);
        $i = 0;
        $list = [];
        while ($length > $i) {
            $list[] = $callback($i);
            $i++;
        }
        return new MutableArrayList($list);
    }

    /**
     * @template T
     * @param T ...$elements
     * @return MutableArrayList<T>
     */
    public static function mutableOf(mixed ...$elements): MutableArrayList
    {
        return new MutableArrayList($elements);
    }

    /**
     * @template T
     * @param Collection<T> $collection
     * @return ArrayList<T>
     */
    public static function toArrayList(Collection $collection): ArrayList
    {
        if ($collection instanceof ArrayList) {
            return $collection;
        }
        return new ArrayList($collection->toArray());
    }

    /**
     * @template T
     * @param Collection<T> $collection
     * @return MutableArrayList<T>
     */
    public static function toMutableArrayList(Collection $collection): MutableArrayList
    {
        if ($collection instanceof MutableArrayList) {
            return $collection;
        }
        return new MutableArrayList($collection->toArray());
    }

    /**
     * @template T
     * @param Collection<T> $collection
     * @return ArraySet<T>
     */
    public static function toArraySet(Collection $collection): ArraySet
    {
        if ($collection instanceof ArraySet) {
            return $collection;
        }
        return new ArraySet($collection->toArray());
    }

    /**
     * @template T
     * @param Collection<T> $collection
     * @return MutableArraySet<T>
     */
    public static function toMutableArraySet(Collection $collection): MutableArraySet
    {
        if ($collection instanceof MutableArraySet) {
            return $collection;
        }
        return new MutableArraySet($collection->toArray());
    }

    /**
     * @template T
     * @param MutableCollection<T> $collection
     * @return void
     */
    public static function sortAscending(MutableCollection $collection): void
    {
        $collection->sort(function ($e1, $e2) {
            return $e1 <=> $e2;
        });
    }

    /**
     * @template T
     * @param MutableCollection<T> $collection
     * @return void
     */
    public static function sortDescending(MutableCollection $collection): void
    {
        $collection->sort(function ($e1, $e2) {
            return $e2 <=> $e1;
        });
    }

    public static function equals(?ObjectC $a, ?ObjectC $b): bool
    {
        if ($a === $b) {
            return true;
        }
        return $a === null ? $b === null : $a->equals($b);
    }

    public static function hashCode(mixed $value): int
    {
        if (is_object($value)) return spl_object_id($value);
        if (is_array($value)) {
            $hash = 0;
            foreach ($value as $v) {
                $hash = 31 * $hash + self::hashCode($v);
            }
            return $hash;
        }
        if (!is_string($value)) {
            $value = strval($value);
        }
        $hash = 0;
        $stringLength = strlen($value);
        for ($i = 0; $i < $stringLength; $i++) {
            $hash = 31 * $hash + $value[$i];
        }
        return $hash;
    }

    public static function toString(mixed $value)
    {
        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return $value->__toString();
            }
            return "Object(" . get_class($value) . ")";
        }
        if (is_array($value)) {
            $array = [];
            foreach ($value as $v) {
                $array[] = self::toString($v);
            }
            return "[" . implode(',', $array) . "]";
        }
        return strval($value);
    }
}