<?php

namespace App\Http\Resources;

use App\Http\Resources\TransactionsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'scope' => $this->role == 0 ? 'admin' : 'user',
            'transactions' => TransactionsResource::collection($this->transactions),
            'incomes' => $this->incomes,
            'expenses' => $this->expenses,
            'balance' => $this->balance,
        ];
    }
}