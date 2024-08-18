<?php

namespace AG\Collection;

/**
 * @template-covariant V
 * @template-extends MutableObjectMapTrait<string,V>
 * @template-implements MutableMap<string,V>
 */
class MutableStringMap extends StringMap implements MutableMap
{
    use MutableObjectMapTrait;
}