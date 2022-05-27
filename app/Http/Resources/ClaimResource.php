<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClaimResource extends JsonResource
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
            'subject'               => $this->subject,
            'note'                  => $this->note,
            'status'                => $this->status,
            'attachments'           => $this->attachments,
            'subscriber_id'         => $this->subscriber_id,
            'claimed_at'            => $this->claimed_at,
            'created_at'            => $this->created_at,
            'accident_type'         => $this->accident_type,

            /** relationship */
            'subscriber'            => new SubscriberResource($this->whenLoaded('subscriber')),
        ];
    }
}
