<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberPolicyResource extends JsonResource
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
            'id'                  => $this->id,
            'subscriber_id'       => $this->policy_id,
            'policy_id'           => $this->policy_id,
            'payment_method'      => $this->payment_method,

            /** relationship */
            'policy'              => new PolicyResource($this->whenLoaded('policy')),
        ];
    }
}
