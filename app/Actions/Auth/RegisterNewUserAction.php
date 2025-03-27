<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use App\Support\DTOs\NewUserDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Support\Contracts\Auth\RegisterNewUserContract;

final class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(NewUserDTO $params): User
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $params->name,
                'email' => $params->email,
                'password' => Hash::make($params->password)
            ]);

            DB::commit();

            return $user;
        } catch (\Throwable $exception) {
            DB::rollBack();
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
