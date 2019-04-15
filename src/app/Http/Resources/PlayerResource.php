<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'full_name'  => $this->full_name,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'image_url'  => $this->image_url
        ];
    }
}
