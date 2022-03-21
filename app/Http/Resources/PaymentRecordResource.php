<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentRecordResource extends JsonResource
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
            "amount" => $this->amount,
            "paid_on" => $this->paid_on->format('Y-m-d'),
            "details" => $this->details,
            "created_at" => $this->created_at->format('Y-m-d'),
        ];
    }
}
