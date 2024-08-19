<?php

namespace AG\Collection;

/**
 * @template T
 * @template-extends Collection<T>
 * @see ArrayList,MutableArrayList
 */
interface MutableCollection extends Collection
{
    /**
     * @param T $element
     * @return void
     */
    public function add(mixed $element): void;

    /**
     * @param Collection|array $elements
     * @return void
     */
    public function addAll(Collection|array $elements): void;

    /**
     * @param int<0,max> $index
     * @param T $element
     * @return void
     */
    public function insert(int $index, mixed $element): void;

    /**
     * @param int<0,max> $index
     * @param Collection<T>|T[] $elements
     * @return void
     */
    public function insertAll(int $index, Collection|array $elements): void;


    /**
     * @param int<0,max> $index
     * @param T $element
     * @return bool
     */
    public function update(int $index, mixed $element): bool;

    /**
     * @param int<0,max> $index
     * @return mixed
     */
    public function remove(int $index): mixed;

    /**
     * @return T
     */
    public function removeFirst(): mixed;

    /**
     * @return T
     */
    public function removeLast(): mixed;

    /**
     * @param int<0,max> $start
     * @param int<1,max> $length
     * @return void
     */
    public function removeRange(int $start, int $length): void;

    /**
     * @param callable(T): bool $test
     * @return void
     */
    public function removeWhere(callable $test): void;

    public function clear(): void;
}