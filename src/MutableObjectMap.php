<?php

namespace AG\Collection;

/**
 * @template K
 * @template-covariant V
 * @template-extends ObjectMap<K,V>
 * @template-implements MutableMap<K,V>
 */
class MutableObjectMap extends ObjectMap implements MutableMap
{
    use MutableObjectMapTrait;
}