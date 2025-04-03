<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'payer' => $this->payer_id,
            'payee' => $this->payee_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
