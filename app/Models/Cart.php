<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'qty',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
