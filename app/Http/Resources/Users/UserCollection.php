<?php

namespace App\Http\Resources\Users;

use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
                /** @var $item User */
                return [
                    'id' => $item->id,
                    'email' => $item->email,
                    'phone' => $item->phone,
                    'position' => $item->position,
                    'first_name' => $item->first_name,
                    'last_name' => $item->last_name,
                    'status' => $item->status,
                    'role_name' => $item->roles[0]->name,
                    'role_title' => $item->roles[0]->title,
                    'email_verified_at' => $item->email_verified_at,
                    'locations' => $item->locations,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            })
        ];
    }
}
