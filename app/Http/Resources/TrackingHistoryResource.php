<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackingHistoryResource extends JsonResource
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
            'id'           => $this->id,
            'type'         => $this->type,
            'model'        => $this->model_type,
            'data'         => $this->data,
            'reference_id' => $this->reference_id,
            'user_id'      => $this->user_id,

            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,

            /** relationship */
            'user'         => new UserResource($this->whenLoaded('user')),
        ];
    }
}
