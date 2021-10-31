<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clients\IndexRequest;
use App\Http\Resources\DefaultCollection;
use App\Models\Client\Client;

class ClientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/clients",
     *     tags={"Clients"},
     *     security={{"Bearer":{}}},
     *     summary="Получить список клиентов",
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Первые цифры номера телефона",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
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
     *                         type="string",
     *                         description="ID"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Имя клиента"
     *                     ),
     *                     @OA\Property(
     *                         property="phone",
     *                         type="integer",
     *                         description="Номер телефона клиента"
     *                     ),
     *                     @OA\Property(
     *                         property="source",
     *                         type="string",
     *                         description="Источник"
     *                     ),
     *                     @OA\Property(
     *                         property="is_regular",
     *                         type="integer",
     *                         description="Постоянный клиент или нет"
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
     *                              "name": "Василий Пупкин",
     *                              "phone": 380994445566,
     *                              "source": "website",
     *                              "is_regular": 1,
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
    public function index(IndexRequest $request)
    {
        return new DefaultCollection(
            Client::filterWhere(
                    'phone',
                    'like',
                    ($request->validated()['phone'] ?? null)
                        ? "{$request->validated()['phone']}%"
                        : null
                )
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        );
    }

    /**
     * @OA\Get(
     *     path="/sources",
     *     tags={"Clients"},
     *     security={{"Bearer":{}}},
     *     summary="Получить список источников клиентов",
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Тип источника"
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="integer",
     *                         description="Название источника"
     *                     ),
     *                     example={"data":{
     *                          {
     *                              "name": "website",
     *                              "title": "Сайт",
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
    public function sources()
    {
        return new DefaultCollection(
            collect(Client::CLIENT_SOURCES)
        );
    }
}
