<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCollection;
use App\Models\Order\Order;
use Illuminate\Http\Request;

class OrderStatusesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/order-statuses",
     *     tags={"Order"},
     *     security={{"Bearer":{}}},
     *     summary="Список статусов заказов",
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
     *                         description="Статус"
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="integer",
     *                         description="Название статуса"
     *                     ),
     *                     example={"data":{
     *                          {
     *                              "name": "new",
     *                              "title": "Новый заказ",
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
    public function index(Request $request)
    {
        return new DefaultCollection(
            collect(Order::STATUSES)
        );
    }
}
