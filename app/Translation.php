<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'translation'; 

    protected $fillable = [ 
        'language_id',
        'product_id',
        'name',
        'description',        
    ];

    public $timestamps = false; 
}
