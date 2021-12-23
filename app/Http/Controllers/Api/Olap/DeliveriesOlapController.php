<?php

namespace App\Http\Controllers\Api\Olap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Olap\DeliveriesDeliveriesRequest;
use App\Http\Requests\Olap\DeliveriesIndexRequest;
use App\Http\Resources\DefaultCollection;
use App\Http\Resources\Olap\DeliveriesOlapCollection;
use App\Repositories\Olap\DeliveriesOlapRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeliveriesOlapController extends Controller
{
    /**
     * @param DeliveriesOlapRepository $deliveriesOlapRepository
     */
    public function __construct(
        private DeliveriesOlapRepository $deliveriesOlapRepository
    ) {}

    /**
     * @OA\Get(
     *     path="/olap/distances",
     *     tags={"OLAP"},
     *     security={{"Bearer":{}}},
     *     summary="Отчёт по пройденной дистанции по курьерам",
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Дата доставок от (включительно)",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Дата доставок до (включительно)",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="kitchen_code",
     *         in="query",
     *         description="Код кухни",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
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
     *                         property="user_id",
     *                         type="string",
     *                         description="ID пользователя"
     *                     ),
     *                      @OA\Property(
     *                         property="courier_iiko_id",
     *                         type="string",
     *                         description="ID курьера из iiko в формате UUID"
     *                      ),
     *                      @OA\Property(
     *                         property="kitchen_title",
     *                         type="string",
     *                         description="Название кухни"
     *                      ),
     *                      @OA\Property(
     *                         property="first_name",
     *                         type="string",
     *                         description="Имя пользователя"
     *                      ),
     *                      @OA\Property(
     *                         property="last_name",
     *                         type="string",
     *                         description="Фамилия пользователя"
     *                      ),
     *                      @OA\Property(
     *                         property="count_deliveries",
     *                         type="string",
     *                         description="Количество поездок"
     *                      ),
     *                      @OA\Property(
     *                         property="sum_delivery_distance",
     *                         type="string",
     *                         description="Общая дистанция доставок, в метрах"
     *                      ),
     *                      @OA\Property(
     *                         property="sum_return_distance",
     *                         type="string",
     *                         description="Общая дистанция обратно на кухню, в метрах"
     *                      ),
     *                      @OA\Property(
     *                         property="orders_within_city",
     *                         type="string",
     *                         description="Количество доставок в пределах города"
     *                      ),
     *                      @OA\Property(
     *                         property="orders_outside_city",
     *                         type="string",
     *                         description="Количество доставок за пределы города"
     *                      ),
     *                     example={"data":
     *                      {{
     *                          "user_id": 18,
     *                          "courier_iiko_id": "b200c40e-8acf-4ba5-b820-58360a855553",
     *                          "kitchen_title": "Кульпарковская",
     *                          "first_name": "Василий",
     *                          "last_name": "Пупкин",
     *                          "count_deliveries": 3,
     *                          "sum_delivery_distance": 10596,
     *                          "sum_return_distance": 10596,
     *                          "orders_within_city": 3,
     *                          "orders_outside_city": 1
     *                      }}
     *                    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     */
    public function distances(DeliveriesIndexRequest $request)
    {
        return new DeliveriesOlapCollection(
            $this->deliveriesOlapRepository->getStatistics($request->validated())
        );
    }

    /**
     * @OA\Get(
     *     path="/olap/deliveries",
     *     tags={"OLAP"},
     *     security={{"Bearer":{}}},
     *     summary="Отчёт по поездкам курьеров",
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Дата доставок от (включительно)",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Дата доставок до (включительно)",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="kitchen_code",
     *         in="query",
     *         description="Код кухни",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Id пользователя-курьера",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
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
     *                         property="delivery_terminal_id",
     *                         type="string",
     *                         description="ID доставочного терминала из айко"
     *                     ),
     *                     @OA\Property(
     *                         property="iiko_courier_id",
     *                         type="string",
     *                         description="ID курьера из айко"
     *                     ),
     *                     @OA\Property(
     *                         property="user_id",
     *                         type="string",
     *                         description="ID пользователя в системе"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         description="Статус поездки"
     *                     ),
     *                     @OA\Property(
     *                         property="delivery_distance",
     *                         type="string",
     *                         description="Дистанция от кухни до адреса последнего доставленного заказа в поездке"
     *                     ),
     *                     @OA\Property(
     *                         property="return_distance",
     *                         type="string",
     *                         description="Дистанция от адреса последнего доставленного заказа в поездке обратно на кухни"
     *                     ),
     *                     @OA\Property(
     *                         property="started_at",
     *                         type="string",
     *                         description="Время начала поездки"
     *                     ),
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                         description="Курьер"
     *                     ),
     *                     @OA\Property(
     *                         property="orders",
     *                         type="array",
     *                          @OA\Items(
     *                              @OA\Property(
     *                                 property="delivery_id",
     *                                 type="integer",
     *                                 description="Id доставки"
     *                              ),
     *                              @OA\Property(
     *                                 property="restaurant",
     *                                 type="string",
     *                                 description="Ресторан"
     *                              ),
     *                              @OA\Property(
     *                                 property="iiko_order_id",
     *                                 type="string",
     *                                 description="UUID заказа в айко"
     *                              ),
     *                              @OA\Property(
     *                                 property="status",
     *                                 type="string",
     *                                 description="Статус заявки"
     *                              ),
     *                              @OA\Property(
     *                                 property="range_type",
     *                                 type="string",
     *                                 description="В пределах города или за пределами"
     *                              ),
     *                              @OA\Property(
     *                                 property="address",
     *                                 type="string",
     *                                 description="Адрес"
     *                              ),
     *                              @OA\Property(
     *                                 property="latitude",
     *                                 type="string",
     *                                 description="Широта"
     *                              ),
     *                              @OA\Property(
     *                                 property="longitude",
     *                                 type="string",
     *                                 description="Долгота"
     *                              ),
     *                              @OA\Property(
     *                                 property="delivered_at",
     *                                 type="string",
     *                                 description="Время доставки"
     *                              ),
     *                          )
     *                      ),
     *                     example={"data":
     *                      {{
     *                          "id": 18,
     *                          "delivery_terminal_id": "aa15c7b2-768f-dbf1-016c-8fc96e6aa61b",
     *                          "iiko_courier_id": "f3b8acfa-da99-454c-9ba4-9220130b85ac",
     *                          "user_id": 21,
     *                          "status": "delivered",
     *                          "delivery_distance": 825541,
     *                          "return_distance": 825541,
     *                          "started_at": "2021-10-20 16:01:06",
     *                          "created_at": "2021-10-20T13:01:06.000000Z",
     *                          "updated_at": "2021-10-20T14:06:15.000000Z",
     *                          "orders":{
     *                              {
     *                                  "id": 32,
     *                                  "delivery_id": 18,
     *                                  "restaurant": "smaki",
     *                                  "iiko_order_id": "49040721-963d-43f8-8bac-8df7f78e54ef",
     *                                  "status": "delivered",
     *                                  "range_type": "within_city",
     *                                  "address": "Львів, Любінська вул. 102",
     *                                  "latitude": "49.8221257",
     *                                  "longitude": "23.9697845",
     *                                  "delivered_at": "2021-10-20 16:02:53",
     *                                  "created_at": "2021-10-20T13:01:06.000000Z",
     *                                  "updated_at": "2021-10-20T13:02:53.000000Z"
     *                              }
     *                          },
     *                          "user":{
     *                              "id": 21,
     *                              "email": "yes7@yes.yes",
     *                              "phone": 380997775573,
     *                              "position": "курьер",
     *                              "first_name": "Василий",
     *                              "last_name": "Пупкин",
     *                              "status": "active",
     *                              "kitchen_code": "vinnytsia",
     *                              "email_verified_at": null,
     *                              "created_at": "2021-08-25T10:19:34.000000Z",
     *                              "updated_at": "2021-09-02T13:13:03.000000Z"
     *                          }
     *                      }}
     *                    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     */
    public function deliveries(DeliveriesDeliveriesRequest $request)
    {
        return new DefaultCollection(
            $this->deliveriesOlapRepository->getDeliveriesStatistics($request->validated())
        );
    }
}
