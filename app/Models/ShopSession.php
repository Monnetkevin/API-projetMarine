<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'user_id',
        'active',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_shop_sessions', 'shopSession_id', 'product_id');
    }
}
