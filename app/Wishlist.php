<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists'; 

    protected $fillable = [ 
        'user_id', 
        'product_id',
    ];
    public $timestamps = false; 

    static function checkWishlist($product_id, $user_id)
    {
        return self::where('product_id', $product_id)->where('user_id',$user_id)->first();
    }
}
