<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\IndexRequest;
use App\Http\Requests\Orders\StoreRequest;
use App\Http\Requests\Orders\UpdateRequest;
use App\Http\Resources\DefaultCollection;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Orders\OrdersCollection;
use App\Models\Order\Order;
use App\Repositories\OrderRepository;
use App\Services\OrderService;

class OrderController extends Controller
{
    /**
     * @param OrderRepository $orderRepository
     * @param OrderService $orderService
     */
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderService $orderService
    ) {}

    /**
     * @OA\Get(
     *     path="/orders",
     *     tags={"Order"},
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
     *                      ),
     *                      @OA\Property(
     *                         property="restaurant",
     *                         type="string",
     *                         description="Идентификатор ресторана"
     *                      ),
     *                      @OA\Property(
     *                         property="kitchen_code",
     *                         type="string",
     *                         description="Идентификатор кухни"
     *                      ),
     *                      @OA\Property(
     *                         property="change_from",
     *                         type="integer",
     *                         description="Подготовить сдачу с"
     *                      ),
     *                      @OA\Property(
     *                         property="type",
     *                         type="string",
     *                         description="Тип заказа",
     *                         enum={"soon","requested_time"}
     *                      ),
     *                      @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         description="Статус"
     *                      ),
     *                      @OA\Property(
     *                         property="return_call",
     *                         type="integer",
     *                         description="Перезванивать ли",
     *                         enum={1,0}
     *                      ),
     *                      @OA\Property(
     *                         property="client_id",
     *                         type="integer",
     *                         description="ID клиента"
     *                      ),
     *                      @OA\Property(
     *                         property="courier_id",
     *                         type="integer",
     *                         description="ID пользователя курьера"
     *                      ),
     *                      @OA\Property(
     *                         property="operator_id",
     *                         type="integer",
     *                         description="ID оператора"
     *                      ),
     *                      @OA\Property(
     *                         property="client_comment",
     *                         type="string",
     *                         description="Комментарий клиента"
     *                      ),
     *                      @OA\Property(
     *                         property="delivered_till",
     *                         type="string",
     *                         description="Доставить до"
     *                      ),
     *                      @OA\Property(
     *                         property="client",
     *                         type="array",
     *                         description="Информация о клиенте",
     *                         @OA\Items(
     *                             type="string"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="address",
     *                         type="array",
     *                         description="Адрес доставки",
     *                         @OA\Items(
     *                             type="string"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         description="Позиции в заказе",
     *                         @OA\Items(
     *                             type="object"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="payments",
     *                         type="array",
     *                         description="Оплаты",
     *                         @OA\Items(
     *                             type="object"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="history",
     *                         type="array",
     *                         description="История изменения статуса",
     *                         @OA\Items(
     *                             type="object"
     *                         )
     *                      ),
     *                     example={"data":{
     *                      "new":{{
     *                          "id": 14,
     *                          "restaurant": "smaki",
     *                          "kitchen_code": "kulparkivska",
     *                          "change_from": 1000,
     *                          "type": "soon",
     *                          "status": "new",
     *                          "return_call": 0,
     *                          "client_id": 5,
     *                          "courier_id": 22,
     *                          "operator_id": null,
     *                          "client_comment": "Без перца, постучать в дверь",
     *                          "delivered_till": null,
     *                          "created_at": "2021-10-25T15:10:59.000000Z",
     *                          "updated_at": "2021-10-25T15:10:59.000000Z",
     *                          "items": {
     *                              {
     *                                  "id": "024c60b3-d89d-443a-b472-711e1a734122",
     *                                  "order_id": 14,
     *                                  "quantity": 2,
     *                                  "product": {},
     *                                  "sum": 220,
     *                                  "price": 110,
     *                                  "comment": "без перца",
     *                                  "created_at": "2021-10-25T15:10:59.000000Z",
     *                                  "updated_at": "2021-10-25T15:10:59.000000Z",
     *                                  "cook_id": null
     *                              },
     *                              {
     *                                  "id": "25f7c5da-37f4-4e53-9b93-4ab42cab0028",
     *                                  "order_id": 14,
     *                                  "quantity": 3,
     *                                  "product": {},
     *                                  "sum": 333,
     *                                  "price": 111,
     *                                  "comment": "без перца",
     *                                  "created_at": "2021-10-25T15:10:59.000000Z",
     *                                  "updated_at": "2021-10-25T15:10:59.000000Z",
     *                                  "cook_id": null
     *                              }
     *                          },
     *                          "history": {
     *                              {
     *                                  "id": 1,
     *                                  "order_id": 14,
     *                                  "user_id": 2,
     *                                  "status": "new",
     *                                  "set_at": "2021-10-26 14:46:48"
     *                              },
     *                              {
     *                                  "id": 2,
     *                                  "order_id": 14,
     *                                  "user_id": 1,
     *                                  "status": "cooking",
     *                                  "set_at": "2021-10-26 14:50:48"
     *                              }
     *                          },
     *                          "payments": {
     *                              {
     *                                  "id": 1,
     *                                  "order_id": 14,
     *                                  "payment_type": "cash",
     *                                  "sum": "500",
     *                                  "created_at": "2021-10-25T15:10:59.000000Z",
     *                                  "updated_at": "2021-10-25T15:10:59.000000Z"
     *                              }
     *                          },
     *                          "address": {
     *                              "id": 12,
     *                              "order_id": 14,
     *                              "city_sync_id": "lviv",
     *                              "street": "Кульпарківська",
     *                              "house_number": "12",
     *                              "entrance": "3",
     *                              "floor": "2",
     *                              "apartment": "2",
     *                              "comment": null,
     *                              "created_at": "2021-10-25T15:10:59.000000Z",
     *                              "updated_at": "2021-10-25T15:10:59.000000Z",
     *                              "latitude": "",
     *                              "longitude": ""
     *                          },
     *                          "client": {
     *                              "id": 5,
     *                              "name": "Максим",
     *                              "phone": 380997775544,
     *                              "source": "website",
     *                              "is_regular": 1,
     *                              "created_at": "2021-10-23T13:09:16.000000Z",
     *                              "updated_at": "2021-10-23T13:09:16.000000Z"
     *                          }
     *                          }}},
     *                      "cooking":{},
     *                      "preparing":{},
     *                      "for_delivery":{},
     *                    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return OrdersCollection
     */
    public function index(IndexRequest $request)
    {
        return new OrdersCollection(
            $this->orderRepository->index($request->validated())
        );
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     tags={"Order"},
     *     security={{"Bearer":{}}},
     *     description="Если клиент подобран из предикшена, в объекте client нужно передать id. Если не подобран, передать имя, номер телефона и источник, чтобы создать нового.",
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               required={"restaurant","kitchen_code","payments","type","status","return_call","client","address","items"},
     *               @OA\Property(
     *                  property="restaurant",
     *                  type="string",
     *                  description="Идентификатор ресторана"
     *               ),
     *               @OA\Property(
     *                  property="kitchen_code",
     *                  type="string",
     *                  description="Идентификатор кухни"
     *               ),
     *               @OA\Property(
     *                  property="change_from",
     *                  type="integer",
     *                  description="Подготовить сдачу с"
     *               ),
     *               @OA\Property(
     *                  property="type",
     *                  type="string",
     *                  description="Тип заказа",
     *                  enum={"soon","requested_time"}
     *               ),
     *               @OA\Property(
     *                  property="return_call",
     *                  type="integer",
     *                  description="Перезванивать ли",
     *                  enum={1,0}
     *               ),
     *               @OA\Property(
     *                  property="courier_id",
     *                  type="integer",
     *                  description="ID пользователя курьера"
     *               ),
     *               @OA\Property(
     *                  property="client_comment",
     *                  type="string",
     *                  description="Комментарий клиента"
     *               ),
     *               @OA\Property(
     *                  property="delivered_till",
     *                  type="string",
     *                  description="Доставить до (обязательно если type со значением requested_time)"
     *               ),
     *               @OA\Property(
     *                  property="client",
     *                  type="array",
     *                  description="Информация о клиенте",
     *                  @OA\Items(
     *                      type="string"
     *                  )
     *               ),
     *               @OA\Property(
     *                  property="address",
     *                  type="array",
     *                  description="Адрес доставки",
     *                  @OA\Items(
     *                      type="string"
     *                  )
     *               ),
     *               @OA\Property(
     *                  property="items",
     *                  type="array",
     *                  description="Позиции в заказе",
     *                  @OA\Items(
     *                      type="object"
     *                  )
     *               ),
     *               @OA\Property(
     *                  property="payments",
     *                  type="array",
     *                  description="Оплаты",
     *                  @OA\Items(
     *                      type="object"
     *                  )
     *               ),
     *              example={
     *                  "restaurant": "smaki",
     *                  "kitchen_code": "kulparkivska",
     *                  "change_from": 1000,
     *                  "type": "soon",
     *                  "status": "new",
     *                  "return_call": 0,
     *                  "courier_id": 22,
     *                  "client_comment": "Без перца, постучать в дверь",
     *                  "client": {
     *                      "id": 5,
     *                      "name": "Максим",
     *                      "phone": 380997775544,
     *                      "source": "website"
     *                  },
     *                  "address": {
     *                      "city_sync_id": "lviv",
     *                      "street": "Кульпарківська",
     *                      "house_number": "12",
     *                      "entrance": "3",
     *                      "floor": "2",
     *                      "apartment": "2",
     *                      "comment": "",
     *                      "latitude": "",
     *                      "longitude": ""
     *                  },
     *                  "items": {
     *                      {
     *                          "id": "024c60b3-d89d-443a-b472-711e1a734122",
     *                          "quantity": 2,
     *                          "comment": "без перца"
     *                      },
     *                      {
     *                          "id": "25f7c5da-37f4-4e53-9b93-4ab42cab0028",
     *                          "quantity": 3,
     *                          "comment": "без перца"
     *                      }
     *                  },
     *                  "payments": {
     *                      {
     *                          "payment_type": "cash",
     *                          "sum": 500,
     *                      }
     *                  }
     *                  }
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
     *                         property="restaurant",
     *                         type="string",
     *                         description="Идентификатор ресторана"
     *                      ),
     *                      @OA\Property(
     *                         property="kitchen_code",
     *                         type="string",
     *                         description="Идентификатор кухни"
     *                      ),
     *                      @OA\Property(
     *                         property="change_from",
     *                         type="integer",
     *                         description="Подготовить сдачу с"
     *                      ),
     *                      @OA\Property(
     *                         property="type",
     *                         type="string",
     *                         description="Тип заказа",
     *                         enum={"soon","requested_time"}
     *                      ),
     *                      @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         description="Статус"
     *                      ),
     *                      @OA\Property(
     *                         property="return_call",
     *                         type="integer",
     *                         description="Перезванивать ли",
     *                         enum={1,0}
     *                      ),
     *                      @OA\Property(
     *                         property="client_id",
     *                         type="integer",
     *                         description="ID клиента"
     *                      ),
     *                      @OA\Property(
     *                         property="courier_id",
     *                         type="integer",
     *                         description="ID пользователя курьера"
     *                      ),
     *                      @OA\Property(
     *                         property="operator_id",
     *                         type="integer",
     *                         description="ID оператора"
     *                      ),
     *                      @OA\Property(
     *                         property="client_comment",
     *                         type="string",
     *                         description="Комментарий клиента"
     *                      ),
     *                      @OA\Property(
     *                         property="delivered_till",
     *                         type="string",
     *                         description="Доставить до"
     *                      ),
     *                      @OA\Property(
     *                         property="client",
     *                         type="array",
     *                         description="Информация о клиенте",
     *                         @OA\Items(
     *                             type="string"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="address",
     *                         type="array",
     *                         description="Адрес доставки",
     *                         @OA\Items(
     *                             type="string"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         description="Позиции в заказе",
     *                         @OA\Items(
     *                             type="object"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="payments",
     *                         type="array",
     *                         description="Оплаты",
     *                         @OA\Items(
     *                             type="object"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="history",
     *                         type="array",
     *                         description="История изменения статуса",
     *                         @OA\Items(
     *                             type="object"
     *                         )
     *                      ),
     *                     example={
     *                      "data": {
     *                          "id": 14,
     *                          "restaurant": "smaki",
     *                          "kitchen_code": "kulparkivska",
     *                          "change_from": 1000,
     *                          "type": "soon",
     *                          "status": "new",
     *                          "return_call": 0,
     *                          "client_id": 5,
     *                          "courier_id": 22,
     *                          "operator_id": null,
     *                          "client_comment": "Без перца, постучать в дверь",
     *                          "delivered_till": null,
     *                          "created_at": "2021-10-25T15:10:59.000000Z",
     *                          "updated_at": "2021-10-25T15:10:59.000000Z",
     *                          "items": {
     *                              {
     *                                  "id": "024c60b3-d89d-443a-b472-711e1a734122",
     *                                  "order_id": 14,
     *                                  "quantity": 2,
     *                                  "product": {},
     *                                  "sum": 220,
     *                                  "price": 110,
     *                                  "comment": "без перца",
     *                                  "created_at": "2021-10-25T15:10:59.000000Z",
     *                                  "updated_at": "2021-10-25T15:10:59.000000Z",
     *                                  "cook_id": null
     *                              },
     *                              {
     *                                  "id": "25f7c5da-37f4-4e53-9b93-4ab42cab0028",
     *                                  "order_id": 14,
     *                                  "quantity": 3,
     *                                  "product": {},
     *                                  "sum": 333,
     *                                  "price": 111,
     *                                  "comment": "без перца",
     *                                  "created_at": "2021-10-25T15:10:59.000000Z",
     *                                  "updated_at": "2021-10-25T15:10:59.000000Z",
     *                                  "cook_id": null
     *                              }
     *                          },
     *                          "history": {
     *                              {
     *                                  "id": 1,
     *                                  "order_id": 14,
     *                                  "user_id": 2,
     *                                  "status": "new",
     *                                  "set_at": "2021-10-26 14:46:48"
     *                              },
     *                              {
     *                                  "id": 2,
     *                                  "order_id": 14,
     *                                  "user_id": 1,
     *                                  "status": "cooking",
     *                                  "set_at": "2021-10-26 14:50:48"
     *                              }
     *                          },
     *                          "payments": {
     *                              {
     *                                  "id": 1,
     *                                  "order_id": 14,
     *                                  "payment_type": "cash",
     *                                  "sum": 500,
     *                                  "created_at": "2021-10-25T15:10:59.000000Z",
     *                                  "updated_at": "2021-10-25T15:10:59.000000Z"
     *                              }
     *                          },
     *                          "address": {
     *                              "id": 12,
     *                              "order_id": 14,
     *                              "city_sync_id": "lviv",
     *                              "street": "Кульпарківська",
     *                              "house_number": "12",
     *                              "entrance": "3",
     *                              "floor": "2",
     *                              "apartment": null,
     *                              "comment": null,
     *                              "latitude": "",
     *                              "longitude": ""
     *                          },
     *                          "client": {
     *                              "id": 5,
     *                              "name": "Максим",
     *                              "phone": 380997775544,
     *                              "source": "website",
     *                              "is_regular": 1,
     *                              "created_at": "2021-10-23T13:09:16.000000Z",
     *                              "updated_at": "2021-10-23T13:09:16.000000Z"
     *                          }
     *                          }
     *                      }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param StoreRequest $request
     * @return OrderResource
     */
    public function store(StoreRequest $request)
    {
        return new OrderResource(
            $this->orderService->store($request->validated())
        );
    }

    /**
     * @OA\PUT(
     *     path="/orders/{id}",
     *     tags={"Order"},
     *     security={{"Bearer":{}}},
     *     description="Передавать нужно объект заказа целиком",
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               required={"restaurant","kitchen_code","payments","type","status","return_call","address","items"},
     *               @OA\Property(
     *                  property="restaurant",
     *                  type="string",
     *                  description="Идентификатор ресторана"
     *               ),
     *               @OA\Property(
     *                  property="kitchen_code",
     *                  type="string",
     *                  description="Идентификатор кухни"
     *               ),
     *               @OA\Property(
     *                  property="change_from",
     *                  type="integer",
     *                  description="Подготовить сдачу с"
     *               ),
     *               @OA\Property(
     *                  property="type",
     *                  type="string",
     *                  description="Тип заказа",
     *                  enum={"soon","requested_time"}
     *               ),
     *               @OA\Property(
     *                  property="return_call",
     *                  type="integer",
     *                  description="Перезванивать ли",
     *                  enum={1,0}
     *               ),
     *               @OA\Property(
     *                  property="courier_id",
     *                  type="integer",
     *                  description="ID пользователя курьера"
     *               ),
     *               @OA\Property(
     *                  property="client_comment",
     *                  type="string",
     *                  description="Комментарий клиента"
     *               ),
     *               @OA\Property(
     *                  property="delivered_till",
     *                  type="string",
     *                  description="Доставить до (обязательно если type со значением requested_time)"
     *               ),
     *               @OA\Property(
     *                  property="address",
     *                  type="array",
     *                  description="Адрес доставки",
     *                  @OA\Items(
     *                      type="string"
     *                  )
     *               ),
     *               @OA\Property(
     *                  property="items",
     *                  type="array",
     *                  description="Позиции в заказе",
     *                  @OA\Items(
     *                      type="object"
     *                  )
     *               ),
     *               @OA\Property(
     *                  property="payments",
     *                  type="array",
     *                  description="Оплаты",
     *                  @OA\Items(
     *                      type="object"
     *                  )
     *               ),
     *              example={
     *                  "restaurant": "smaki",
     *                  "kitchen_code": "kulparkivska",
     *                  "payment_type": "cash",
     *                  "change_from": 1000,
     *                  "type": "soon",
     *                  "status": "closed",
     *                  "return_call": 0,
     *                  "courier_id": 22,
     *                  "client_comment": "Без перца, постучать в дверь",
     *                  "address": {
     *                      "city_sync_id": "lviv",
     *                      "street": "Кульпарківська",
     *                      "house_number": "12",
     *                      "entrance": "3",
     *                      "floor": "2",
     *                      "comment": "",
     *                      "latitude": "",
     *                      "longitude": ""
     *                  },
     *                  "items": {
     *                      {
     *                          "id": "024c60b3-d89d-443a-b472-711e1a734122",
     *                          "quantity": 2,
     *                          "comment": "без перца"
     *                      },
     *                      {
     *                          "id": "25f7c5da-37f4-4e53-9b93-4ab42cab0028",
     *                          "quantity": 3,
     *                          "comment": "без перца"
     *                      }
     *                  },
     *                  "payments": {
     *                      {
     *                          "payment_type": "cash",
     *                          "sum": 500,
     *                      }
     *                  }
     *                  }
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
     *                         property="restaurant",
     *                         type="string",
     *                         description="Идентификатор ресторана"
     *                      ),
     *                      @OA\Property(
     *                         property="kitchen_code",
     *                         type="string",
     *                         description="Идентификатор кухни"
     *                      ),
     *                      @OA\Property(
     *                         property="change_from",
     *                         type="integer",
     *                         description="Подготовить сдачу с"
     *                      ),
     *                      @OA\Property(
     *                         property="type",
     *                         type="string",
     *                         description="Тип заказа",
     *                         enum={"soon","requested_time"}
     *                      ),
     *                      @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         description="Статус"
     *                      ),
     *                      @OA\Property(
     *                         property="return_call",
     *                         type="integer",
     *                         description="Перезванивать ли",
     *                         enum={1,0}
     *                      ),
     *                      @OA\Property(
     *                         property="client_id",
     *                         type="integer",
     *                         description="ID клиента"
     *                      ),
     *                      @OA\Property(
     *                         property="courier_id",
     *                         type="integer",
     *                         description="ID пользователя курьера"
     *                      ),
     *                      @OA\Property(
     *                         property="operator_id",
     *                         type="integer",
     *                         description="ID оператора"
     *                      ),
     *                      @OA\Property(
     *                         property="client_comment",
     *                         type="string",
     *                         description="Комментарий клиента"
     *                      ),
     *                      @OA\Property(
     *                         property="delivered_till",
     *                         type="string",
     *                         description="Доставить до"
     *                      ),
     *                      @OA\Property(
     *                         property="client",
     *                         type="array",
     *                         description="Информация о клиенте",
     *                         @OA\Items(
     *                             type="string"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="address",
     *                         type="array",
     *                         description="Адрес доставки",
     *                         @OA\Items(
     *                             type="string"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="payments",
     *                         type="array",
     *                         description="Оплаты",
     *                         @OA\Items(
     *                             type="object"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         description="Позиции в заказе",
     *                         @OA\Items(
     *                             type="object"
     *                         )
     *                      ),
     *                      @OA\Property(
     *                         property="history",
     *                         type="array",
     *                         description="История изменения статуса",
     *                         @OA\Items(
     *                             type="object"
     *                         )
     *                      ),
     *                     example={
     *                      "data": {
     *                          "id": 14,
     *                          "restaurant": "smaki",
     *                          "kitchen_code": "kulparkivska",
     *                          "change_from": 1000,
     *                          "type": "soon",
     *                          "status": "new",
     *                          "return_call": 0,
     *                          "client_id": 5,
     *                          "courier_id": 22,
     *                          "operator_id": null,
     *                          "client_comment": "Без перца, постучать в дверь",
     *                          "delivered_till": null,
     *                          "created_at": "2021-10-25T15:10:59.000000Z",
     *                          "updated_at": "2021-10-25T15:10:59.000000Z",
     *                          "items": {
     *                              {
     *                                  "id": "024c60b3-d89d-443a-b472-711e1a734122",
     *                                  "order_id": 14,
     *                                  "product": {},
     *                                  "quantity": 2,
     *                                  "sum": 220,
     *                                  "price": 110,
     *                                  "comment": "без перца",
     *                                  "created_at": "2021-10-25T15:10:59.000000Z",
     *                                  "updated_at": "2021-10-25T15:10:59.000000Z",
     *                                  "cook_id": null
     *                              },
     *                              {
     *                                  "id": "25f7c5da-37f4-4e53-9b93-4ab42cab0028",
     *                                  "order_id": 14,
     *                                  "product": {},
     *                                  "quantity": 3,
     *                                  "sum": 333,
     *                                  "price": 111,
     *                                  "comment": "без перца",
     *                                  "created_at": "2021-10-25T15:10:59.000000Z",
     *                                  "updated_at": "2021-10-25T15:10:59.000000Z",
     *                                  "cook_id": null
     *                              }
     *                          },
     *                          "history": {
     *                              {
     *                                  "id": 1,
     *                                  "order_id": 14,
     *                                  "user_id": 2,
     *                                  "status": "new",
     *                                  "set_at": "2021-10-26 14:46:48"
     *                              },
     *                              {
     *                                  "id": 2,
     *                                  "order_id": 14,
     *                                  "user_id": 1,
     *                                  "status": "cooking",
     *                                  "set_at": "2021-10-26 14:50:48"
     *                              }
     *                          },
     *                          "payments": {
     *                              {
     *                                  "id": 1,
     *                                  "order_id": 14,
     *                                  "payment_type": "cash",
     *                                  "sum": 500,
     *                                  "created_at": "2021-10-25T15:10:59.000000Z",
     *                                  "updated_at": "2021-10-25T15:10:59.000000Z"
     *                              }
     *                          },
     *                          "address": {
     *                              "id": 12,
     *                              "order_id": 14,
     *                              "city_sync_id": "lviv",
     *                              "street": "Кульпарківська",
     *                              "house_number": "12",
     *                              "entrance": "3",
     *                              "floor": "2",
     *                              "comment": null,
     *                              "created_at": "2021-10-25T15:10:59.000000Z",
     *                              "updated_at": "2021-10-25T15:10:59.000000Z",
     *                              "latitude": "",
     *                              "longitude": ""
     *                          },
     *                          "client": {
     *                              "id": 5,
     *                              "name": "Максим",
     *                              "phone": 380997775544,
     *                              "source": "website",
     *                              "is_regular": 1,
     *                              "created_at": "2021-10-23T13:09:16.000000Z",
     *                              "updated_at": "2021-10-23T13:09:16.000000Z"
     *                          }
     *                          }
     *                      }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param UpdateRequest $request
     * @param Order $order
     * @return OrderResource
     */
    public function update(UpdateRequest $request, Order $order)
    {
        return new OrderResource(
            $this->orderService->update($request->validated(), $order)
        );
    }
}
