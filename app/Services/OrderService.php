<?php

namespace App\Services;

use App\Models\City;
use App\Models\Client\Client;
use App\Models\Order\Order;
use App\Models\Order\OrderAddress;
use App\Models\Order\OrderItem;
use App\Models\Order\OrderPayment;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * @param array $validated
     * @return mixed
     */
    public function store(array $validated)
    {
        DB::beginTransaction();

        try {
            $userId = Auth::id();

            $clientId = $this->saveClient($validated);

            $order = $this->saveOrder($validated, $clientId, $userId);

            $this->savePayments($validated, $order->id);
            $this->saveOrderAddress($validated, $order->id);
            $this->saveOrderItems($validated, $order->id);

            DB::commit();

            $order = Order::where('id', $order->id)
                ->with('items', 'items.product', 'address', 'client', 'payments')
                ->first()->toArray();
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e->getMessage());
            Log::error($e->getMessage());
        }

        return $order ?? null;
    }

    /**
     * @param array $validated
     * @return int
     */
    private function saveClient(array $validated): int
    {
        if (!isset($validated['client']['id'])) {
            // Если id клиента не пришёл, создать клиента и записать его id
            $client = new Client();
            $client->name = $validated['client']['name'];
            $client->phone = $validated['client']['phone'];
            $client->source = $validated['client']['source'];
            $client->is_regular = Client::NOT_REGULAR;
            $client->save();
        } else {
            // Если id клиента пришёл, записать его
            $client = Client::find($validated['client']['id']);

            // Если это не первый заказ клиента и клиент не постоянный, сделать его постоянным
            if ($client->is_regular === Client::NOT_REGULAR) {
                if (Order::where('client_id', '=', $client->id)->count() > 0) {
                    $client->is_regular = Client::REGULAR;
                    $client->save();
                }
            }
        }

        return $client->id;
    }

    /**
     * @param array $validated
     * @param int $clientId
     * @param int $userId
     * @return Order
     */
    private function saveOrder(array $validated, int $clientId, int $userId)
    {
        $order = new Order();
        $order->restaurant = $validated['restaurant'];
        $order->kitchen_code = $validated['kitchen_code'];
        $order->type = $validated['type'];
        $order->operator_id = $userId;
        $order->status = Order::STATUS_NEW;
        $order->return_call = $validated['return_call'];
        $order->courier_id = $validated['courier_id'] ?? null;
        $order->client_comment = $validated['client_comment'];
        $order->client_id = $clientId;
        $order->change_from = $validated['change_from'] ?? null;
        $order->delivered_till = $validated['delivered_till'] ?? now()->addMinutes(30);
        $order->save();

        $this->updateStatusHistory($order, $userId, Order::STATUS_NEW);

        return $order;
    }

    /**
     * @param Order $order
     * @param int $userId
     * @param string $status
     */
    private function updateStatusHistory(Order $order, int $userId, string $status)
    {
        $order->history()->create([
            'status' => $status,
            'user_id' => $userId,
            'set_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param array $validated
     * @param int $orderId
     */
    private function saveOrderAddress(array $validated, int $orderId)
    {
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $orderId;
        $orderAddress->city_sync_id = $validated['address']['city_sync_id'];
        $orderAddress->street = $validated['address']['street'];
        $orderAddress->house_number = $validated['address']['house_number'];
        $orderAddress->entrance = $validated['address']['entrance'];
        $orderAddress->floor = $validated['address']['floor'];
        $orderAddress->apartment = $validated['address']['apartment'] ?? null;
        $orderAddress->comment = $validated['address']['comment'] ?? null;
        $orderAddress->longitude = $validated['address']['longitude'] ?? null;
        $orderAddress->latitude = $validated['address']['latitude'] ?? null;
        $orderAddress->save();
    }

    /**
     * @param array $validated
     * @param int $orderId
     */
    private function saveOrderItems(array $validated, int $orderId)
    {
        foreach ($validated['items'] as $item) {
            $productPrice = ProductPrice::where([
                ['city_sync_id', '=', $validated['address']['city_sync_id']],
                ['product_id', '=', $item['id']]
            ])->first();

            $orderItem = new OrderItem();
            $orderItem->order_id = $orderId;
            $orderItem->product_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->comment = $item['comment'];
            $orderItem->sum = $productPrice->price * $item['quantity'];
            $orderItem->save();
        }
    }

    /**
     * @param array $validated
     * @param int $orderId
     */
    private function savePayments(array $validated, int $orderId)
    {
        foreach ($validated['payments'] as $item) {
            $orderPayment = new OrderPayment();
            $orderPayment->order_id = $orderId;
            $orderPayment->payment_type = $item['payment_type'];
            $orderPayment->sum = $item['sum'];
            $orderPayment->save();
        }
    }

    /**
     * @param array $validated
     * @param Order $order
     * @return array
     */
    public function update(array $validated, Order $order): array
    {
        DB::beginTransaction();

        try {
            $userId = Auth::id();

            $this->updateOrder($validated, $order, $userId);
            $this->updatePayments($validated, $order->id);
            $this->updateOrderAddress($validated, $order->id);
            $this->updateOrderItems($validated, $order->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e->getMessage());
            Log::error($e->getMessage());
        }

        $updatedOrder = Order::with('client', 'address', 'items', 'items.product', 'payments')
            ->where('id', $order->id)
            ->first()->toArray();

        return $updatedOrder;
    }

    /**
     * @param array $validated
     * @param Order $order
     * @param int $userId
     */
    private function updateOrder(array $validated, Order $order, int $userId)
    {
        if ($order->status !== $validated['status']) {
            $this->updateStatusHistory($order, $userId, $validated['status']);
        }

        $order->restaurant = $validated['restaurant'];
        $order->kitchen_code = $validated['kitchen_code'];
        $order->type = $validated['type'];
        $order->status = $validated['status'];
        $order->return_call = $validated['return_call'];
        $order->courier_id = $validated['courier_id'] ?? null;
        $order->client_comment = $validated['client_comment'];

        if ($validated['type'] === Order::TYPE_REQUESTED_TIME) {
            $order->delivered_till = $validated['delivered_till'];
        }

        $order->save();
    }

    /**
     * @param array $validated
     * @param int $orderId
     */
    private function updatePayments(array $validated, int $orderId)
    {
        OrderPayment::where('order_id', $orderId)->delete();

        foreach ($validated['payments'] as $item) {
            $orderPayment = new OrderPayment();
            $orderPayment->order_id = $orderId;
            $orderPayment->payment_type = $item['payment_type'];
            $orderPayment->sum = $item['sum'];
            $orderPayment->save();
        }
    }

    /**
     * @param array $validated
     * @param int $orderId
     */
    private function updateOrderAddress(array $validated, int $orderId)
    {
        $orderAddress = OrderAddress::where('order_id', $orderId)->first();
        $orderAddress->city_sync_id = $validated['address']['city_sync_id'];
        $orderAddress->street = $validated['address']['street'];
        $orderAddress->house_number = $validated['address']['house_number'];
        $orderAddress->entrance = $validated['address']['entrance'];
        $orderAddress->floor = $validated['address']['floor'];
        $orderAddress->apartment = $validated['address']['apartment'] ?? null;
        $orderAddress->comment = $validated['address']['comment'] ?? null;
        $orderAddress->longitude = $validated['address']['longitude'] ?? null;
        $orderAddress->latitude = $validated['address']['latitude'] ?? null;
        $orderAddress->save();
    }

    /**
     * @param array $validated
     * @param int $orderId
     */
    private function updateOrderItems(array $validated, int $orderId)
    {
        $existingItems = OrderItem::where('order_id', $orderId)->get();

        foreach ($validated['items'] as $item) {
            $productPrice = ProductPrice::where([
                ['city_sync_id', '=', $validated['address']['city_sync_id']],
                ['product_id', '=', $item['id']]
            ])->first();

            //Взять товары в заказе
            $itemToUpdate = $existingItems->where('product_id', $item['id'])->first();

            if (!isset($itemToUpdate)) { //Если есть новый - добавить
                $orderItem = new OrderItem();
                $orderItem->order_id = $orderId;
                $orderItem->product_id = $item['id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->comment = $item['comment'];
                $orderItem->sum = $productPrice->price * $item['quantity'];
                $orderItem->save();
            } else { //Есть совпадение - обновить
                $itemToUpdate->quantity = $item['quantity'];
                $itemToUpdate->comment = $item['comment'];
                $itemToUpdate->sum = $productPrice->price * $item['quantity'];
                $itemToUpdate->save();
            }
        }

        //Если нет существующего - удалить
        $receivedItems = array_column($validated['items'], 'id');
        $existingItems = OrderItem::where('order_id', $orderId)->pluck('product_id')->toArray();
        $itemsToDelete = array_diff($existingItems, $receivedItems);

        foreach ($itemsToDelete as $itemToDelete) {
            OrderItem::where([
                    ['order_id', '=', $orderId],
                    ['product_id', '=', $itemToDelete],
                    ['status', '=', OrderItem::STATUS_NEW],
                ])
                ->delete();
        }
    }
}
