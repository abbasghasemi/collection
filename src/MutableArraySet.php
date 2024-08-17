<?php

namespace AG\Collection;


use RuntimeException;

/**
 * @template T
 * @template-extends ArraySet<T>
 * @template-implements MutableCollection<T>
 */
class MutableArraySet extends ArraySet implements MutableCollection
{
    use MutableArrayListTrait;


    /**
     * @param int $index
     * @param T $element
     * @return void
     */
    public function insert(int $index, mixed $element): void
    {
        if (!in_array($element, $this->list, true)) {
            assert($index > -1);
            array_splice($this->list, $index, 0, $element);
            $this->size++;
        }
    }

    /**
     * @param int $index
     * @param Collection<T>|T[] $elements
     * @return void
     */
    public function insertAll(int $index, Collection|array $elements): void
    {
        assert($index > -1);
        $check = !($elements instanceof ArraySet);
        if ($elements instanceof Collection) {
            $elements = $elements->toArray();
        } else {
            $elements = array_values($elements);
        }
        if ($check) {
            $list = [];
            foreach ($elements as $element) {
                if (!in_array($element, $this->list, true)) {
                    $list[] = $element;
                }
            }
            $elements = $list;
        }
        array_splice($this->list, $index, 0, $elements);
        $this->size += count($elements);
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