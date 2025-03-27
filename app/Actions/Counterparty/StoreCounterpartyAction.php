<?php

declare(strict_types=1);

namespace App\Actions\Counterparty;

use App\Models\Counterparty;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Support\DTOs\NewCounterpartyDTO;
use App\Support\Contracts\Counterparty\StoreCounterpartyContract;

final class StoreCounterpartyAction implements StoreCounterpartyContract
{
    public function __invoke(NewCounterpartyDTO $params): Counterparty
    {
        DB::beginTransaction();

        try {
            $counterparty = Counterparty::create([
                'name' => $params->name,
                'ogrn' => $params->ogrn,
                'address' => $params->address,
                'user_id' => $params->user_id
            ]);

            DB::commit();

            return $counterparty;
        } catch (\Throwable $exception) {
            DB::rollBack();
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
