<?php

namespace AG\Collection;

use RuntimeException;

/**
 * @template T
 * @template-extends ArrayList<T>
 */
class ArraySet extends ArrayList
{
    public function __construct(
        null|Collection|array $list = null,
        ?string               $type = null,
    )
    {
        parent::__construct(null, $type);
        if (!empty($list)) {
            if ($list instanceof ArraySet) {
                $this->list = $list->toArray();
                $this->size = $this->size();
                if ($this->requiredCheckType() && !$this->equalsType($list))
                    foreach ($this->list as $element)
                        $this->checkElementType($element);
            } else {
                $checkType = $this->requiredCheckType();
                if ($list instanceof Collection) {
                    if ($checkType && $this->equalsType($list)) $checkType = false;
                    $list = $list->toArray();
                } else {
                    $list = array_values($list);
                }
                foreach ($list as $element) {
                    if (!in_array($element, $this->list, true)) {
                        if ($checkType) $this->checkElementType($element);
                        $this->list[] = $element;
                        $this->size++;
                    }
                }
            }
        }
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