<?php

namespace AG\Collection;


use RuntimeException;

/**
 * @template T
 * @template-extends ArrayList<T>
 * @template-implements MutableCollection<T>
 * @see ArrayList
 */
class MutableArrayList extends ArrayList implements MutableCollection
{
    use MutableArrayListTrait;

    public function update(int $index, mixed $element): bool
    {
        $this->fillRange($index, 1, $element);
        return true;
    }

    /**
     * @param int<0,max> $start
     * @param int<1,max> $length
     * @param T $element
     * @return void
     */
    public function fillRange(int $start, int $length, mixed $element): void
    {
        $this->checkElementType($element);
        assert($start > -1 && $length > 0 && $this->size() >= $start + $length);
        for ($i = $start; $i < $start + $length; $i++)
            $this->list[$i] = $element;
    }

    public function getRange(int $start, int $length): self
    {
        $collection = parent::getRange($start, $length);
        if ($collection instanceof self) {
            return $collection;
        }
        throw new RuntimeException('Fatal error!');
    }

    public function take(int $count): self
    {
        $collection = parent::take($count);
        if ($collection instanceof self) {
            return $collection;
        }
        throw new RuntimeException('Fatal error!');
    }

    public function where(callable $test): self
    {
        $collection = parent::where($test);
        if ($collection instanceof self) {
            return $collection;
        }
        throw new RuntimeException('Fatal error!');
    }
}