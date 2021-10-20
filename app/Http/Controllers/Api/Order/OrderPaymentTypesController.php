<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCollection;
use App\Models\Order\Order;

class OrderPaymentTypesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/order-payment-types",
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
     *                         description="Тип оплаты"
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="integer",
     *                         description="Название типа оплаты"
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
            collect(Order::PAYMENT_TYPES)
        );
    }
}
