<?php

namespace AG\Collection;

/**
 * @template T
 */
interface Comparator
{
    /**
     * compare
     * @param T $o1
     * @param T $o2
     * @return int
     */
    public function __invoke(mixed $o1, mixed $o2): int;
}