<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        // return [
        //     'id'          => $this->id,
        //     'user_id'       => $this->user_id,
        //     'website_id'       => $this->website_id,
        //     'created_at'  => $this->created_at->format('d-m-Y')
        // ];
    }
}
