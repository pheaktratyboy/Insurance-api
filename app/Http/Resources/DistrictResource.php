<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
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
            'id'              => $this->id,
            'municipality_id' => $this->municipality_id,
            'name'            => $this->name,

            /** relationship */
            'municipality' => new MunicipalityResource($this->whenLoaded('municipality')),
        ];
    }
}
