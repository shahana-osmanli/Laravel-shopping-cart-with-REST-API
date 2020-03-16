<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts'; 

    protected $fillable = [ 
        'user_id', 
        'product_id',
        'quantity',
    ];

    public $timestamps = false; 
}
