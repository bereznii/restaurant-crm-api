<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Deliveries\StoreRequest;
use App\Http\Resources\Mobile\Deliveries\DeliveryResource;
use App\Services\iiko\DeliveryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    /**
     * @param DeliveryService $deliveryService
     */
    public function __construct(
        private DeliveryService $deliveryService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/mobile/deliveries",
     *     tags={"Mobile.Deliveries"},
     *     description="Создать доставку (поездку). Доступ для пользователей с ролью: <b>Courier</b>",
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="orders",
     *                      type="array",
     *                      description="Массив сущностей заказов в доставке",
     *                      @OA\Items(
     *                          required={"restaurant","order_uuid","address"},
     *                          @OA\Property(
     *                              property="restaurant",
     *                              type="string",
     *                              description="Идентификатор ресторана",
     *                              example="smaki",
     *                          ),
     *                          @OA\Property(
     *                              property="order_uuid",
     *                              type="string",
     *                              description="Уникальный идентификатор заказа",
     *                              example="446cbc3d-325c-8bef-d629-0503ba247f40"
     *                          ),
     *                          @OA\Property(
     *                              property="address",
     *                              type="object",
     *                              description="Объект адреса, полученный по адресу GET /api/mobile/orders",
     *                              required={"city","street","home"},
     *                              @OA\Property(
     *                                 property="city",
     *                                 type="string",
     *                                 description="Наименование города",
     *                                 example="Львів"
     *                              ),
     *                              @OA\Property(
     *                                 property="street",
     *                                 type="string",
     *                                 description="Наименование улицы",
     *                                 example="Зелена вул."
     *                              ),
     *                              @OA\Property(
     *                                 property="index",
     *                                 type="string",
     *                                 description="Индекс улицы в адресе, если есть",
     *                                 example=""
     *                              ),
     *                              @OA\Property(
     *                                 property="home",
     *                                 type="string",
     *                                 description="Дом",
     *                                 example="105"
     *                              ),
     *                              @OA\Property(
     *                                 property="housing",
     *                                 type="string",
     *                                 description="Корпус",
     *                                 example=""
     *                              ),
     *                              @OA\Property(
     *                                 property="apartment",
     *                                 type="string",
     *                                 description="Квартира",
     *                                 example=""
     *                              ),
     *                              @OA\Property(
     *                                 property="entrance",
     *                                 type="string",
     *                                 description="Подъезд",
     *                                 example=""
     *                              ),
     *                              @OA\Property(
     *                                 property="floor",
     *                                 type="string",
     *                                 description="Этаж",
     *                                 example=""
     *                              ),
     *                              @OA\Property(
     *                                 property="comment",
     *                                 type="string",
     *                                 description="Дополнительная информация",
     *                                 example=""
     *                              ),
     *                          ),
     *                      ),
     *                  ),
     *             )
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
     *                         property="data",
     *                         type="object",
     *                         @OA\Property(
     *                             property="success",
     *                             type="boolean"
     *                         )
     *                     ),
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param StoreRequest $request
     * @return DeliveryResource
     */
    public function store(StoreRequest $request)
    {
        return new DeliveryResource(
            $this->deliveryService->store(Auth::user()->iikoId, Auth::id(), $request->validated())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
