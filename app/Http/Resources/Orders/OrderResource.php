<?php

namespace App\Http\Resources\Orders;

use App\Models\Order\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Order $order */
        $order = $this->resource;

        foreach ($order['items'] as &$item) {
            $item['id'] = $item['product_id'];
            unset($item['product_id']);
        }

        return $order;
    }
}
