<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Orders\IndexRequest;
use App\Http\Resources\Mobile\Orders\OrdersCollection;
use App\Services\iiko\IikoService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @param IikoService $iikoService
     */
    public function __construct(
        private IikoService $iikoService
    ) {}

    /**
     * @OA\Get(
     *     path="/mobile/orders",
     *     tags={"Mobile.Orders"},
     *     description="Доступ для пользователей с ролью: <b>Courier</b>",
     *     security={{"Bearer":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="statusTitle",
     *                         type="string",
     *                         description="Название статуса заказа"
     *                     ),
     *                     @OA\Property(
     *                         property="orderUuid",
     *                         type="string",
     *                         description="Уникальный идентификатор заказа"
     *                     ),
     *                     @OA\Property(
     *                         property="orderId",
     *                         type="string",
     *                         description="Понятный номер заказа. Может использоваться для передачи клиенту. Уникальность не гарантирована"
     *                     ),
     *                     @OA\Property(
     *                         property="paymentTitle",
     *                         type="string",
     *                         description="Название типа оплаты"
     *                     ),
     *                     @OA\Property(
     *                         property="paymentSum",
     *                         type="integer",
     *                         description="Сумма к оплате"
     *                     ),
     *                     @OA\Property(
     *                         property="comment",
     *                         type="integer",
     *                         description="Комментарий к заказу"
     *                     ),
     *                     @OA\Property(
     *                        property="customer",
     *                        type="object",
     *                        @OA\Property(
     *                           property="name",
     *                           type="string",
     *                           description="Имя клиента"
     *                        ),
     *                        @OA\Property(
     *                           property="phone",
     *                           type="string",
     *                           description="Телефон клиента доставки"
     *                        ),
     *                     ),
     *                     @OA\Property(
     *                        property="address",
     *                        type="object",
     *                        @OA\Property(
     *                           property="city",
     *                           type="string",
     *                           description="Наименование города"
     *                        ),
     *                        @OA\Property(
     *                           property="street",
     *                           type="string",
     *                           description="Наименование улицы"
     *                        ),
     *                        @OA\Property(
     *                           property="index",
     *                           type="string",
     *                           description="Индекс улицы в адресе, если есть"
     *                        ),
     *                        @OA\Property(
     *                           property="home",
     *                           type="string",
     *                           description="Дом"
     *                        ),
     *                        @OA\Property(
     *                           property="housing",
     *                           type="string",
     *                           description="Корпус"
     *                        ),
     *                        @OA\Property(
     *                           property="apartment",
     *                           type="string",
     *                           description="Квартира"
     *                        ),
     *                        @OA\Property(
     *                           property="entrance",
     *                           type="string",
     *                           description="Подъезд"
     *                        ),
     *                        @OA\Property(
     *                           property="floor",
     *                           type="string",
     *                           description="Этаж"
     *                        ),
     *                        @OA\Property(
     *                           property="comment",
     *                           type="string",
     *                           description="Дополнительная информация"
     *                        ),
     *                     ),
     *                     example={"data":{
     *                      {
     *                          "statusTitle": "В пути",
     *                          "orderUuid": "257fd1c0-3015-dc6c-f751-d78dd06b4ef5",
     *                          "orderId": 83112,
     *                          "paymentTitle": "Наличные",
     *                          "paymentSum": 609,
     *                          "comment": "Доставка за 29хв; | Замовлення онлайн | Підготувати решту з: готівка;",
     *                          "customer": {
     *                              "name": "Юлія Кухар",
     *                              "phone": "+380974747560"
     *                          },
     *                          "address": {
     *                              "city": "Львів",
     *                              "street": "Чернівецька вул.",
     *                              "index": "",
     *                              "home": "11",
     *                              "housing": "",
     *                              "apartment": "",
     *                              "entrance": "",
     *                              "floor": "2",
     *                              "comment": "Львів, Чернівецька , дім 11, поверх 2\nЛьвів Чернівецька 11 2"
     *                          }
     *                      }}
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
            $this->iikoService->getOrdersForCourier()
        );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
