<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\IndexRequest;
use App\Http\Requests\Products\MassStoreRequest;
use App\Http\Requests\Products\SearchRequest;
use App\Http\Requests\Products\UpdateRequest;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ProductsCollection;
use App\Models\Product\Product;
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
     *         name="type_sync_id",
     *         in="query",
     *         description="Тип товара",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"pizza","sushi","soup","other"},
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="category_sync_id",
     *         in="query",
     *         description="Категория товара",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"sushi","sets","pizza","drinks","additions","deserts","salads","other"},
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
     *                         description="ID в формате UUID"
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
     *                         property="category_sync_id",
     *                         type="string",
     *                         description="Идентификатор категории товара"
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
     *                         property="image",
     *                         type="string",
     *                         description="Ссылка на фото"
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
     *                          "id": "5524e4ca-8655-41b8-9d79-b9fdbc7d39f4",
     *                          "restaurant": "smaki",
     *                          "article": "art-93993",
     *                          "title_ua": "Пицца 4 Сыра",
     *                          "title_ru": "Пицца 4 Сыра",
     *                          "is_active": 0,
     *                          "weight": 250,
     *                          "weight_type": "г",
     *                          "category_sync_id": "pizza",
     *                          "type_sync_id": "pizza",
     *                          "description_ua": "Lorem ipsum dolor sit amet.",
     *                          "description_ru": "Lorem ipsum dolor sit amet.",
     *                          "created_at": "2021-07-30T15:29:31.000000Z",
     *                          "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          "image": "http://smaki.local/storage/29/goku.jpg",
     *                          "prices": {
     *                              {
     *                                  "product_id": "5524e4ca-8655-41b8-9d79-b9fdbc7d39f4",
     *                                  "city_sync_id": "lviv",
     *                                  "price": 120,
     *                                  "price_old": 114
     *                              },
     *                              {
     *                                  "product_id": "5524e4ca-8655-41b8-9d79-b9fdbc7d39f4",
     *                                  "city_sync_id": "mykolaiv",
     *                                  "price": 125,
     *                                  "price_old": 119
     *                              },
     *                          },
     *                          "type": {
     *                              "sync_id": "pizza",
     *                              "name": "Пицца"
     *                          },
     *                          "category": {
     *                              "sync_id": "pizza",
     *                              "name": "Пицца"
     *                          },
     *                      }},
     *                      "links": {
     *                          "first": "https://api.smaki.com.ua/api/products?page=1",
     *                          "last": "https://api.smaki.com.ua/api/products?page=1",
     *                          "prev": null,
     *                          "next": null
     *                      },
     *                      "meta": {
     *                          "current_page": 1,
     *                          "from": 1,
     *                          "last_page": 1,
     *                          "links": {},
     *                          "path": "https://api.smaki.com.ua/api/products",
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
     *                         property="product_uuid",
     *                         type="integer",
     *                         description="ID в формате UUID"
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
     *                                 property="city_uuid",
     *                                 type="string",
     *                                 description="Идентификатор города в формате UUID"
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
     *                     "product_uuid": "ff7d0b98-e818-4813-ae90-e11791dc35f7",
     *                     "article": "art-1",
     *                     "title_ua": "Калифорния",
     *                     "weight": 250,
     *                     "weight_type": "г",
     *                     "prices": {
     *                          {
     *                              "city_uuid": "cbc41f7d-823c-411a-b6d2-9af963c6ed99",
     *                              "price": 190,
     *                          },
     *                          {
     *                              "city_uuid": "914e5cea-d605-4bc3-ab82-3bccb83329c5",
     *                              "price": 180,
     *                          }
     *                      }
     *                  },
     *                  {
     *                     "product_uuid": "ff7d0b98-e818-4813-ae90-e11791dc35f7",
     *                     "article": "art-2",
     *                     "title_ua": "Филадельфия",
     *                     "weight": 260,
     *                     "weight_type": "г",
     *                     "prices": {
     *                          {
     *                              "city_uuid": "cbc41f7d-823c-411a-b6d2-9af963c6ed99",
     *                              "price": 210,
     *                          },
     *                          {
     *                              "city_uuid": "914e5cea-d605-4bc3-ab82-3bccb83329c5",
     *                              "price": 200,
     *                          },
     *                          {
     *                              "city_uuid": "b8b978d8-f6f2-47b8-b67f-f77b5c2cf92b",
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
     *                         type="string",
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
     *                         property="category_sync_id",
     *                         type="string",
     *                         description="Категория товара"
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
     *                         property="image",
     *                         type="string",
     *                         description="Ссылка на фото"
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
     *                          "id": "c00efd9f-cd30-4452-82d3-52ac9c0af9b6",
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
     *                          "category_sync_id": "pizza",
     *                          "description_ua": "Lorem ipsum dolor sit amet.",
     *                          "description_ru": "Lorem ipsum dolor sit amet.",
     *                          "created_at": "2021-07-30T15:29:31.000000Z",
     *                          "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          "image": "http://smaki.local/storage/29/goku.jpg",
     *                          "prices": {
     *                              {
     *                                  "product_id": "c00efd9f-cd30-4452-82d3-52ac9c0af9b6",
     *                                  "city_sync_id": "lviv",
     *                                  "price": 120,
     *                                  "price_old": 114
     *                              },
     *                              {
     *                                  "product_id": "c00efd9f-cd30-4452-82d3-52ac9c0af9b6",
     *                                  "city_sync_id": "mykolaiv",
     *                                  "price": 125,
     *                                  "price_old": 119
     *                              },
     *                          },
     *                          "type": {
     *                              "sync_id": "pizza",
     *                              "name": "Пицца"
     *                          },
     *                          "category": {
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
     * @OA\PUT(
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
     *                          @OA\Property(
     *                             property="is_active",
     *                             type="integer",
     *                             description="Активен ли товар"
     *                          ),
     *                  ),
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
     *                  description="Идентификатор типа товара"
     *               ),
     *               @OA\Property(
     *                  property="category_sync_id",
     *                  type="string",
     *                  description="Идентификатор категории товара"
     *               )
     *              ),
     *              example={"data":
     *                  {
     *                      "title_ua": "Філадельфія",
     *                      "title_ru": "Филадельфия",
     *                      "description_ua": "Филадельфия DESC UA",
     *                      "description_ru": "Филадельфия DESC RU",
     *                      "category_sync_id": "pizza",
     *                      "type_sync_id": "pizza",
     *                      "prices": {
     *                          {
     *                              "city_sync_id": "lviv",
     *                              "is_active": 0,
     *                              "price_old": 123
     *                          },
     *                          {
     *                              "city_sync_id": "mykolaiv",
     *                              "is_active": 0,
     *                              "price_old": 124
     *                          },
     *                          {
     *                              "city_sync_id": "sumy",
     *                              "is_active": 0,
     *                              "price_old": 125
     *                          }
     *                      }
     *                  }
     *              }
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
     *                         type="string",
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
     *                         property="category_sync_id",
     *                         type="string",
     *                         description="Идентификатор категории товара"
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
     *                         property="created_at",
     *                         type="string",
     *                         description="Дата создания товара"
     *                     ),
     *                     @OA\Property(
     *                         property="image",
     *                         type="string",
     *                         description="Ссылка на фото"
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
     *                          "id": "4d619e0a-b3cd-4cc7-aa05-3e0a70fa0c52",
     *                          "restaurant": "smaki",
     *                          "article": "art-93993",
     *                          "title_ua": "Пицца 4 Сыра",
     *                          "title_ru": "Пицца 4 Сыра",
     *                          "price": 120,
     *                          "price_old": 114,
     *                          "weight": 250,
     *                          "weight_type": "г",
     *                          "category_sync_id": "pizza",
     *                          "type_sync_id": "pizza",
     *                          "description_ua": "Lorem ipsum dolor sit amet.",
     *                          "description_ru": "Lorem ipsum dolor sit amet.",
     *                          "created_at": "2021-07-30T15:29:31.000000Z",
     *                          "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          "image": "http://smaki.local/storage/29/goku.jpg",
     *                          "prices": {
     *                              {
     *                                  "product_id": "4d619e0a-b3cd-4cc7-aa05-3e0a70fa0c52",
     *                                  "city_sync_id": "lviv",
     *                                  "price": 120,
     *                                  "price_old": 114,
     *                                  "is_active": 1,
     *                              },
     *                              {
     *                                  "product_id": "4d619e0a-b3cd-4cc7-aa05-3e0a70fa0c52",
     *                                  "city_sync_id": "mykolaiv",
     *                                  "price": 125,
     *                                  "price_old": 119,
     *                                  "is_active": 0,
     *                              },
     *                          },
     *                          "type": {
     *                              "sync_id": "pizza",
     *                              "name": "Пицца"
     *                          },
     *                          "category": {
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
