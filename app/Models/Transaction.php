<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'transaction_date',
        'amount',
        'description',
        'user_id',
    ];

    protected $dates = ['transaction_date', 'created_at'];



    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 100;
    }

    public function setTransactionDateAttribute($value)
    {
        $this->attributes['transaction_date'] = Carbon::parse($value)->format('Y-m-d');
    }



    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
