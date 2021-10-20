<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCollection;
use App\Models\Order\Order;
use Illuminate\Http\Request;

class OrderTypesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/order-types",
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
     *                         description="Тип алгоритма"
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="integer",
     *                         description="Название алгоритма"
     *                     ),
     *                     example={"data":{
     *                          {
     *                              "name": "soon",
     *                              "title": "Ближайшее время",
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
            collect(Order::TYPES)
        );
    }
}
