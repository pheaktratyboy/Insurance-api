<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyUserResource extends JsonResource
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
            'id'               => $this->id,
            'company_id'       => $this->company_id,
            'user_id'          => $this->user_id,
            'disabled'         => $this->disabled,

            /** relationship */
            'user'             => new UserAllResource($this->whenLoaded('user')),
            'company'          => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}
