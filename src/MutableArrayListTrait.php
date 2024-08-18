<?php

namespace AG\Collection;

use OutOfRangeException;

/**
 * @template T
 */
trait MutableArrayListTrait
{
    /**
     * @param ?T $element
     * @return void
     */
    public function add(mixed $element): void
    {
        $this->insert($this->size(), $element);
    }

    /**
     * @param Collection|T[] $elements
     * @return void
     */
    public function addAll(Collection|array $elements): void
    {
        $this->insertAll($this->size(), $elements);
    }

    /**
     * @param int<0,max> $index
     * @param T $element
     * @return void
     */
    public function insert(int $index, mixed $element): void
    {
        assert($index > -1);
        array_splice($this->list, $index, 0, $element);
        $this->size++;
    }

    /**
     * @param int<0,max> $index
     * @param Collection<T>|T[] $elements
     * @return void
     */
    public function insertAll(int $index, Collection|array $elements): void
    {
        assert($index > -1);
        $elements = $elements instanceof Collection ? $elements->toArray() : array_values($elements);
        array_splice($this->list, $index, 0, $elements);
        $this->size += count($elements);
    }

    /**
     * @param int<0,max> $index
     * @return T
     */
    public function remove(int $index): mixed
    {
        assert($index > -1 && $this->size > $index);
        $this->size--;
        return array_shift($this->list);
    }

    /**
     * @return T
     */
    public function removeFirst(): mixed
    {
        return $this->remove(0);
    }

    /**
     * @return T
     */
    public function removeLast(): mixed
    {
        return $this->remove($this->size() - 1);
    }

    /**
     * @param int<0,max> $start
     * @param int<1,max> $length
     * @return void
     */
    public function removeRange(int $start, int $length): void
    {
        assert($start > -1 && $length > 0);
        if ($start >= $this->size() || $start + $length > $this->size()) {
            throw new OutOfRangeException("Range [$start:$length] is out of bounds! size={$this->size()}");
        }
        for ($i = $start; $i < $start + $length; $i++)
            unset($this->list[$i]);
        $this->list = array_values($this->toArray());
        $this->size -= $length;
    }

    /**
     * @param callable(T): bool $test
     * @return void
     */
    public function removeWhere(callable $test): void
    {
        $this->rewind();
        while ($this->valid()) {
            if (call_user_func($test, $this->current())) {
                $this->remove($this->key());
            } else $this->next();
        }
    }

    /**
     * @return void
     */
    public function reversed(): void
    {
        $this->list = array_reverse($this->list);
    }

    public function shuffle(): void
    {
        shuffle($this->list);
    }

    public function clear(): void
    {
        $this->list = [];
        $this->size = 0;
        $this->rewind();
    }
}