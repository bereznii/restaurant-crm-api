<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderItems\IndexRequest;
use App\Http\Requests\Orders\OrderItems\UpdateRequest;
use App\Http\Resources\DefaultCollection;
use App\Http\Resources\DefaultResource;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Repositories\OrderItemsRepository;
use App\Repositories\OrderRepository;
use App\Services\OrderItemsService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderItemsController extends Controller
{
    /**
     * @param OrderItemsRepository $orderItemsRepository
     * @param OrderItemsService $orderItemsService
     */
    public function __construct(
        private OrderItemsRepository $orderItemsRepository,
        private OrderItemsService $orderItemsService
    ) {}

    /**
     * @OA\Get(
     *     path="/order-items",
     *     tags={"Order.Items"},
     *     security={{"Bearer":{}}},
     *     description="Доступна поварам. Выдает позиции для приготовления в зависимости от типа товара и кухни повара",
     *     summary="Список позиций из заказов для приготовления",
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         description="ID"
     *                     ),
     *                     @OA\Property(
     *                         property="order_id",
     *                         type="integer",
     *                         description="ID заказа"
     *                     ),
     *                     @OA\Property(
     *                         property="product_id",
     *                         type="integer",
     *                         description="ID товара"
     *                     ),
     *                     @OA\Property(
     *                         property="quantity",
     *                         type="integer",
     *                         description="Количество"
     *                     ),
     *                     @OA\Property(
     *                         property="sum",
     *                         type="integer",
     *                         description="Стоимость"
     *                     ),
     *                     @OA\Property(
     *                         property="comment",
     *                         type="string",
     *                         description="Комментарий"
     *                     ),
     *                     @OA\Property(
     *                         property="cook_id",
     *                         type="integer",
     *                         description="ID повара"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         description="Статус"
     *                     ),
     *                     @OA\Property(
     *                         property="product",
     *                         type="object",
     *                         description="Объект товара"
     *                     ),
     *                     example={"data":{
     *                          {
     *                          "id": 28,
     *                          "order_id": 21,
     *                          "product_id": "88526c81-9dbd-4d8b-91df-6b12cebf5c0a",
     *                          "quantity": 2,
     *                          "sum": 150,
     *                          "comment": null,
     *                          "created_at": "2021-10-25T20:32:16.000000Z",
     *                          "updated_at": "2021-10-25T20:32:16.000000Z",
     *                          "cook_id": null,
     *                          "status": "new",
     *                          "product": {
     *                              "id": "88526c81-9dbd-4d8b-91df-6b12cebf5c0a",
     *                              "restaurant": "smaki",
     *                              "article": "art-2",
     *                              "title_ua": "Саке макі",
     *                              "title_ru": "Саке макі",
     *                              "weight": 250,
     *                              "weight_type": "г",
     *                              "category_sync_id": null,
     *                              "type_sync_id": "sushi",
     *                              "description_ua": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse blandit hendrerit mi eget aliquam. Mauris volutpat sem augue, quis tincidunt leo vehicula ut. Pellentesque convallis vel eros vitae luctus. Curabitur commodo fringilla risus, consectetur varius enim porttitor vel. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus magna sem, efficitur quis interdum quis, viverra in purus. Sed finibus odio euismod felis gravida mollis. Praesent ante sapien, ornare sed consectetur at, euismod eget nulla. Suspendisse accumsan imperdiet sagittis. Maecenas quis mi tortor. Integer vel ante ut nisi pulvinar tincidunt id sed metus. Nunc non erat ut tortor finibus gravida quis quis magna. Suspendisse varius odio sed leo scelerisque pretium. Praesent auctor odio sit amet leo posuere, nec tempus risus tincidunt.",
     *                              "description_ru": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse blandit hendrerit mi eget aliquam. Mauris volutpat sem augue, quis tincidunt leo vehicula ut. Pellentesque convallis vel eros vitae luctus. Curabitur commodo fringilla risus, consectetur varius enim porttitor vel. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus magna sem, efficitur quis interdum quis, viverra in purus. Sed finibus odio euismod felis gravida mollis. Praesent ante sapien, ornare sed consectetur at, euismod eget nulla. Suspendisse accumsan imperdiet sagittis. Maecenas quis mi tortor. Integer vel ante ut nisi pulvinar tincidunt id sed metus. Nunc non erat ut tortor finibus gravida quis quis magna. Suspendisse varius odio sed leo scelerisque pretium. Praesent auctor odio sit amet leo posuere, nec tempus risus tincidunt.",
     *                              "created_at": "2021-10-01T16:25:57.000000Z",
     *                              "updated_at": "2021-10-01T16:25:57.000000Z",
     *                              "image": "http://smaki.local/images/default.jpg",
     *                              "media": {}
     *                              }
     *                          },
     *                          "links": {
     *                              "first": "https://api.smaki.com.ua/api/orders?page=1",
     *                              "last": "https://api.smaki.com.ua/api/orders?page=1",
     *                              "prev": null,
     *                              "next": null
     *                          },
     *                          "meta": {
     *                              "current_page": 1,
     *                              "from": 1,
     *                              "last_page": 1,
     *                              "links": {},
     *                              "path": "https://api.smaki.com.ua/api/orders",
     *                              "per_page": 20,
     *                              "to": 11,
     *                              "total": 11,
     *                          },
     *                      }}
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return DefaultCollection
     */
    public function index(IndexRequest $request)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new DefaultCollection(
            $this->orderItemsRepository->index($request->validated())
        );
    }

    /**
     * @OA\Patch(
     *     path="/order-items/{id}",
     *     tags={"Order.Items"},
     *     security={{"Bearer":{}}},
     *     description="Доступна поварам",
     *     summary="Редактирование позиций в заказе",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID позиции заказа",
     *         required=true
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               @OA\Property(
     *                  property="status",
     *                  type="string",
     *                  description="Статус"
     *               ),
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
     *                         property="id",
     *                         type="integer",
     *                         description="ID"
     *                     ),
     *                     @OA\Property(
     *                         property="order_id",
     *                         type="integer",
     *                         description="ID заказа"
     *                     ),
     *                     @OA\Property(
     *                         property="product_id",
     *                         type="integer",
     *                         description="ID товара"
     *                     ),
     *                     @OA\Property(
     *                         property="quantity",
     *                         type="integer",
     *                         description="Количество"
     *                     ),
     *                     @OA\Property(
     *                         property="sum",
     *                         type="integer",
     *                         description="Стоимость"
     *                     ),
     *                     @OA\Property(
     *                         property="comment",
     *                         type="string",
     *                         description="Комментарий"
     *                     ),
     *                     @OA\Property(
     *                         property="cook_id",
     *                         type="integer",
     *                         description="ID повара"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         description="Статус"
     *                     ),
     *                     @OA\Property(
     *                         property="product",
     *                         type="object",
     *                         description="Объект товара"
     *                     ),
     *                     example={"data":{
     *                          "id": 28,
     *                          "order_id": 21,
     *                          "product_id": "88526c81-9dbd-4d8b-91df-6b12cebf5c0a",
     *                          "quantity": 2,
     *                          "sum": 150,
     *                          "comment": null,
     *                          "created_at": "2021-10-25T20:32:16.000000Z",
     *                          "updated_at": "2021-10-25T20:32:16.000000Z",
     *                          "cook_id": null,
     *                          "status": "new",
     *                          "product": {
     *                              "id": "88526c81-9dbd-4d8b-91df-6b12cebf5c0a",
     *                              "restaurant": "smaki",
     *                              "article": "art-2",
     *                              "title_ua": "Саке макі",
     *                              "title_ru": "Саке макі",
     *                              "weight": 250,
     *                              "weight_type": "г",
     *                              "category_sync_id": null,
     *                              "type_sync_id": "sushi",
     *                              "description_ua": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse blandit hendrerit mi eget aliquam. Mauris volutpat sem augue, quis tincidunt leo vehicula ut. Pellentesque convallis vel eros vitae luctus. Curabitur commodo fringilla risus, consectetur varius enim porttitor vel. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus magna sem, efficitur quis interdum quis, viverra in purus. Sed finibus odio euismod felis gravida mollis. Praesent ante sapien, ornare sed consectetur at, euismod eget nulla. Suspendisse accumsan imperdiet sagittis. Maecenas quis mi tortor. Integer vel ante ut nisi pulvinar tincidunt id sed metus. Nunc non erat ut tortor finibus gravida quis quis magna. Suspendisse varius odio sed leo scelerisque pretium. Praesent auctor odio sit amet leo posuere, nec tempus risus tincidunt.",
     *                              "description_ru": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse blandit hendrerit mi eget aliquam. Mauris volutpat sem augue, quis tincidunt leo vehicula ut. Pellentesque convallis vel eros vitae luctus. Curabitur commodo fringilla risus, consectetur varius enim porttitor vel. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus magna sem, efficitur quis interdum quis, viverra in purus. Sed finibus odio euismod felis gravida mollis. Praesent ante sapien, ornare sed consectetur at, euismod eget nulla. Suspendisse accumsan imperdiet sagittis. Maecenas quis mi tortor. Integer vel ante ut nisi pulvinar tincidunt id sed metus. Nunc non erat ut tortor finibus gravida quis quis magna. Suspendisse varius odio sed leo scelerisque pretium. Praesent auctor odio sit amet leo posuere, nec tempus risus tincidunt.",
     *                              "created_at": "2021-10-01T16:25:57.000000Z",
     *                              "updated_at": "2021-10-01T16:25:57.000000Z",
     *                              "image": "http://smaki.local/images/default.jpg",
     *                              "media": {}
     *                          },
     *                          "links": {
     *                              "first": "https://api.smaki.com.ua/api/orders?page=1",
     *                              "last": "https://api.smaki.com.ua/api/orders?page=1",
     *                              "prev": null,
     *                              "next": null
     *                          },
     *                          "meta": {
     *                              "current_page": 1,
     *                              "from": 1,
     *                              "last_page": 1,
     *                              "links": {},
     *                              "path": "https://api.smaki.com.ua/api/orders",
     *                              "per_page": 20,
     *                              "to": 11,
     *                              "total": 11,
     *                          },
     *                      }}
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param UpdateRequest $request
     * @param OrderItem $orderItem
     * @return DefaultResource
     */
    public function update(UpdateRequest $request, OrderItem $orderItem)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new DefaultResource(
            $this->orderItemsService->update($request->validated(), $orderItem, Auth::id())
        );
    }

    /**
     * @OA\Get(
     *     path="/order-items-statuses",
     *     tags={"Order.Items"},
     *     summary="Статусы позиций в заказе",
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
     *                          },
     *                          {
     *                              "name": "in_process",
     *                              "title": "Готовится",
     *                          },
     *                          {
     *                              "name": "ready",
     *                              "title": "Приготовлен",
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
    public function statuses(Request $request)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new DefaultCollection(
            collect(OrderItem::STATUSES)
        );
    }
}
