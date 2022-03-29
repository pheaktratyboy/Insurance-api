<?php

namespace App\Http\Resources;

use App\Models\SubscriberPolicy;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
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
            'name_kh'               => $this->name_kh,
            'name_en'               => $this->name_en,
            'identity_number'       => $this->identity_number,
            'date_of_birth'         => $this->date_of_birth,
            'primary_phone'         => $this->primary_phone,
            'address'               => $this->address,
            'place_of_birth'        => $this->place_of_birth,
            'gender'                => $this->gender,
            'category'              => $this->category,
            'avatar_url'            => $this->avatar_url,
            'created_at'            => $this->created_at,

            /** relationship */
            'policies'              => SubscriberPolicyResource::collection($this->whenLoaded('policies')),
        ];
    }
}
