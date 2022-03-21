<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "name" => $this->name
        ];

        if ($this->relationLoaded('children')) {
            $result['children'] = self::collection($this->children);
        }

        return $result;
    }
}
