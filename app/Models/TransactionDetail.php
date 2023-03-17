<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'transaction_id',
        'product_id',
        'qty',
        'total_price',
        'note'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
