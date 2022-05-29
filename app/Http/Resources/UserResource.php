<?php

namespace App\Http\Resources;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            // Relationship
            // 'role' => $this->whenLoaded('role', function () {
            //     return RoleResource::make(
            //         $this->role
            //     );
            // }),
        ];
    }

    public function with($request)
    {
        return parent::with($request);
    }
}
