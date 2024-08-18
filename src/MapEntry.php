<?php

namespace AG\Collection;

/**
 * @template K
 * @template-covariant V
 * @template-implements Entry<K,V>
 */
class MapEntry implements Entry
{

    /**
     * @param K $key
     * @param V $value
     */
    public function __construct(
        private mixed $key,
        private mixed $value,
    )
    {
    }

    /**
     * @return K
     */
    function getKey(): mixed
    {
        return $this->key;
    }

    /**
     * @return V
     */
    function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param K $key
     * @param V $value
     * @return void
     */
    function setEntry(mixed $key, mixed $value): void
    {
        $this->key = $key;
        $this->value = $value;
    }
}