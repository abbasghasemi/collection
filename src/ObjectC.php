<?php
namespace AG\Collection;
interface ObjectC {
    public function hashCode(): int;

    public function equals(mixed $o): bool;
}