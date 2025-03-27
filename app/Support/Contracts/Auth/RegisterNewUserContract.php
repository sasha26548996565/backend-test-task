<?php

declare(strict_types=1);

namespace App\Support\Contracts\Auth;

use App\Models\User;
use App\Support\DTOs\NewUserDTO;

interface RegisterNewUserContract
{
    public function __invoke(NewUserDTO $params): User;
}
