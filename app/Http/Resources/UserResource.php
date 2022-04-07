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
            'full_name'             => $this->full_name,
            'email'                 => $this->email,
            'force_change_password' => $this->force_change_password,
            'activated'             => $this->activated,
            'disabled'              => $this->disabled,
            'activated_at'          => $this->activated_at,

            /** relationship */
            'roles'   => RoleResource::collection($this->whenLoaded('roles')),
            'profile' => new EmployeeResource($this->whenLoaded('profile')),
        ];
    }
}
