<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCollection;
use App\Models\Order\Order;

class OrderStatusesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/order-statuses",
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
    public function index()
    {
        return new DefaultCollection(
            collect(Order::STATUSES)
        );
    }
}
