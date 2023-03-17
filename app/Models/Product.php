<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'price',
        'description',
        'stock',
        'image_url'
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'parent');
    }

    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class);
    }
    
}
