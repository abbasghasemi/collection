<?php

namespace AG\Collection;

/**
 * @template K
 * @template V
 */
interface Entry
{

    /**
     * @return K
     */
    function getKey(): mixed;

    /**
     * @return V
     */
    function getValue(): mixed;

    /**
     * @param K $key
     * @param V $value
     * @return void
     */
    function setEntry(mixed $key, mixed $value):void;

}