<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $table = 'checkouts'; 

    protected $fillable = [ 
        'user_id',
        'products',
        'note',
        'total_price',
    ];

    public $timestamps = false; 

}
