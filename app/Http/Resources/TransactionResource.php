<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category_name' => Str::upper($this->category->name),
            'amount' => '$' . number_format($this->amount / 100, 2),
            'transaction_date' => Carbon::parse($this->transaction_date)->format('m/d/Y'),
            'description' => Str::ucfirst($this->description),
            'created_at' => Carbon::parse($this->created_at)->format('m/d/Y H:i'),
        ];
    }
}
