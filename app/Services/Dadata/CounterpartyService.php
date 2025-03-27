<?php

declare(strict_types=1);

namespace App\Services\Dadata;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

final class CounterpartyService
{
    private const DADATA_URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party';

    public function getCompanyData(string $token, string $inn): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $token,
                'Content-Type' => 'application/json',
            ])->post(self::DADATA_URL, ['query' => $inn]);

            $result = $response->json();

            if (empty($result['suggestions'])) {
                Log::info('dadata api: counterparty not found');
                return [];
            }

            $company = $result['suggestions'][0]['data'];

            return [
                'name' => $company['name']['short_with_opf'] ?? null,
                'ogrn' => $company['ogrn'] ?? null,
                'address' => $company['address']['unrestricted_value'] ?? null,
            ];
        } catch (\Throwable $exception) {
            Log::error('dadata api error: ' . $exception->getMessage(), [
                'inn' => $inn,
                'trace' => $exception->getTraceAsString()
            ]);

            return [];
        }
    }
}
