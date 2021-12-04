<?php

namespace App\Http\Controllers\Api\Olap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Olap\DeliveriesIndexRequest;
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
     *     path="/olap/deliveries",
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
    public function index(DeliveriesIndexRequest $request)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new DeliveriesOlapCollection(
            $this->deliveriesOlapRepository->getStatistics($request->validated())
        );
    }
}
