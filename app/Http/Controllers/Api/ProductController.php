<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsCollection;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
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
     *                         property="restaurant",
     *                         type="string",
     *                         description="Идентификатор ресторана"
     *                     ),
     *                     @OA\Property(
     *                         property="city_sync_id",
     *                         type="string",
     *                         description="Идентификатор города"
     *                     ),
     *                     @OA\Property(
     *                         property="article",
     *                         type="string",
     *                         description="Артикул"
     *                     ),
     *                     @OA\Property(
     *                         property="title_ua",
     *                         type="string",
     *                         description="Название на украинском"
     *                     ),
     *                     @OA\Property(
     *                         property="title_ru",
     *                         type="string",
     *                         description="Название на русском"
     *                     ),
     *                     @OA\Property(
     *                         property="is_active",
     *                         type="string",
     *                         description="Активен ли товар"
     *                     ),
     *                     @OA\Property(
     *                         property="price",
     *                         type="string",
     *                         description="Актуальная стоимость"
     *                     ),
     *                      @OA\Property(
     *                         property="price_old",
     *                         type="string",
     *                         description="Предыдущая стоимость"
     *                      ),
     *                      @OA\Property(
     *                         property="weight",
     *                         type="string",
     *                         description="Вес"
     *                      ),
     *                     @OA\Property(
     *                         property="weight_type",
     *                         type="string",
     *                         description="Единица измерения веса"
     *                     ),
     *                     @OA\Property(
     *                         property="description_ua",
     *                         type="string",
     *                         description="Описание на украинском"
     *                     ),
     *                     @OA\Property(
     *                         property="description_ru",
     *                         type="string",
     *                         description="Описание на русском"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         description="Дата последнего редактирования"
     *                     ),
     *                     @OA\Property(
     *                         property="city",
     *                         type="string",
     *                         description="Сущность города"
     *                     ),
     *                     example={"data":{
     *                      {
     *                          "id": "1",
     *                          "restaurant": "smaki",
     *                          "city_sync_id": "lviv",
     *                          "article": "art-93993",
     *                          "title_ua": "Пицца 4 Сыра",
     *                          "title_ru": "Пицца 4 Сыра",
     *                          "is_active": 0,
     *                          "price": 120,
     *                          "price_old": 114,
     *                          "weight": 250,
     *                          "weight_type": "г",
     *                          "description_ua": "Lorem ipsum dolor sit amet.",
     *                          "description_ru": "Lorem ipsum dolor sit amet.",
     *                          "created_at": "2021-07-30T15:29:31.000000Z",
     *                          "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          "city": {
     *                              "id": 1,
     *                              "sync_id": "lviv",
     *                              "name": "Львов",
     *                              "created_at": "2021-07-30T15:29:31.000000Z",
     *                              "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          },
     *                      }},
     *                      "links": {
     *                          "first": "http://77.120.110.168:8080/api/products?page=1",
     *                          "last": "http://77.120.110.168:8080/api/products?page=1",
     *                          "prev": null,
     *                          "next": null
     *                      },
     *                      "meta": {
     *                          "current_page": 1,
     *                          "from": 1,
     *                          "last_page": 1,
     *                          "links": {},
     *                          "path": "http://77.120.110.168:8080/api/products",
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
     * @return ProductsCollection
     */
    public function index(Request $request)
    {
        //TODO: фильтр по ресторану
        //TODO: фильтр по городу
        //TODO: фильтр по типу
        return new ProductsCollection(Product::with('city')->paginate(
            (int) $request->get('per_page', 50)
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
