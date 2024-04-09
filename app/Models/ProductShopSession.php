<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductShopSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_quantity',
        'product_id',
        'shopSession_id',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function shopSession()
    {
        return $this->hasMany(ShopSession::class);
    }
}
