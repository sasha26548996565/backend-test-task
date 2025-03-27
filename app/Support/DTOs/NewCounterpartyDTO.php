<?php

declare(strict_types=1);

namespace App\Support\DTOs;

use App\Support\Traits\Makeable;

final class NewCounterpartyDTO
{
    use Makeable;

    public function __construct(
        public readonly string $name,
        public readonly string $ogrn,
        public readonly string $address,
        public readonly int $user_id
    ) {}
}
