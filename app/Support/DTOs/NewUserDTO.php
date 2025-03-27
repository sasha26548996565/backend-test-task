<?php

declare(strict_types=1);

namespace App\Support\DTOs;

use App\Support\Traits\Makeable;

final class NewUserDTO
{
    use Makeable;

    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {}
}
