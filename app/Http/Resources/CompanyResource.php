<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name'                  => $this->name,
            'staff_count'           => $this->staff_count,
            'logo'                  => $this->logo,

            /** relationship */
            'employees'             => CompanyUserResource::collection($this->whenLoaded('employees')),
        ];
    }
}
