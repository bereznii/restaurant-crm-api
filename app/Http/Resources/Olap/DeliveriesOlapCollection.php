<?php

namespace App\Http\Resources\Olap;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DeliveriesOlapCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item, $key) {
                return [
                    'user_id' => $item->user_id,
                    'courier_iiko_id' => $item->courier_iiko_id,
                    'kitchen_title' => $item->kitchen_title,
                    'first_name' => $item->first_name,
                    'last_name' => $item->last_name,
                    'count_deliveries' => (int) ($item->count_deliveries ?? 0),
                    'sum_delivery_distance' => (int) ($item->sum_delivery_distance ?? 0),
                    'sum_return_distance' => (int) ($item->sum_return_distance ?? 0),
                    'orders_within_city' => (int) ($item->orders_within_city ?? 0),
                    'orders_outside_city' => (int) ($item->orders_outside_city ?? 0),
                ];
            }),
        ];
    }
}
