<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'stock',
        'category', 'image_url', 'origin_city', 'status',
    ];

    protected $casts = ['price' => 'integer', 'stock' => 'integer'];
}
