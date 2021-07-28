<?php

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LocationCollection extends ResourceCollection
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
                /** @var $item Location */
                return array_merge(
                    $item->getAttributes(),
                    ['city' => $item->city->name]
                );
            })
        ];
    }
}
