<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'category',
        'description',
        'image',
        'stock_small',
        'stock_medium',
        'stock_large',
        'price_small',
        'price_medium',
        'price_large',
    ];
}
