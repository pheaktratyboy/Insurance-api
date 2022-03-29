<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'id'                => $this->id,
            'name_kh'           => $this->name_kh,
            'name_en'           => $this->name_en,
            'identity_number'   => $this->identity_number,
            'date_of_birth'     => $this->date_of_birth,
            'primary_phone'     => $this->primary_phone,
            'gender'            => $this->gender,
            'place_of_birth'    => $this->place_of_birth,
            'category'          => $this->category,
            'avatar_url'        => $this->avatar_url,

            'commission'        => $this->commission,
            'kpi'               => $this->kpi,

            'municipality_id'   => $this->municipality_id,
            'district_id'       => $this->district_id,

            /** relationship */
            'user'              => new UserResource($this->whenLoaded('user')),
            'municipality'      => new MunicipalityResource($this->whenLoaded('municipality')),
            'district'          => new DistrictResource($this->whenLoaded('district')),
        ];
    }
}
