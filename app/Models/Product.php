<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_content',
        'price',
        'quantity',
        'category_id',
        'stripe_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function shopSessions()
    {
        return $this->belongsToMany(ShopSession::class, 'products_shop_sessions', 'product_id', 'shopSession_id');
    }
}
