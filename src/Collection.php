<?php

namespace AG\Collection;

/**
 * @template T
 * @template-extends BaseArray<int,T>
 * @see ArrayList,MutableArrayList
 */
interface Collection extends BaseArray
{
    /**
     * @param T $element
     * @return bool
     */
    public function contains(mixed $element): bool;

    /**
     * @return T
     */
    public function first(): mixed;

    /**
     * @return ?T
     */
    public function firstOrNull(): mixed;

    /**
     * @return T
     */
    public function last(): mixed;

    /**
     * @return ?T
     */
    public function lastOrNull(): mixed;

    /**
     * @param int $index
     * @return T
     */
    public function get(int $index): mixed;

    public function getRange(int $start, int $length): self;

    public function take(int $count): self;

    /**
     * @param callable(T): bool $test
     * @param ?callable():T $orElse
     * @return T
     */
    public function firstWhere(callable $test, callable $orElse = null): mixed;

    /**
     * @param callable(T): bool $test
     * @return self<T>
     */
    public function where(callable $test): self;

    /**
     * @param callable(T): bool $test
     * @param ?callable(): T $orElse
     * @return T
     */
    public function lastWhere(callable $test, callable $orElse = null): mixed;

    /**
     * @param callable(T $value,T $element):T $combine
     * @return T
     */
    public function reduce(callable $combine): mixed;

    /**
     * @param T $element
     * @param int<0,max> $start
     * @return int
     */
    public function indexOf(mixed $element, int $start = 0): int;

    /**
     * @param T $element
     * @param ?int<0,max> $start
     * @return int
     */
    public function lastIndexOf(mixed $element, ?int $start = null): int;

    /**
     * @param callable(T): bool $test
     * @param int<0,max> $start
     * @return int
     */
    public function indexWhere(callable $test, int $start = 0): int;

    /**
     * @param callable(T): bool $test
     * @param ?int<0,max> $start
     * @return int
     */
    public function lastIndexWhere(callable $test, ?int $start = null): int;

    public function sort(Comparator|callable $cmp): void;

    public function toArray(): array;

    public function getType(): ?string;
}