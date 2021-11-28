<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Auth"},
     *     summary="Системная точка. Создать пользователя извне",
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               required={"first_name","last_name","email","password","phone","position"},
     *               @OA\Property(
     *                  property="first_name",
     *                  type="string",
     *                  description="Имя пользователя"
     *               ),
     *               @OA\Property(
     *                  property="last_name",
     *                  type="string",
     *                  description="Фамилия"
     *               ),
     *               @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Имейл"
     *               ),
     *               @OA\Property(
     *                  property="phone",
     *                  type="string",
     *                  description="Телефон"
     *               ),
     *               @OA\Property(
     *                  property="position",
     *                  type="string",
     *                  description="Должность"
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
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'position' => $request->input('position'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Логин",
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               required={"phone","password"},
     *               @OA\Property(
     *                  property="phone",
     *                  type="string",
     *                  description="Телефон"
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
        if (!Auth::attempt($request->only('phone', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $user = User::where('phone', $request->input('phone'))->firstOrFail();

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/me",
     *     tags={"Auth"},
     *     security={{"Bearer":{}}},
     *     summary="Получить данные для текущего пользователя, сделавшего запрос",
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
     *                         property="email",
     *                         type="string",
     *                         description="Имейл"
     *                     ),
     *                     @OA\Property(
     *                         property="phone",
     *                         type="string",
     *                         description="Телефон"
     *                     ),
     *                     @OA\Property(
     *                         property="position",
     *                         type="string",
     *                         description="Должность"
     *                     ),
     *                     @OA\Property(
     *                         property="first_name",
     *                         type="string",
     *                         description="Имя"
     *                     ),
     *                     @OA\Property(
     *                         property="last_name",
     *                         type="string",
     *                         description="Фамилия"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         description="Статус"
     *                     ),
     *                      @OA\Property(
     *                         property="role_name",
     *                         type="string",
     *                         description="Идентификатор роли"
     *                      ),
     *                      @OA\Property(
     *                         property="role_title",
     *                         type="string",
     *                         description="Развание роли"
     *                      ),
     *                     @OA\Property(
     *                         property="email_verified_at",
     *                         type="string",
     *                         description="Дата подтверждения кредов"
     *                     ),
     *                     @OA\Property(
     *                         property="locations",
     *                         type="array",
     *                         description="Массив сущностей локаций, к которым привязан пользователь",
     *                         @OA\Items(
     *                             type="object"
     *                         ),
     *                     ),
     *                     @OA\Property(
     *                         property="iiko",
     *                         type="array",
     *                         description="Данные из iiko CRM (для пользователей с ролью Courier)",
     *                         @OA\Items(
     *                             type="object"
     *                         ),
     *                     ),
     *                      @OA\Property(
     *                         property="kitchen_code",
     *                         type="string",
     *                         description="Идентификатор физической кухни"
     *                      ),
     *                      @OA\Property(
     *                         property="kitchen_name",
     *                         type="string",
     *                         description="Название физической кухни"
     *                      ),
     *                      @OA\Property(
     *                         property="product_types",
     *                         type="array",
     *                         description="Типы товаров для повара",
     *                         @OA\Items(
     *                             type="string",
     *                         )
     *                      ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         description="Дата создания"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         description="Дата последнего редактирования"
     *                     ),
     *                     example={"data":{
     *                      "id": 1,
     *                      "email": "admin@smaki.com",
     *                      "phone": 380973334455,
     *                      "position": "Developer",
     *                      "first_name": "John",
     *                      "last_name": "Doe",
     *                      "status": "active",
     *                      "role_name": "content_manager",
     *                      "role_title": "Контент-менеджер",
     *                      "email_verified_at": "2021-07-24T12:47:09.000000Z",
     *                      "locations": {{
     *                          "id": 1,
     *                          "restaurant": "smaki",
     *                          "name": "Кульпарковская Смаки",
     *                          "city_sync_id": "lviv",
     *                          "city": "Львов",
     *                          "street": "ул. Кульпарковская",
     *                          "house_number": "95",
     *                          "latitude": null,
     *                          "longitude": null,
     *                          "created_at": "2021-07-28T11:08:01.000000Z",
     *                          "updated_at": "2021-07-28T11:08:01.000000Z"
     *                      }},
     *                      "iiko": {
     *                          "iiko_id": "8f423953-8d9e-47c5-a409-1e7cb33c6f00",
     *                          "status": "waiting",
     *                          "current_delivery_id": null,
     *                          "created_at": "2021-08-25T20:36:13.000000Z",
     *                          "updated_at": "2021-08-25T20:36:13.000000Z"
     *                      },
     *                      "kitchen_code": "sumy",
     *                      "kitchen_name": "Суми",
     *                      "product_types": {"pizza"},
     *                      "created_at": "2021-07-24T12:47:09.000000Z",
     *                      "updated_at": "2021-07-24T12:47:09.000000Z",
     *                     }}
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param Request $request
     * @return mixed
     */
    public function me(Request $request)
    {
        return new UserResource(
            User::with('roles', 'iiko', 'locations', 'kitchen')->findOrFail(Auth::id())
        );
//        return $request->user();
    }
}
