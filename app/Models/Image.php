<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Image extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id', 'image_path', 'parent_id'
    ];

    public function parent()
    {
        return $this->morphTo();
    }
}
