<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products'; 

    protected $fillable = [ 
        'user_id',
        'price',
    ];

    public $timestamps = false; 

    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
