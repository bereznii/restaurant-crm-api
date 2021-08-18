<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
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
     *                          "created_at": "2021-07-24T12:47:09.000000Z",
     *                          "updated_at": "2021-07-24T12:47:09.000000Z",
     *                      }},
     *                      "links": {
     *                          "first": "http://77.120.110.168:8080/api/users?page=1",
     *                          "last": "http://77.120.110.168:8080/api/users?page=1",
     *                          "prev": null,
     *                          "next": null
     *                      },
     *                      "meta": {
     *                          "current_page": 1,
     *                          "from": 1,
     *                          "last_page": 1,
     *                          "links": {},
     *                          "path": "http://77.120.110.168:8080/api/users",
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
    public function index(Request $request)
    {
        return new UserCollection(User::with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(
            (int) $request->get('per_page', 20)
        ));
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
     *               required={"first_name","last_name","email","password","phone","position", "role_name"},
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
     *                               "first_name": "Someone",
     *                               "last_name": "Pupkin",
     *                               "position": "CTO",
     *                               "phone": "380997774455",
     *                               "email": "some@some.com",
     *                               "role_name": "content_manager",
     *                               "role_title": "Контент-менеджер",
     *                               "updated_at": "2021-07-28T12:47:01.000000Z",
     *                               "created_at": "2021-07-28T12:47:01.000000Z",
     *                               "id": 7
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
    public function show($id)
    {
        return new UserResource(User::with('roles')->findOrFail($id));
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
        $user = User::with('roles')->findOrFail($id);

        $attrs = $request->validated();

        if (isset($attrs['password'])) {
            $attrs['password'] = Hash::make($request->input('password'));
        }
        if (isset($attrs['role_name'])) {
            $user->syncRoles([$attrs['role_name']]);
        }

        $user->update($attrs);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
