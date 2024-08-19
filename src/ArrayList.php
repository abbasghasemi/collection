<?php
declare(strict_types=1);

namespace AG\Collection;

use JsonSerializable;
use OutOfRangeException;
use RuntimeException;
use Serializable;

/**
 * @template T
 * @template-extends AbstractCollection<T>
 * @see MutableArrayList
 */
class ArrayList extends AbstractCollection implements JsonSerializable, Serializable
{

    /**
     * @var T[]
     */
    protected array $list;

    protected int $size = 0;

    public function __construct(
        null|Collection|array    $list = null,
        private readonly ?string $type = null,
    )
    {
        if (!empty($list)) {
            $checkType = $this->requiredCheckType();
            if ($list instanceof Collection) {
                $this->list = $list->toArray();
                $this->size = $this->size();
                if ($checkType && $this->equalsType($list)) $checkType = false;
            } else {
                $this->list = array_values($list);
                $this->size = count($list);
            }
            if ($checkType) foreach ($this->list as $element)
                $this->checkElementType($element);
        } else {
            $this->list = array();
        }
    }

    /**
     * @param int<0,max> $index
     * @return T
     */
    public function get(int $index): mixed
    {
        assert($index > -1);
        if ($index < $this->size()) {
            return $this->list[$index];
        }
        throw new OutOfRangeException("Index $index is out of bounds! size={$this->size()}");
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

    public function size(): int
    {
        return $this->size;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return T[]
     */
    public function toArray(): array
    {
        return $this->list;
    }

    /**
     * @return ArraySet<T>
     */
    public function toArraySet(): ArraySet
    {
        return new ArraySet($this, $this->getType());
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function serialize(): ?string
    {
        return serialize($this->list);
    }

    public function unserialize(string $data): void
    {
        $this->list = unserialize($data);
        $this->size = count($this->list);
    }

    public function __serialize(): array
    {
        return [$this->list];
    }

    public function __unserialize(array $data): void
    {
        $this->list = $data[0];
        $this->size = count($this->list);
    }
}