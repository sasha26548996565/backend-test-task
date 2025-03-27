<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use OpenApi\Annotations as OA;
use App\Models\Counterparty;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Support\DTOs\NewCounterpartyDTO;
use App\Services\Dadata\CounterpartyService;
use App\Http\Requests\Counterparty\SearchRequest;
use App\Support\Contracts\Counterparty\StoreCounterpartyContract;

/**
 * @OA\Tag(
 *     name="Counterparty",
 *     description="Управление контрагентами"
 * )
 */
class CounterpartyController extends Controller
{
    public function __construct(
        private CounterpartyService $counterpartyService
    ) {}

    /**
     * Получить список контрагентов.
     *
     * @OA\Get(
     *     path="/api/counterparties",
     *     summary="Получить список контрагентов",
     *     tags={"Counterparty"},
     *     @OA\Response(
     *         response=200,
     *         description="Список контрагентов",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="counterparties", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="ООО Ромашка"),
     *                     @OA\Property(property="ogrn", type="string", example="1027700132195"),
     *                     @OA\Property(property="address", type="string", example="г. Москва, ул. Ленина, д. 10"),
     *                     @OA\Property(property="user_id", type="integer", example=42),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getList(): JsonResponse
    {
        $counterparties = Counterparty::orderByDesc('id')->get();

        return response()->json([
            'status' => true,
            'counterparties' => $counterparties,
        ]);
    }

    /**
     * Создать нового контрагента.
     *
     * @OA\Post(
     *     path="/api/counterparties",
     *     summary="Создать контрагента",
     *     tags={"Counterparty"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"inn", "user_id"},
     *             @OA\Property(property="inn", type="string", example="7707083893"),
     *             @OA\Property(property="user_id", type="integer", example=42)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Контрагент успешно создан",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="counterparty", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="ООО Ромашка"),
     *                 @OA\Property(property="ogrn", type="string", example="1027700132195"),
     *                 @OA\Property(property="address", type="string", example="г. Москва, ул. Ленина, д. 10"),
     *                 @OA\Property(property="user_id", type="integer", example=42),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Контрагент не найден",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="counterparty not found")
     *         )
     *     )
     * )
     */
    public function store(SearchRequest $request, StoreCounterpartyContract $action): JsonResponse
    {
        $searchParams = $request->validated();
        $companyData = $this->counterpartyService->getCompanyData(
            config('services.dadata.api_key'),
            $searchParams['inn']
        );

        if (empty($companyData)) {
            return response()->json([
                'status' => false,
                'message' => 'counterparty not found',
            ]);
        }

        $companyData['user_id'] = (int) $searchParams['user_id'];
        $counterparty = $action(NewCounterpartyDTO::make(...$companyData));

        return response()->json([
            'status' => true,
            'counterparty' => $counterparty,
        ], Response::HTTP_OK);
    }
}
