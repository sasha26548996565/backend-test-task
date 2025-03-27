<?php

declare(strict_types=1);

namespace App\Support\Contracts\Counterparty;

use App\Models\Counterparty;
use App\Support\DTOs\NewCounterpartyDTO;

interface StoreCounterpartyContract
{
    public function __invoke(NewCounterpartyDTO $params): Counterparty;
}
