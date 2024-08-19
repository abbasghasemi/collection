<?php declare(strict_types=1);


namespace AG\Collection;

use TypeError;

trait CheckTypeTrait
{

    /**
     * @param ?string $type
     * @param mixed $goal
     * @param callable $onError
     * @return void
     */
    protected function checkType(?string $type, mixed $goal, callable $onError): void
    {
        if ($type !== null && !match ($type) {
                'string' => is_string($goal),
                'object' => is_object($goal),
                'int', 'long' => is_int($goal),
                'bool' => is_bool($goal),
                'array' => is_array($goal),
                'callable' => is_callable($goal),
                'scalar' => is_scalar($goal),
                'float', 'double' => is_float($goal),
                'numeric' => is_numeric($goal),
                'resource' => is_resource($goal),
                'null' => $goal === null,
                'mixed' => true,
                default => $goal instanceof $type,
            }) throw new TypeError($onError());
    }
}
