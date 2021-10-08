<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DefaultMediaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item, $key) {
                /** @var $item Media */
                return [
                    'product_id' => $item->model_id,
                    'file_name' => $item->file_name,
                    'name' => $item->name,
                    'mime_type' => $item->mime_type,
                    'size' => $item->size,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'url' => $item->getFullUrl(),
                ];
            }),
        ];
    }
}
