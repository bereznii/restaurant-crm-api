<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\IndexRequest;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResource;
use App\Models\iiko\CourierIiko;
use App\Models\Location;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="role_name",
     *         in="query",
     *         description="Идентификатор роли",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="kitchen_code",
     *         in="query",
     *         description="Идентификатор кухни",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество результатов на странице",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default="20"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Номер страницы",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default="1"
     *         )
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
     *                      @OA\Property(
     *                         property="kitchen_name",
     *                         type="string",
     *                         description="Название физической кухни"
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
     *                      }},
     *                      "links": {
     *                          "first": "https://api.smaki.com.ua/api/users?page=1",
     *                          "last": "https://api.smaki.com.ua/api/users?page=1",
     *                          "prev": null,
     *                          "next": null
     *                      },
     *                      "meta": {
     *                          "current_page": 1,
     *                          "from": 1,
     *                          "last_page": 1,
     *                          "links": {},
     *                          "path": "https://api.smaki.com.ua/api/users",
     *                          "per_page": 20,
     *                          "to": 11,
     *                          "total": 11,
     *                      },
     *                    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return UserCollection
     */
    public function index(IndexRequest $request)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new UserCollection(
            User::with('roles', 'iiko', 'locations', 'kitchen')
                ->whereNotIn('phone', [env('PRODUCTS_SYNC_LOGIN'), env('MOBILE_APP_LOGIN')])
                ->filterWhereRelation('roles', 'name', '=', $request->validated()['role_name'] ?? null)
                ->filterWhere('kitchen_code', '=', $request->validated()['kitchen_code'] ?? null)
                ->orderBy('created_at', 'desc')
                ->paginate((int) $request->get('per_page', 20))
        );
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"Users"},
     *     security={{"Bearer":{}}},
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               required={"first_name","last_name","password","phone","kitchen_code","role_name"},
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
     *               ),
     *               @OA\Property(
     *                  property="role_name",
     *                  type="string",
     *                  description="Идентификатор роли"
     *               ),
     *               @OA\Property(
     *                  property="kitchen_code",
     *                  type="string",
     *                  description="Идентификатор физической кухни"
     *               ),
     *               @OA\Property(
     *                  property="iiko_id",
     *                  type="string",
     *                  description="ID курьера из iiko в формате UUID (обязательно при создании пользователя с ролью courier)"
     *               ),
     *               @OA\Property(
     *                  property="product_types",
     *                  type="array",
     *                  description="Типы товаров для повара",
     *                  @OA\Items(
     *                      type="string",
     *                  )
     *               ),
     *               @OA\Property(
     *                  property="locations",
     *                  type="array",
     *                  description="ID локаций, к которым будет привязан пользователь",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *               ),
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
     *                      @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         description="ID"
     *                      ),
     *                      @OA\Property(
     *                         property="first_name",
     *                         type="string",
     *                         description="Имя пользователя"
     *                      ),
     *                      @OA\Property(
     *                         property="last_name",
     *                         type="string",
     *                         description="Фамилия"
     *                      ),
     *                      @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         description="Имейл"
     *                      ),
     *                      @OA\Property(
     *                         property="phone",
     *                         type="string",
     *                         description="Телефон"
     *                      ),
     *                      @OA\Property(
     *                         property="position",
     *                         type="string",
     *                         description="Должность"
     *                      ),
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
     *                      @OA\Property(
     *                          property="locations",
     *                          type="array",
     *                          description="Массив сущностей локаций, к которым привязан пользователь",
     *                          @OA\Items(
     *                              type="object"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="iiko",
     *                          type="array",
     *                          description="Данные из iiko CRM (для пользователей с ролью Courier)",
     *                          @OA\Items(
     *                              type="object"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         description="Дата последнего редактирования"
     *                      ),
     *                      @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         description="Дата создания"
     *                      ),
     *                     example={
     *                           "data": {
     *                               "id": 7,
     *                               "first_name": "Someone",
     *                               "last_name": "Pupkin",
     *                               "position": "CTO",
     *                               "phone": "380997774455",
     *                               "email": "some@some.com",
     *                               "role_name": "content_manager",
     *                               "role_title": "Контент-менеджер",
     *                               "locations": {{
     *                                   "id": 1,
     *                                   "restaurant": "smaki",
     *                                   "name": "Кульпарковская Смаки",
     *                                   "city_sync_id": "lviv",
     *                                   "city": "Львов",
     *                                   "street": "ул. Кульпарковская",
     *                                   "house_number": "95",
     *                                   "latitude": null,
     *                                   "longitude": null,
     *                                   "created_at": "2021-07-28T11:08:01.000000Z",
     *                                   "updated_at": "2021-07-28T11:08:01.000000Z"
     *                               }},
     *                               "iiko": {
     *                                   "iiko_id": "8f423953-8d9e-47c5-a409-1e7cb33c6f00",
     *                                   "created_at": "2021-08-25T20:36:13.000000Z",
     *                                   "updated_at": "2021-08-25T20:36:13.000000Z"
     *                               },
     *                               "kitchen_code": "sumy",
     *                               "kitchen_name": "Суми",
     *                               "product_types": {"pizza"},
     *                               "updated_at": "2021-07-28T12:47:01.000000Z",
     *                               "created_at": "2021-07-28T12:47:01.000000Z"
     *                           }
     *                      }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param StoreRequest $request
     * @return UserResource
     */
    public function store(StoreRequest $request)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new UserResource(
            $this->userService->store($request->validated())
        );
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID пользователя",
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
     *                      "id": "1",
     *                      "email": "admin@smaki.com",
     *                      "phone": "380973334455",
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
     * @param  int  $id
     * @return UserResource
     */
    public function show($id, Request $request)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new UserResource(
            User::with('roles', 'iiko', 'locations', 'kitchen')->findOrFail($id)
        );
    }

    /**
     * @OA\Patch(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID пользователя",
     *         required=true
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
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
     *               ),
     *               @OA\Property(
     *                  property="role_name",
     *                  type="string",
     *                  description="Идентификатор роли"
     *               ),
     *               @OA\Property(
     *                  property="iiko_id",
     *                  type="string",
     *                  description="ID курьера из iiko в формате UUID (обязательно при создании пользователя с ролью courier)"
     *               ),
     *               @OA\Property(
     *                  property="kitchen_code",
     *                  type="string",
     *                  description="Идентификатор физической кухни"
     *               ),
     *               @OA\Property(
     *                  property="locations",
     *                  type="array",
     *                  description="ID локаций, к которым будет привязан пользователь",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *               ),
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
     *                      @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         description="ID"
     *                      ),
     *                      @OA\Property(
     *                         property="first_name",
     *                         type="string",
     *                         description="Имя пользователя"
     *                      ),
     *                      @OA\Property(
     *                         property="last_name",
     *                         type="string",
     *                         description="Фамилия"
     *                      ),
     *                      @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         description="Имейл"
     *                      ),
     *                      @OA\Property(
     *                         property="phone",
     *                         type="string",
     *                         description="Телефон"
     *                      ),
     *                      @OA\Property(
     *                         property="position",
     *                         type="string",
     *                         description="Должность"
     *                      ),
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
     *                      @OA\Property(
     *                          property="locations",
     *                          type="array",
     *                          description="Массив сущностей локаций, к которым привязан пользователь",
     *                          @OA\Items(
     *                              type="object"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="iiko",
     *                          type="array",
     *                          description="Данные из iiko CRM (для пользователей с ролью Courier)",
     *                          @OA\Items(
     *                              type="object"
     *                          ),
     *                      ),
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
     *                      @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         description="Дата последнего редактирования"
     *                      ),
     *                      @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         description="Дата создания"
     *                      ),
     *                     example={
     *                           "data": {
     *                               "id": 7,
     *                               "first_name": "Someone",
     *                               "last_name": "Pupkin",
     *                               "position": "CTO",
     *                               "phone": "380997774455",
     *                               "email": "some@some.com",
     *                               "role_name": "content_manager",
     *                               "role_title": "Контент-менеджер",
     *                                "locations": {{
     *                                    "id": 1,
     *                                    "restaurant": "smaki",
     *                                    "name": "Кульпарковская Смаки",
     *                                    "city_sync_id": "lviv",
     *                                    "city": "Львов",
     *                                    "street": "ул. Кульпарковская",
     *                                    "house_number": "95",
     *                                    "latitude": null,
     *                                    "longitude": null,
     *                                    "created_at": "2021-07-28T11:08:01.000000Z",
     *                                    "updated_at": "2021-07-28T11:08:01.000000Z"
     *                                }},
     *                               "iiko": {
     *                                   "iiko_id": "8f423953-8d9e-47c5-a409-1e7cb33c6f00",
     *                                   "created_at": "2021-08-25T20:36:13.000000Z",
     *                                   "updated_at": "2021-08-25T20:36:13.000000Z"
     *                               },
     *                               "kitchen_code": "sumy",
     *                               "kitchen_name": "Суми",
     *                               "product_types": {"pizza"},
     *                               "updated_at": "2021-07-28T12:47:01.000000Z",
     *                               "created_at": "2021-07-28T12:47:01.000000Z"
     *                           }
     *                      }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param UpdateRequest $request
     * @param int $id
     * @return UserResource
     */
    public function update(UpdateRequest $request, $id)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new UserResource(
            $this->userService->update($id, $request->validated())
        );
    }
}
