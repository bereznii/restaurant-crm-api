<?php

namespace App\Http\Resources\Orders;

use App\Models\Order\Order;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrdersCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($groupItems, $key) {
                foreach ($groupItems as &$order) {
                    /** @var $order Order */
                    foreach ($order['items'] as &$item) {
                        $item['id'] = $item['product_id'];
                        unset($item['product_id']);
                    }
                }
                return $groupItems;
            })
        ];
    }
}
