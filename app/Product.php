<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products'; 

    protected $fillable = [ 
        'user_id',
        'name', 
        'description', 
        'price',
        
    ];

    public $timestamps = false; 
}
