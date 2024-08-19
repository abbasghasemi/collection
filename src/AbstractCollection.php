<?php

namespace AG\Collection;

use OutOfRangeException;
use RuntimeException;

/**
 * @template T
 * @template-extends Collection<T>
 */
abstract class AbstractCollection implements Collection
{
    use CheckTypeTrait;

    private int $pointer = 0;

    /**
     * @return T
     */
    public function first(): mixed
    {
        return $this->get(0);
    }

    /**
     * @return ?T
     */
    public function firstOrNull(): mixed
    {
        if ($this->isNotEmpty()) {
            return $this->first();
        }
        return null;
    }

    /**
     * @return T
     */
    public function last(): mixed
    {
        return $this->get($this->size() - 1);
    }

    /**
     * @return ?T
     */
    public function lastOrNull(): mixed
    {
        if ($this->isNotEmpty()) {
            return $this->last();
        }
        return null;
    }

    /**
     * @param T $element
     * @param int<0,max> $start
     * @return int
     */
    public function indexOf(mixed $element, int $start = 0): int
    {
        assert($start > -1);
        while ($this->size() > $start) {
            if ($element === $this->get($start)) {
                return $start;
            }
            $start++;
        }
        return -1;
    }

    /**
     * @param T $element
     * @param ?int<0,max> $start
     * @return int
     */
    public function lastIndexOf(mixed $element, ?int $start = null): int
    {
        assert($start === null || $start > -1);
        $start ??= $this->size() - 1;
        while ($start > -1) {
            if ($element === $this->get($start)) {
                return $start;
            }
            $start--;
        }
        return -1;
    }

    /**
     * @param callable(T): bool $test
     * @param int<0,max> $start
     * @return int
     */
    public function indexWhere(callable $test, int $start = 0): int
    {
        assert($start > -1);
        $this->pointer = $start;
        while ($this->valid()) {
            if (call_user_func($test, $this->current())) {
                return $this->key();
            }
            $this->next();
        }
        return -1;
    }

    /**
     * @param callable(T): bool $test
     * @param ?int<0,max> $start
     * @return int
     */
    public function lastIndexWhere(callable $test, ?int $start = null): int
    {
        assert($start === null || $start > -1);
        $start ??= $this->size() - 1;
        while ($start > -1) {
            if ($test($this->get($start))) {
                return $start;
            }
            $start--;
        }
        return -1;
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
     * @param T $element
     * @return bool
     */
    public function contains(mixed $element): bool
    {
        foreach ($this as $i) if ($element === $i) {
            return true;
        }
        return false;
    }

    /**
     * @param callable(T,int): void $callback
     * @return void
     */
    public function forEach(callable $callback): void
    {
        foreach ($this as $i => $v) call_user_func($callback, $v, $i);
    }

    /**
     * @param int<0,max> $start
     * @param int<0,max> $length
     * @return Collection
     */
    public function getRange(int $start, int $length): Collection
    {
        assert($start > -1 && $length > -1);
        if ($start >= $this->size() || $start + $length > $this->size()) {
            throw new OutOfRangeException("Range [$start:$length] is out of bounds! size={$this->size()}");
        }
        return $this->createCollection(array_slice($this->list, $start, $length));
    }

    /**
     * @param int<1,max> $count
     * @return Collection<T>
     */
    public function take(int $count): Collection
    {
        if ($this->isEmpty()) return $this;
        if ($count > $this->size()) $count = $this->size();
        return $this->getRange(0, $count);
    }

    /**
     * @param callable(T): bool $test
     * @param ?callable():T $orElse
     * @return T
     */
    public function firstWhere(callable $test, callable $orElse = null): mixed
    {
        for ($i = 0; $i < $this->size(); $i++) {
            $args = $this->get($i);
            if (call_user_func($test, $args)) {
                return $args;
            }
        }
        if ($orElse === null) {
            throw new RuntimeException("Not found element!");
        }
        return call_user_func($orElse);
    }

    /**
     * @param callable(T): bool $test
     * @return Collection<T>
     */
    public function where(callable $test): Collection
    {
        $list = [];
        $this->forEach(function ($element) use ($test, &$list) {
            if (call_user_func($test, $element)) {
                $list[] = $element;
            }
        });
        return $this->createCollection($list);
    }

    /**
     * @param callable(T): bool $test
     * @param ?callable(): T $orElse
     * @return T
     */
    public function lastWhere(callable $test, callable $orElse = null): mixed
    {
        for ($i = $this->size() - 1; $i > -1; $i--) {
            $args = $this->get($i);
            if (call_user_func($test, $args)) {
                return $args;
            }
        }
        if ($orElse === null) {
            throw new RuntimeException("Not found element!");
        }
        return call_user_func($orElse);
    }

    /**
     * @param callable(T $value,T $element):T $combine
     * @return T
     */
    public function reduce(callable $combine): mixed
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('No element!');
        }
        $this->rewind();
        $value = $this->current();
        $this->next();
        while ($this->valid()) {
            $value = $combine($value, $this->current());
            $this->next();
        }
        return $value;
    }

    public function join(string $separator): string
    {
        return implode($separator, $this->toArray());
    }

    /**
     * @return T
     */
    public function current(): mixed
    {
        return $this->get($this->pointer);
    }

    /**
     * @param Comparator|callable(T,T):int $cmp
     * @return void
     * @example
     * sort('cmp');
     * function cmp(T $a,T $b): int
     * {
     *  if ($a === $b) return 0;
     *  return ($a < $b) ? -1 : 1;
     * }
     */
    public function sort(Comparator|callable $cmp): void
    {
        usort($this->list, $cmp);
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function key(): int
    {
        return $this->pointer;
    }

    public function valid(): bool
    {
        return $this->pointer < $this->size();
    }

    public function rewind(): void
    {
        $this->pointer = 0;
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

    public function count(): int
    {
        return $this->size();
    }

    protected function requiredCheckType(): bool
    {
        return $this->getType() !== null && $this->getType() !== 'mixed';
    }

    protected function equalsType(Collection $collection): bool
    {
        $type = $this->getType();
        return $type === $collection->getType() || $collection->getType() instanceof $type;
    }

    protected function checkElementType(mixed $element): void
    {
        $this->checkType($this->getType(), $element, fn() => "Element type most be '{$this->getType()}'!");
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
        if (!($o instanceof Collection))
            return false;
        $this->rewind();
        $o->rewind();
        while ($this->valid() && $o->valid()) {
            $e1 = $this->current();
            $e2 = $o->current();
            if (!($e1 === null ? $e2 === null : $e1 === $e2))
                return false;
            $this->next();
            $o->next();
        }
        return !($this->valid() || $o->valid());
    }

    public function __toString(): string
    {
        if ($this->isEmpty()) return "[]";
        $str = '';
        $this->rewind();
        while ($this->valid()) {
            $str .= Collections::toString($this->current()) . ',';
            $this->next();
        }
        return "[$str]";
    }

    private function createCollection(array $array): Collection
    {
        if ($this instanceof MutableArrayList) {
            return new MutableArrayList($array, $this->getType());
        } else if ($this instanceof MutableArraySet) {
            return new MutableArraySet($array, $this->getType());
        } else if ($this instanceof ArraySet) {
            return new ArraySet($array, $this->getType());
        }
        return new ArrayList($array, $this->getType());
    }
}