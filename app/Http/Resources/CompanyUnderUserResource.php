<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyUnderUserResource extends JsonResource
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
            'id'                    => $this->company->id,
            'name'                  => $this->company->name,
            'staff_count'           => $this->company->staff_count,
            'subscriber_count'      => $this->company->subscriber_count,
            'logo'                  => $this->company->logo,
            'disabled'              => $this->company->disabled,
        ];
    }
}
