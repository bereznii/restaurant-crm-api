<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/register",
     *     tags={"Auth"},
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               required={"name","email","password"},
     *               @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Имя пользователя"
     *               ),
     *               @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Имейл"
     *               ),
     *               @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  description="Пароль"
     *               )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="access_token",
     *                         type="string",
     *                         description="Токен"
     *                     ),
     *                   @OA\Property(
     *                         property="token_type",
     *                         type="string",
     *                         description="Тип токена"
     *                     ),
     *                     example={
     *                      "access_token": "10|ZWYsDHtiSuV7MBADO1FLUe5AX42TYBUuFbRjUqEE",
     *                      "token_type": "Bearer"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/login",
     *     tags={"Auth"},
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               required={"email","password"},
     *               @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Имейл"
     *               ),
     *               @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  description="Пароль"
     *               )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="access_token",
     *                         type="string",
     *                         description="Токен"
     *                     ),
     *                   @OA\Property(
     *                         property="token_type",
     *                         type="string",
     *                         description="Тип токена"
     *                     ),
     *                     example={
     *                      "access_token": "10|ZWYsDHtiSuV7MBADO1FLUe5AX42TYBUuFbRjUqEE",
     *                      "token_type": "Bearer"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/me",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         description="ID"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Имя"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         description="Имейл"
     *                     ),
     *                     @OA\Property(
     *                         property="email_verified_at",
     *                         type="string",
     *                         description="Имейл"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         description="Имейл"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         description="Имейл"
     *                     ),
     *                     example={
     *                      "id": "10",
     *                      "name": "Administrator",
     *                      "email": "admin@smaki.com",
     *                      "created_at": "2021-07-24T12:47:09.000000Z",
     *                      "updated_at": "2021-07-24T12:47:09.000000Z",
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     *
     * @param Request $request
     * @return mixed
     */
    public function me(Request $request)
    {
        return $request->user();
    }
}
