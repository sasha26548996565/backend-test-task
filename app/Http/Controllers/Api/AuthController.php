<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\User;
use OpenApi\Annotations as OA;
use Illuminate\Http\Response;
use App\Support\DTOs\NewUserDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Support\Contracts\Auth\RegisterNewUserContract;

/**
 * @OA\Info(
 *     title="Authentication API",
 *     version="1.0.0",
 *     description="API для регистрации и авторизации пользователей"
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="Методы аутентификации"
 * )
 */
class AuthController extends Controller
{
    private const TOKEN_NAME = 'API TOKEN';

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Регистрация нового пользователя",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Иван Иванов"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="securepassword"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="securepassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Пользователь успешно зарегистрирован",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function register(RegisterRequest $request, RegisterNewUserContract $action): JsonResponse
    {
        $params = $request->validated();

        $user = $action(NewUserDTO::make(...$params));

        return response()->json([
            'user' => $user,
            'token' => $this->generateApiToken($user, self::TOKEN_NAME),
            'status' => true
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Авторизация пользователя",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="securepassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный вход в систему",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Ошибка авторизации"
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $params = $request->validated();

        if (Auth::attempt($params) == false) {
            return response()->json([
                'status' => false,
                'error' => 'user not found'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $params['email'])->first();
        $token = $this->generateApiToken($user, self::TOKEN_NAME);

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user,
        ], Response::HTTP_OK);
    }

    private function generateApiToken(User $user, string $name): string
    {
        return $user->createToken($name)->plainTextToken;
    }
}
