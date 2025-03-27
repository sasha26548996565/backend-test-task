<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Actions\Auth\RegisterNewUserAction;
use App\Actions\Counterparty\StoreCounterpartyAction;
use App\Support\Contracts\Auth\RegisterNewUserContract;
use App\Support\Contracts\Counterparty\StoreCounterpartyContract;

class ActionsServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RegisterNewUserContract::class => RegisterNewUserAction::class,
        StoreCounterpartyContract::class => StoreCounterpartyAction::class,
    ];
}
