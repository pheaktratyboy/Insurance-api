<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExportSubscriberReportResource extends JsonResource
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
            'name_kh'           => $this->name_kh,
            'name_en'           => $this->name_en,
            'identity_number'   => $this->identity_number,
            'date_of_birth'     => $this->date_of_birth,
            'phone_number'      => $this->phone_number,
            'address'           => $this->address,
            'place_of_birth'    => $this->place_of_birth,
            'gender'            => $this->gender,
            'religion'          => $this->religion,
            'status'            => $this->status,
        ];
    }
}
