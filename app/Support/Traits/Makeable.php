<?php

declare(strict_types=1);

namespace App\Support\Traits;

trait Makeable
{
    public static function make(mixed ...$params): static
    {
        return new static(...$params);
    }
}
