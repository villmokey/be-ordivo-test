<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'customer_name',
        'customer_address',
        'customer_email',
        'total',
        'transfer_date'
    ];

    public function detail()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
