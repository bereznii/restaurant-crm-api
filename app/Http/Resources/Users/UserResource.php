<?php

namespace App\Http\Resources\Users;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userRecourse = [
            'id' => $this->id,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->position,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'status' => $this->status,
            'role_name' => $this->roles[0]->name,
            'role_title' => $this->roles[0]->title,
            'email_verified_at' => $this->email_verified_at,
            'locations' => $this->locations,
            'kitchen_code' => $this->kitchen_code,
            'kitchen_name' => $this->kitchen->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if (isset($this->iiko)) {
            $userRecourse['iiko']['iiko_id'] = $this->iiko->iiko_id;
            $userRecourse['iiko']['created_at'] = $this->iiko->created_at;
            $userRecourse['iiko']['updated_at'] = $this->iiko->updated_at;
        }

        if ($this->roles->where('name', User::ROLE_COOK)->count() > 0) {
            $userRecourse['product_types'] = $this->productTypes->pluck('product_type_sync_id')->toArray();
        }

        return $userRecourse;
    }
}
