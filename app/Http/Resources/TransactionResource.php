<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = [
            "id" => $this->id,
            "total" => floatVal($this->total),
            "due_on" => $this->due_on->format('Y-m-d'),
            "vat" => (float) $this->vat,
            "is_vat_inclusive" => (boolean) $this->is_vat_inclusive,
        ];

        if ($this->relationLoaded('records')) {
            $result['records'] = PaymentRecordResource::collection($this->records);
        }

        if ($this->relationLoaded('category')) {
            $result['category'] = new CategoryResource($this->category);
        }

        if ($this->relationLoaded('subCategory')) {
            $result['subCategory'] = new CategoryResource($this->subCategory);
        }

        if ($this->relationLoaded('payer')) {
            $result['payer'] = new UserResource($this->payer);
        }

        return $result;
    }
}
