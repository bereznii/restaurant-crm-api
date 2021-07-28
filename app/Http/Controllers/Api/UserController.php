<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
     *     security={{"Bearer":{}}},
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
     *                     example={"data":{{
     *                      "id": "1",
     *                      "email": "admin@smaki.com",
     *                      "phone": "+380973334455",
     *                      "position": "Developer",
     *                      "first_name": "John",
     *                      "last_name": "Doe",
     *                      "status": "active",
     *                      "email_verified_at": "2021-07-24T12:47:09.000000Z",
     *                      "created_at": "2021-07-24T12:47:09.000000Z",
     *                      "updated_at": "2021-07-24T12:47:09.000000Z",
     *                     }}}
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return UserCollection
     */
    public function index()
    {
        return new UserCollection(User::get());
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
     *                               "phone": "+380997774455",
     *                               "email": "some@some.com",
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
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'position' => $request->input('position'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return new UserResource($user);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     security={{"Bearer":{}}},
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
     *                      "phone": "+380973334455",
     *                      "position": "Developer",
     *                      "first_name": "John",
     *                      "last_name": "Doe",
     *                      "status": "active",
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
        return new UserResource(User::findOrFail($id));
    }

    /**
     * @OA\Patch(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     security={{"Bearer":{}}},
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
     *                               "phone": "+380997774455",
     *                               "email": "some@some.com",
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
     * @param UpdateRequest $request
     * @param int $id
     * @return UserResource
     */
    public function update(UpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $attrs = $request->all();

        if (isset($attrs['password'])) {
            $attrs['password'] = Hash::make($request->input('password'));
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
