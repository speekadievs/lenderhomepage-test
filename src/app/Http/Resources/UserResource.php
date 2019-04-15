<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Determine if resource sensitive fields should be hidden.
     *
     * @var bool
     */
    protected $hideSensitiveFields = true;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'id'   => $this->id,
            'name' => $this->name,
        ];

        if (!$this->hideSensitiveFields) {
            $resource['email'] = $this->email;
        }

        return $resource;
    }

    /**
     * Include sensitive data.
     *
     * @return $this
     */
    public function withSensitiveData(): UserResource
    {
        $this->hideSensitiveFields = false;

        return $this;
    }
}
