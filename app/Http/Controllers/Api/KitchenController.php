<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCollection;
use App\Http\Resources\Users\UserCollection;
use App\Models\Kitchen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KitchenController extends Controller
{
    /**
     * @OA\Get(
     *     path="/kitchens",
     *     tags={"Kitchens"},
     *     security={{"Bearer":{}}},
     *     summary="Список физических кухонь",
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="string",
     *                         description="ID"
     *                     ),
     *                     @OA\Property(
     *                         property="code",
     *                         type="string",
     *                         description="Текстовый идентификатор кухни"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Название кухни"
     *                     ),
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
     *                          {
     *                              "id": 1,
     *                              "code": "kulparkivska",
     *                              "title": "Кульпарківська",
     *                              "created_at": "2021-09-02T13:13:03.000000Z",
     *                              "updated_at": "2021-09-02T13:13:03.000000Z"
     *                          }
     *                      }}
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return DefaultCollection
     */
    public function index(Request $request)
    {
        return new DefaultCollection(
            Kitchen::get()
        );
    }

    /**
     * @OA\Get(
     *     path="/kitchens/{kitchen_code}/couriers",
     *     tags={"Kitchens"},
     *     security={{"Bearer":{}}},
     *     summary="Список курьеров привязанных к кухне",
     *     @OA\Parameter(
     *         name="kitchen_code",
     *         in="path",
     *         description="Код кухни",
     *         required=true
     *     ),
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
     *                     @OA\Property(
     *                         property="email_verified_at",
     *                         type="string",
     *                         description="Дата подтверждения кредов"
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
     *                         property="locations",
     *                         type="array",
     *                         description="Массив сущностей локаций, к которым привязан пользователь",
     *                         @OA\Items(
     *                             type="object"
     *                         ),
     *                     ),
     *                      @OA\Property(
     *                         property="kitchen_code",
     *                         type="string",
     *                         description="Идентификатор физической кухни"
     *                      ),
     *                     @OA\Property(
     *                         property="iiko",
     *                         type="array",
     *                         description="Данные из iiko CRM (для пользователей с ролью Courier)",
     *                         @OA\Items(
     *                             type="object"
     *                         ),
     *                     ),
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
     *                      {
     *                          "id": "1",
     *                          "email": "admin@smaki.com",
     *                          "phone": "380973334455",
     *                          "position": "Developer",
     *                          "first_name": "John",
     *                          "last_name": "Doe",
     *                          "status": "active",
     *                          "role_name": "content_manager",
     *                          "role_title": "Контент-менеджер",
     *                          "email_verified_at": "2021-07-24T12:47:09.000000Z",
     *                          "locations": {{
     *                              "id": 1,
     *                              "restaurant": "smaki",
     *                              "name": "Кульпарковская Смаки",
     *                              "city_sync_id": "lviv",
     *                              "city": "Львов",
     *                              "street": "ул. Кульпарковская",
     *                              "house_number": "95",
     *                              "latitude": null,
     *                              "longitude": null,
     *                              "created_at": "2021-07-28T11:08:01.000000Z",
     *                              "updated_at": "2021-07-28T11:08:01.000000Z"
     *                          }},
     *                          "iiko": {
     *                              "iiko_id": "8f423953-8d9e-47c5-a409-1e7cb33c6f00",
     *                              "created_at": "2021-08-25T20:36:13.000000Z",
     *                              "updated_at": "2021-08-25T20:36:13.000000Z"
     *                          },
     *                          "kitchen_code": "sumy",
     *                          "kitchen_name": "Суми",
     *                          "created_at": "2021-07-24T12:47:09.000000Z",
     *                          "updated_at": "2021-07-24T12:47:09.000000Z",
     *                      }}
     *                    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return UserCollection
     */
    public function couriers(string $kitchenCode, Request $request)
    {
        return new UserCollection(
            User::with('iiko')
                ->whereHas('iiko', function ($query) {
                    return $query->whereNotNull('iiko_id');
                })
                ->where('users.kitchen_code', $kitchenCode)
                ->get()
        );
    }
}
