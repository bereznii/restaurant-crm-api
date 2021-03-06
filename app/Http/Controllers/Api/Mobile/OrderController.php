<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Orders\IndexRequest;
use App\Http\Requests\Mobile\Orders\UpdateRequest;
use App\Http\Resources\Mobile\Orders\OrderResource;
use App\Http\Resources\Mobile\Orders\OrdersCollection;
use App\Services\iiko\IikoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
     *     summary="Курьерское приложение. Получить список заказов для доставки",
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
     *                         property="restaurant",
     *                         type="string",
     *                         description="Идентификатор ресторана"
     *                     ),
     *                     @OA\Property(
     *                         property="delivery_terminal_id",
     *                         type="string",
     *                         description="Уникальный идентификатор доставочного терминала в iiko"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         description="Статус заказа"
     *                     ),
     *                     @OA\Property(
     *                         property="order_uuid",
     *                         type="string",
     *                         description="Уникальный идентификатор заказа"
     *                     ),
     *                     @OA\Property(
     *                         property="order_id",
     *                         type="string",
     *                         description="Понятный номер заказа. Может использоваться для передачи клиенту. Уникальность не гарантирована"
     *                     ),
     *                     @OA\Property(
     *                         property="comment",
     *                         type="integer",
     *                         description="Комментарий к заказу"
     *                     ),
     *                     @OA\Property(
     *                         property="expected_delivery_at",
     *                         type="integer",
     *                         description="Время, к которому нужно доставить заказ"
     *                     ),
     *                     @OA\Property(
     *                        property="payment",
     *                        type="object",
     *                        @OA\Property(
     *                            property="code",
     *                            type="string",
     *                            description="Тип оплаты"
     *                        ),
     *                        @OA\Property(
     *                            property="title",
     *                            type="string",
     *                            description="Название типа оплаты"
     *                        ),
     *                        @OA\Property(
     *                            property="sum",
     *                            type="integer",
     *                            description="Сумма к оплате"
     *                        ),
     *                        @OA\Property(
     *                            property="prepareChangeFrom",
     *                            type="integer",
     *                            description="Сдача с"
     *                        ),
     *                     ),
     *                     @OA\Property(
     *                        property="bonusesPayment",
     *                        type="object",
     *                        @OA\Property(
     *                            property="code",
     *                            type="string",
     *                            description="Тип оплаты"
     *                        ),
     *                        @OA\Property(
     *                            property="title",
     *                            type="string",
     *                            description="Название типа оплаты"
     *                        ),
     *                        @OA\Property(
     *                            property="sum",
     *                            type="integer",
     *                            description="Сумма к оплате"
     *                        ),
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
     *                        property="items",
     *                        type="array",
     *                            @OA\Items(
     *                              @OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                                 description="Название позиции"
     *                              ),
     *                              @OA\Property(
     *                                 property="amount",
     *                                 type="integer",
     *                                 description="Количество"
     *                              ),
     *                              @OA\Property(
     *                                 property="sum",
     *                                 type="integer",
     *                                 description="Сумма"
     *                              ),
     *                              @OA\Property(
     *                                 property="comment",
     *                                 type="string",
     *                                 description="Комментарий"
     *                              ),
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
     *                          "restaurant": "smaki",
     *                          "delivery_terminal_id": "018cf4d4-0e07-b00a-0172-e4f18ac4ce92",
     *                          "status": "waiting",
     *                          "order_uuid": "257fd1c0-3015-dc6c-f751-d78dd06b4ef5",
     *                          "order_id": 83112,
     *                          "payment": {
     *                              "code": "CASH",
     *                              "title": "Наличные",
     *                              "sum": 609,
     *                              "prepareChangeFrom": 700,
     *                          },
     *                          "bonusesPayment": {
     *                              "code": "BALL",
     *                              "title": "Оплата бонусами Smaki",
     *                              "sum": 70,
     *                          },
     *                          "comment": "Доставка за 29хв; | Замовлення онлайн | Підготувати решту з: готівка;",
     *                          "expected_delivery_at": "2021-08-27 20:15:18",
     *                          "customer": {
     *                              "name": "Юлія Кухар",
     *                              "phone": "+380974747560"
     *                          },
     *                          "items": {{
     *                              "name": "Преміум Кілограм",
     *                              "amount": 1,
     *                              "sum": 609,
     *                              "comment": null,
     *                          }},
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
     * @throws \Exception
     */
    public function index(IndexRequest $request)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new OrdersCollection(
            $this->iikoService->getOrdersForCourier(Auth::user()->iikoId)
        );
    }

    /**
     * @OA\Patch(
     *     path="/mobile/orders/{order_uuid}",
     *     tags={"Mobile.Orders"},
     *     summary="Курьерское приложение. Изменить статус заказу из поездки",
     *     description="Отметить заказ доставленным или недоставленным. Доступ для пользователей с ролью: <b>Courier</b>",
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="order_uuid",
     *         in="path",
     *         description="UUID заказа",
     *         required=true
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               @OA\Property(
     *                  property="restaurant",
     *                  type="string",
     *                  description="Идентификатор ресторана"
     *               ),
     *               @OA\Property(
     *                  property="latitude",
     *                  type="string",
     *                  description="Широта"
     *               ),
     *               @OA\Property(
     *                  property="longitude",
     *                  type="string",
     *                  description="Долгота"
     *               )
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
     * @param UpdateRequest $request
     * @param string $orderUuid
     * @return OrderResource
     * @throws \Exception
     */
    public function update(UpdateRequest $request, string $orderUuid)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri() . ' : ' . json_encode($request->validated()));

        return new OrderResource(
            $this->iikoService->setOrderDelivered(Auth::user()->iikoId, Auth::id(), $orderUuid, $request->validated())
        );
    }
}
