<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Couriers\CoordinatesRequest;
use App\Http\Resources\DefaultResource;
use App\Repositories\CourierRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @param CourierRepository $courierRepository
     */
    public function __construct(
        private CourierRepository $courierRepository
    ) {}

    /**
     * @OA\Get(
     *     path="/orders/{order_uuid}/courier",
     *     tags={"Orders"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="order_uuid",
     *         in="path",
     *         description="UUID заказа в iiko",
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
     *                         property="latitude",
     *                         type="string",
     *                         description="Широта"
     *                     ),
     *                     @OA\Property(
     *                         property="longitude",
     *                         type="string",
     *                         description="Долгота"
     *                     ),
     *                     example={"data":{
     *                      "latitude": "50.436518",
     *                      "longitude": "30.5502122",
     *                     }}
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param CoordinatesRequest $request
     * @param string $orderUuid
     * @return DefaultResource
     */
    public function courier(CoordinatesRequest $request, string $orderUuid)
    {
        return new DefaultResource(
            $this->courierRepository->getCoordinates($orderUuid)
        );
    }
}
