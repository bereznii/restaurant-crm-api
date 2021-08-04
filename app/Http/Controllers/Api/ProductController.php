<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\IndexRequest;
use App\Http\Requests\Products\MassStoreRequest;
use App\Http\Requests\Products\SearchRequest;
use App\Http\Requests\Products\UpdateRequest;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ProductsCollection;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;

class ProductController extends Controller
{
    /**
     * @param ProductService $productService
     * @param ProductRepository $productRepository
     */
    public function __construct(
        private ProductService $productService,
        private ProductRepository $productRepository
    ) {}

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
     *     @OA\Parameter(
     *         name="restaurant",
     *         in="query",
     *         description="Идентификатор ресторана",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="city_sync_id",
     *         in="query",
     *         description="Идентификатор города",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="type_sync_id",
     *         in="query",
     *         description="Тип товара",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"pizza","sushi","soup","other"},
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
     *                      @OA\Property(
     *                         property="weight",
     *                         type="intger",
     *                         description="Вес"
     *                      ),
     *                     @OA\Property(
     *                         property="weight_type",
     *                         type="string",
     *                         description="Единица измерения веса"
     *                     ),
     *                     @OA\Property(
     *                         property="type_sync_id",
     *                         type="string",
     *                         description="Идентификатор типа товара"
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
     *                         property="prices",
     *                         type="string",
     *                         description="Массив сущностей цены товара для каждого города"
     *                     ),
     *                     @OA\Property(
     *                         property="type",
     *                         type="string",
     *                         description="Сущность типа товара"
     *                     ),
     *                     example={"data":{
     *                      {
     *                          "id": 1,
     *                          "restaurant": "smaki",
     *                          "article": "art-93993",
     *                          "title_ua": "Пицца 4 Сыра",
     *                          "title_ru": "Пицца 4 Сыра",
     *                          "is_active": 0,
     *                          "weight": 250,
     *                          "weight_type": "г",
     *                          "type_sync_id": "pizza",
     *                          "description_ua": "Lorem ipsum dolor sit amet.",
     *                          "description_ru": "Lorem ipsum dolor sit amet.",
     *                          "created_at": "2021-07-30T15:29:31.000000Z",
     *                          "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          "prices": {
     *                              {
     *                                  "product_id": 1,
     *                                  "city_sync_id": "lviv",
     *                                  "price": 120,
     *                                  "price_old": 114
     *                              },
     *                              {
     *                                  "product_id": 1,
     *                                  "city_sync_id": "mykolaiv",
     *                                  "price": 125,
     *                                  "price_old": 119
     *                              },
     *                          },
     *                          "type": {
     *                              "sync_id": "pizza",
     *                              "name": "Пицца"
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
    public function index(IndexRequest $request)
    {
        return new ProductsCollection(
            $this->productRepository->index($request->validated())
        );
    }

    /**
     * @OA\Post(
     *     path="/{restaurant}/products",
     *     tags={"Products"},
     *     security={{"Bearer":{}}},
     *     description="Используется исключительно для синхронизации товаров с 1С",
     *     @OA\Parameter(
     *         name="restaurant",
     *         in="path",
     *         description="Идентификатор ресторана",
     *         required=true,
     *         example="smaki"
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="array",
     *                @OA\Items(
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
     *                      @OA\Property(
     *                         property="weight",
     *                         type="integer",
     *                         description="Вес"
     *                      ),
     *                     @OA\Property(
     *                         property="weight_type",
     *                         type="string",
     *                         description="Единица измерения веса"
     *                     ),
     *                      @OA\Property(
     *                         property="price",
     *                         type="array",
     *                          @OA\Items(
     *                              @OA\Property(
     *                                 property="city_sync_id",
     *                                 type="string",
     *                                 description="Идентификатор города для которого редактируется цена"
     *                              ),
     *                              @OA\Property(
     *                                 property="price",
     *                                 type="integer",
     *                                 description="Актуальная цена"
     *                              ),
     *                          )
     *                      ),
     *                ),
     *                example={
     *                  {
     *                     "article": "art-1",
     *                     "title_ua": "Калифорния",
     *                     "weight": 250,
     *                     "weight_type": "г",
     *                     "prices": {
     *                          {
     *                              "city": "lviv",
     *                              "price": 190,
     *                          },
     *                          {
     *                              "city": "sumy",
     *                              "price": 180,
     *                          }
     *                      }
     *                  },
     *                  {
     *                     "article": "art-2",
     *                     "title_ua": "Филадельфия",
     *                     "weight": 260,
     *                     "weight_type": "г",
     *                     "prices": {
     *                          {
     *                              "city": "lviv",
     *                              "price": 210,
     *                          },
     *                          {
     *                              "city": "sumy",
     *                              "price": 200,
     *                          },
     *                          {
     *                              "city": "vinnytsia",
     *                              "price": 200,
     *                          }
     *                      }
     *                  }
     *                }
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
     *                         property="message",
     *                         type="string",
     *                         description="Результат синхронизации товаров"
     *                     ),
     *                     example={
     *                          "message": "OK",
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param string $restaurant
     * @param MassStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function massStore(string $restaurant, MassStoreRequest $request)
    {
        return response()->json([
            'message' => $this->productService->massStore($restaurant, $request->validated())
                ? 'OK'
                : 'При сохранении товаров произошла ошибка'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID товара",
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
     *                         property="restaurant",
     *                         type="string",
     *                         description="Идентификатор ресторана"
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
     *                      @OA\Property(
     *                         property="weight",
     *                         type="integer",
     *                         description="Вес"
     *                      ),
     *                     @OA\Property(
     *                         property="weight_type",
     *                         type="string",
     *                         description="Единица измерения веса"
     *                     ),
     *                     @OA\Property(
     *                         property="type_sync_id",
     *                         type="string",
     *                         description="Идентификатор типа товара"
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
     *                         property="prices",
     *                         type="string",
     *                         description="Массив сущностей цены товара для каждого города"
     *                     ),
     *                     @OA\Property(
     *                         property="type",
     *                         type="string",
     *                         description="Сущность типа товара"
     *                     ),
     *                     example={"data":{
     *                          "id": 1,
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
     *                          "type_sync_id": "pizza",
     *                          "description_ua": "Lorem ipsum dolor sit amet.",
     *                          "description_ru": "Lorem ipsum dolor sit amet.",
     *                          "created_at": "2021-07-30T15:29:31.000000Z",
     *                          "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          "prices": {
     *                              {
     *                                  "product_id": 1,
     *                                  "city_sync_id": "lviv",
     *                                  "price": 120,
     *                                  "price_old": 114
     *                              },
     *                              {
     *                                  "product_id": 1,
     *                                  "city_sync_id": "mykolaiv",
     *                                  "price": 125,
     *                                  "price_old": 119
     *                              },
     *                          },
     *                          "type": {
     *                              "sync_id": "pizza",
     *                              "name": "Пицца"
     *                          },
     *                      }
     *                    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param  int  $id
     * @return ProductResource
     */
    public function show($id)
    {
        return new ProductResource(
            $this->productRepository->show($id)
        );
    }

    /**
     * @OA\Patch(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID товара",
     *         required=true
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               @OA\Property(
     *                  property="title_ua",
     *                  type="string",
     *                  description="Название на украинском"
     *               ),
     *               @OA\Property(
     *                  property="title_ru",
     *                  type="string",
     *                  enum={"1","0"},
     *                  description="Название на русском"
     *               ),
     *               @OA\Property(
     *                  property="prices",
     *                  type="array",
     *                  description="Массив цен товара для редактирования",
     *                      @OA\Items(
     *                          @OA\Property(
     *                             property="city_sync_id",
     *                             type="string",
     *                             description="Идентификатор города для которого редактируется цена"
     *                          ),
     *                          @OA\Property(
     *                             property="price_old",
     *                             type="integer",
     *                             description="Новое значения предыдщуей цены"
     *                          ),
     *                  ),
     *               ),
     *               @OA\Property(
     *                  property="is_active",
     *                  type="integer",
     *                  description="Активен ли товар"
     *               ),
     *               @OA\Property(
     *                  property="description_ua",
     *                  type="string",
     *                  description="Описание на украинском"
     *               ),
     *               @OA\Property(
     *                  property="description_ru",
     *                  type="string",
     *                  description="Описание на русском"
     *               ),
     *               @OA\Property(
     *                  property="type_sync_id",
     *                  type="string",
     *                  description="Идентификатор типа товара",
     *                  enum={"pizza","sushi","soup","other"}
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
     *                      @OA\Property(
     *                         property="weight",
     *                         type="integer",
     *                         description="Вес"
     *                      ),
     *                     @OA\Property(
     *                         property="weight_type",
     *                         type="string",
     *                         description="Единица измерения веса"
     *                     ),
     *                     @OA\Property(
     *                         property="type_sync_id",
     *                         type="string",
     *                         description="Идентификатор типа товара"
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
     *                         property="prices",
     *                         type="string",
     *                         description="Массив сущностей цены товара для каждого города"
     *                     ),
     *                     @OA\Property(
     *                         property="type",
     *                         type="string",
     *                         description="Сущность типа товара"
     *                     ),
     *                     example={"data":{
     *                          "id": 1,
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
     *                          "type_sync_id": "pizza",
     *                          "description_ua": "Lorem ipsum dolor sit amet.",
     *                          "description_ru": "Lorem ipsum dolor sit amet.",
     *                          "created_at": "2021-07-30T15:29:31.000000Z",
     *                          "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          "prices": {
     *                              {
     *                                  "product_id": 1,
     *                                  "city_sync_id": "lviv",
     *                                  "price": 120,
     *                                  "price_old": 114
     *                              },
     *                              {
     *                                  "product_id": 1,
     *                                  "city_sync_id": "mykolaiv",
     *                                  "price": 125,
     *                                  "price_old": 119
     *                              },
     *                          },
     *                          "type": {
     *                              "sync_id": "pizza",
     *                              "name": "Пицца"
     *                          },
     *                      }
     *                    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param UpdateRequest $request
     * @param Product $product
     * @return ProductResource
     */
    public function update(UpdateRequest $request, Product $product)
    {
        return new ProductResource(
            $this->productService->update($product, $request->validated())
        );
    }

    /**
     * @param SearchRequest $request
     * @return ProductsCollection
     */
    public function search(SearchRequest $request)
    {
        return new ProductsCollection(
            $this->productRepository->search($request->validated())
        );
    }
}
