<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
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
            'id' => $this->id,
            'description' => $this->description,
            'type' => $this->type,
            'image' => $this->image,
            'status' => $this->status,
            'value' => $this->value,
            'user' => ['id' => $this->user->id, 'name' => $this->user->name],
            'date' => $this->date,
        ];
    }
}