<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                    => $this->id,
            'username'              => $this->username,
            'email'                 => $this->email,
            'phone_number'          => $this->phone_number,
            'activated_at'          => $this->activated_at,
            'force_change_password' => $this->force_change_password,
            'disabled'              => $this->disabled,

            /** relationship */
            'roles'   => RoleResource::collection($this->whenLoaded('roles')),
            'profile' => new EmployeeResource($this->whenLoaded('profile')),
        ];
    }
}
